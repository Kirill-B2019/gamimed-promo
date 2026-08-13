<?php

namespace Tests\Feature;

use App\Models\AnalyticsEvent;
use App\Models\ContactMessage;
use App\Models\ContentSection;
use App\Models\Site;
use App\Services\AdminScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_scope_filters_by_site(): void
    {
        $siteA = Site::factory()->create();
        $siteB = Site::factory()->create();

        ContentSection::factory()->forSite($siteA)->create(['section_key' => 'hero-a']);
        ContentSection::factory()->forSite($siteB)->create(['section_key' => 'hero-b']);
        ContentSection::factory()->global()->create(['section_key' => 'global-hero']);

        $scope = new AdminScope(AdminScope::TYPE_SITE, null, $siteA->id);

        $keys = $scope->applyToContentSections(ContentSection::query())
            ->pluck('section_key')
            ->all();

        $this->assertContains('hero-a', $keys);
        $this->assertContains('global-hero', $keys);
        $this->assertNotContains('hero-b', $keys);
    }

    public function test_leads_scope_filters_by_site_ids(): void
    {
        $siteA = Site::factory()->create();
        $siteB = Site::factory()->create();

        ContactMessage::factory()->create(['site_id' => $siteA->id]);
        ContactMessage::factory()->create(['site_id' => $siteB->id]);

        $scope = new AdminScope(AdminScope::TYPE_SITE, null, $siteA->id);

        $this->assertSame(1, $scope->applyToContactMessages(ContactMessage::query())->count());
    }

    public function test_events_scope_filters_by_site_ids(): void
    {
        $siteA = Site::factory()->create();
        $siteB = Site::factory()->create();

        AnalyticsEvent::factory()->create(['site_id' => $siteA->id]);
        AnalyticsEvent::factory()->create(['site_id' => $siteB->id]);

        $scope = new AdminScope(AdminScope::TYPE_SITE, null, $siteA->id);

        $this->assertSame(1, $scope->applyToAnalyticsEvents(AnalyticsEvent::query())->count());
    }
}
