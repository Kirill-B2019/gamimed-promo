<?php

namespace Tests\Feature\Api;

use App\Enums\SiteStatus;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_requires_authentication(): void
    {
        $this->getJson('/api/v1/content')
            ->assertUnauthorized();
    }

    public function test_site_can_fetch_content_with_sanctum_token(): void
    {
        $site = Site::factory()->create([
            'slug' => 'arab',
            'status' => SiteStatus::Active,
            'default_locale' => 'en',
            'locales' => ['ar', 'en'],
        ]);

        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/content?locale=en')
            ->assertOk()
            ->assertJsonPath('site.slug', 'arab')
            ->assertJsonStructure(['site', 'sections']);
    }

    public function test_inactive_site_is_rejected(): void
    {
        $site = Site::factory()->create([
            'status' => SiteStatus::Inactive,
        ]);

        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/content')
            ->assertForbidden();
    }

    public function test_lead_intake_accepts_valid_payload(): void
    {
        $site = Site::factory()->create(['status' => SiteStatus::Active]);
        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/leads', [
                'email' => 'investor@example.com',
                'name' => 'Investor',
                'message' => 'Interested',
            ])
            ->assertCreated()
            ->assertJsonPath('status', 'accepted');

        $this->assertDatabaseHas('contact_messages', [
            'site_id' => $site->id,
            'email' => 'investor@example.com',
        ]);
    }

    public function test_event_intake_accepts_known_types(): void
    {
        $site = Site::factory()->create(['status' => SiteStatus::Active]);
        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/events', [
                'event_type' => 'page_view',
                'path' => '/',
                'locale' => 'en',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('analytics_events', [
            'site_id' => $site->id,
            'event_type' => 'page_view',
        ]);
    }
}
