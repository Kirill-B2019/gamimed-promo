<?php

namespace App\Http\Controllers;

use App\Services\HubClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrackEventController extends Controller
{
    public function __invoke(Request $request, HubClient $hub): JsonResponse
    {
        $validated = $request->validate([
            'event_type' => ['required', 'string', Rule::in(HubClient::EVENT_TYPES)],
            'path' => ['nullable', 'string', 'max:2048'],
            'locale' => ['nullable', 'string', 'max:16'],
            'meta' => ['nullable', 'array'],
        ]);

        $hub->trackEvent($validated['event_type'], [
            'path' => $validated['path'] ?? $request->getPathInfo(),
            'locale' => $validated['locale'] ?? app()->getLocale(),
            'meta' => $validated['meta'] ?? [],
        ]);

        return response()->json(['ok' => true]);
    }
}
