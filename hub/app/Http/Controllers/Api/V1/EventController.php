<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public const EVENT_TYPES = [
        'page_view',
        'cta_click',
        'calculator_use',
        'contact_submit',
        'whitepaper_download',
    ];

    public function __invoke(Request $request): JsonResponse
    {
        /** @var Site $site */
        $site = $request->user();

        $data = $request->validate([
            'event_type' => ['required', 'string', Rule::in(self::EVENT_TYPES)],
            'locale' => ['nullable', 'string', 'max:16'],
            'path' => ['nullable', 'string', 'max:2048'],
            'meta' => ['nullable', 'array'],
        ]);

        $event = AnalyticsEvent::query()->create([
            'site_id' => $site->id,
            'event_type' => $data['event_type'],
            'locale' => $data['locale'] ?? $site->default_locale,
            'path' => $data['path'] ?? null,
            'meta' => $data['meta'] ?? null,
            'ip_hash' => $this->hashIp($request->ip()),
            'created_at' => now(),
        ]);

        return response()->json([
            'id' => $event->id,
            'status' => 'accepted',
        ], 201);
    }

    private function hashIp(?string $ip): ?string
    {
        if (! $ip) {
            return null;
        }

        $pepper = (string) config('app.key');

        return hash_hmac('sha256', $ip, $pepper);
    }
}
