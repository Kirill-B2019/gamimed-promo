<?php

namespace App\Support;

class PublicDownload
{
    /**
     * Show a download CTA only when the file exists under public/downloads,
     * or when the URL is hosted on an external origin.
     */
    public static function isAvailable(?string $url): bool
    {
        return self::exists($url);
    }

    public static function exists(?string $url): bool
    {
        if (! is_string($url) || trim($url) === '') {
            return false;
        }

        $parts = parse_url($url);
        if ($parts === false) {
            return false;
        }

        if (! empty($parts['host'])) {
            return true;
        }

        $relative = ltrim((string) ($parts['path'] ?? ''), '/');

        if (
            $relative === ''
            || str_contains($relative, '..')
            || ! str_starts_with($relative, 'downloads/')
            || ! str_ends_with(strtolower($relative), '.pdf')
        ) {
            return false;
        }

        return is_file(public_path($relative));
    }
}
