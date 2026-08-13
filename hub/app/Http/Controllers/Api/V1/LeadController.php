<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var Site $site */
        $site = $request->user();

        $data = $request->validate([
            'locale' => ['nullable', 'string', 'max:16'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'messenger' => ['nullable', 'string', 'max:64'],
            'message' => ['nullable', 'string', 'max:5000'],
            'meta' => ['nullable', 'array'],
        ]);

        if (
            blank($data['email'] ?? null)
            && blank($data['phone'] ?? null)
            && blank($data['messenger'] ?? null)
            && blank($data['message'] ?? null)
        ) {
            return response()->json([
                'message' => 'At least one of email, phone, messenger, or message is required.',
            ], 422);
        }

        $lead = ContactMessage::query()->create([
            'site_id' => $site->id,
            'locale' => $data['locale'] ?? $site->default_locale,
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'messenger' => $data['messenger'] ?? null,
            'message' => $data['message'] ?? null,
            'meta' => $data['meta'] ?? null,
            'ip_hash' => $this->hashIp($request->ip()),
            'status' => LeadStatus::New,
        ]);

        return response()->json([
            'id' => $lead->id,
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
