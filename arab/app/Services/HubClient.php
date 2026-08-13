<?php

namespace App\Services;

use App\Jobs\RetryHubEventJob;
use App\Jobs\RetryHubLeadJob;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class HubClient
{
    public const EVENT_TYPES = [
        'page_view',
        'cta_click',
        'calculator_use',
        'contact_submit',
        'whitepaper_download',
    ];

    public function content(?string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        $cacheKey = sprintf('hub.content.%s.%s', config('site.key'), $locale);
        $ttl = (int) config('site.content_cache_ttl', 600);

        return Cache::remember($cacheKey, $ttl, function () use ($locale) {
            try {
                $response = $this->http()
                    ->get('/api/v1/content', ['locale' => $locale])
                    ->throw()
                    ->json();

                return $this->normalizeContentPayload($response, $locale);
            } catch (Throwable $e) {
                Log::warning('Hub content fetch failed; using local fallback.', [
                    'locale' => $locale,
                    'message' => $e->getMessage(),
                ]);

                return $this->fallbackContent($locale);
            }
        });
    }

    public function forgetContentCache(?string $locale = null): void
    {
        $locales = $locale ? [$locale] : config('site.locales', ['ar', 'en']);

        foreach ($locales as $loc) {
            Cache::forget(sprintf('hub.content.%s.%s', config('site.key'), $loc));
        }
    }

    /**
     * Best-effort lead POST. On failure, queue a local retry job.
     *
     * @param  array<string, mixed>  $payload
     */
    public function submitLead(array $payload): bool
    {
        $payload = $this->withLocale($payload);

        try {
            $this->http()
                ->post('/api/v1/leads', $payload)
                ->throw();

            return true;
        } catch (Throwable $e) {
            Log::warning('Hub lead submit failed; queueing retry.', [
                'message' => $e->getMessage(),
            ]);

            try {
                RetryHubLeadJob::dispatch($payload);
            } catch (Throwable $dispatchError) {
                Log::error('Hub lead retry dispatch failed.', [
                    'message' => $dispatchError->getMessage(),
                ]);
            }

            return false;
        }
    }

    /**
     * Queue a first-party analytics event (never blocks the page render).
     *
     * @param  array<string, mixed>  $payload
     */
    public function trackEvent(string $eventType, array $payload = []): bool
    {
        if (! in_array($eventType, self::EVENT_TYPES, true)) {
            Log::warning('Unknown hub event type ignored.', [
                'event_type' => $eventType,
            ]);

            return false;
        }

        $payload = $this->withLocale(array_merge($payload, [
            'event_type' => $eventType,
            'path' => $payload['path'] ?? request()?->getPathInfo(),
        ]));

        try {
            RetryHubEventJob::dispatch($payload);

            return true;
        } catch (Throwable $e) {
            Log::warning('Hub event queue dispatch failed; attempting inline send.', [
                'event_type' => $eventType,
                'message' => $e->getMessage(),
            ]);

            try {
                $this->trackEventOrFail($payload);

                return true;
            } catch (Throwable $inlineError) {
                Log::error('Hub event submit failed.', [
                    'event_type' => $eventType,
                    'message' => $inlineError->getMessage(),
                ]);

                return false;
            }
        }
    }

    /**
     * Used by retry jobs — throws so the queue can back off.
     *
     * @param  array<string, mixed>  $payload
     *
     * @throws ConnectionException|RequestException
     */
    public function submitLeadOrFail(array $payload): void
    {
        $this->http()
            ->post('/api/v1/leads', $this->withLocale($payload))
            ->throw();
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ConnectionException|RequestException
     */
    public function trackEventOrFail(array $payload): void
    {
        $this->http()
            ->post('/api/v1/events', $this->withLocale($payload))
            ->throw();
    }

    /**
     * @return array<string, mixed>
     */
    public function settings(?string $locale = null): array
    {
        $content = $this->content($locale);

        return $content['site']['settings'] ?? config('site.settings', []);
    }

    /**
     * @return array<string, mixed>
     */
    public function section(string $key, ?string $locale = null): array
    {
        $content = $this->content($locale);

        return $content['sections'][$key] ?? [];
    }

    protected function http()
    {
        $token = (string) config('site.secret');

        return Http::baseUrl((string) config('site.hub_url'))
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('site.http_timeout', 5))
            ->retry((int) config('site.http_retries', 2), 200, throw: false)
            ->withToken($token)
            ->withHeaders([
                'X-Site-Key' => (string) config('site.key'),
            ]);
    }

    /**
     * @param  array<string, mixed>|null  $response
     * @return array{site: array<string, mixed>, sections: array<string, array<string, mixed>>, source: string}
     */
    protected function normalizeContentPayload(?array $response, string $locale): array
    {
        if (! is_array($response) || ! isset($response['sections'])) {
            return $this->fallbackContent($locale);
        }

        $sections = [];
        foreach ($response['sections'] as $key => $payload) {
            if (! is_array($payload)) {
                continue;
            }

            // Hub returns a list of {section_key, payload, ...}
            if (isset($payload['section_key'])) {
                $sections[(string) $payload['section_key']] = is_array($payload['payload'] ?? null)
                    ? $payload['payload']
                    : $payload;

                continue;
            }

            // Already keyed by section_key
            if (is_string($key)) {
                $sections[$key] = $payload;
            }
        }

        $fallback = $this->fallbackContent($locale);
        $hubSite = is_array($response['site'] ?? null) ? $response['site'] : [];
        $mergedSite = array_merge($fallback['site'], $hubSite);
        $mergedSite['settings'] = array_replace_recursive(
            $fallback['site']['settings'] ?? [],
            is_array($hubSite['settings'] ?? null) ? $hubSite['settings'] : [],
        );

        return [
            'site' => $mergedSite,
            'sections' => array_replace_recursive($fallback['sections'], $sections),
            'source' => 'hub',
        ];
    }

    /**
     * @return array{site: array<string, mixed>, sections: array<string, array<string, mixed>>, source: string}
     */
    protected function fallbackContent(string $locale): array
    {
        $sections = config("site.fallback.{$locale}")
            ?? config('site.fallback.en', []);

        return [
            'site' => [
                'slug' => config('site.key'),
                'name' => 'GAMIMED ARAB',
                'locale' => $locale,
                'locales' => config('site.locales', ['ar', 'en']),
                'settings' => config('site.settings', []),
            ],
            'sections' => $sections,
            'source' => 'fallback',
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function withLocale(array $payload): array
    {
        $payload['locale'] = $payload['locale'] ?? app()->getLocale();

        return $payload;
    }
}
