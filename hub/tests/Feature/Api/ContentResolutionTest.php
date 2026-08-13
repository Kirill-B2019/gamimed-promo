<?php

namespace Tests\Feature\Api;

use App\Enums\ContentStatus;
use App\Enums\SiteStatus;
use App\Models\ContentSection;
use App\Models\HubSetting;
use App\Models\Site;
use App\Models\SiteGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentResolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_resolves_site_override_over_group_and_global(): void
    {
        $group = SiteGroup::factory()->create();
        $site = Site::factory()->create([
            'status' => SiteStatus::Active,
            'default_locale' => 'en',
        ]);
        $group->sites()->attach($site);

        ContentSection::factory()->global()->create([
            'section_key' => 'hero',
            'locale' => 'en',
            'payload' => ['title' => 'Global hero'],
            'status' => ContentStatus::Published,
        ]);

        ContentSection::factory()->forGroup($group)->create([
            'section_key' => 'hero',
            'locale' => 'en',
            'payload' => ['title' => 'Group hero'],
            'status' => ContentStatus::Published,
        ]);

        ContentSection::factory()->forSite($site)->create([
            'section_key' => 'hero',
            'locale' => 'en',
            'payload' => ['title' => 'Site hero'],
            'status' => ContentStatus::Published,
        ]);

        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/content?locale=en')
            ->assertOk();

        $sections = collect($response->json('sections'));
        $hero = $sections->firstWhere('section_key', 'hero');

        $this->assertSame('Site hero', $hero['payload']['title'] ?? null);
        $this->assertSame('site', $hero['source'] ?? null);
    }

    public function test_content_falls_back_to_group_then_global(): void
    {
        $group = SiteGroup::factory()->create();
        $site = Site::factory()->create([
            'status' => SiteStatus::Active,
            'default_locale' => 'en',
        ]);
        $group->sites()->attach($site);

        ContentSection::factory()->global()->create([
            'section_key' => 'about',
            'locale' => 'en',
            'payload' => ['title' => 'Global about'],
            'status' => ContentStatus::Published,
        ]);

        ContentSection::factory()->forGroup($group)->create([
            'section_key' => 'about',
            'locale' => 'en',
            'payload' => ['title' => 'Group about'],
            'status' => ContentStatus::Published,
        ]);

        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/content?locale=en')
            ->assertOk();

        $about = collect($response->json('sections'))->firstWhere('section_key', 'about');

        $this->assertSame('Group about', $about['payload']['title'] ?? null);
        $this->assertSame('group', $about['source'] ?? null);
    }

    public function test_content_uses_global_when_no_override(): void
    {
        $site = Site::factory()->create([
            'status' => SiteStatus::Active,
            'default_locale' => 'en',
        ]);

        ContentSection::factory()->global()->create([
            'section_key' => 'faq',
            'locale' => 'en',
            'payload' => ['title' => 'Global FAQ'],
            'status' => ContentStatus::Published,
        ]);

        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/content?locale=en')
            ->assertOk();

        $faq = collect($response->json('sections'))->firstWhere('section_key', 'faq');

        $this->assertSame('Global FAQ', $faq['payload']['title'] ?? null);
        $this->assertSame('global', $faq['source'] ?? null);
    }

    public function test_content_merges_global_settings(): void
    {
        HubSetting::set('presale_price_usd', ['value' => 0.08]);
        HubSetting::set('fx_rates', ['USD' => 1, 'AED' => 3.67]);

        $site = Site::factory()->create([
            'status' => SiteStatus::Active,
            'settings' => ['currencies' => ['USD', 'AED']],
        ]);

        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/content')
            ->assertOk()
            ->assertJsonPath('site.settings.presale_price_usd', 0.08)
            ->assertJsonPath('site.settings.currencies', ['USD', 'AED'])
            ->assertJsonPath('site.settings.fx_rates.USD', 1);
    }

    public function test_draft_sections_are_excluded(): void
    {
        $site = Site::factory()->create(['status' => SiteStatus::Active]);

        ContentSection::factory()->global()->create([
            'section_key' => 'hero',
            'locale' => 'en',
            'status' => ContentStatus::Draft,
        ]);

        $token = $site->createToken('site-api', ['site:api'])->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/content?locale=en')
            ->assertOk()
            ->assertJsonCount(0, 'sections');
    }
}
