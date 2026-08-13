<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SeoLegalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Queue::fake();
        Http::fake([
            '*' => Http::response(null, 503),
        ]);
    }

    public function test_home_includes_open_graph_and_canonical_tags(): void
    {
        $response = $this->get('/ar');

        $response->assertOk();
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta property="og:description"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('hreflang="ar"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="x-default"', false);
        $response->assertSee(config('site.fallback.ar.hero.headline'));
    }

    public function test_robots_and_sitemap_are_public(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:')
            ->assertSee('/sitemap.xml', false);

        $sitemap = $this->get('/sitemap.xml');

        $sitemap->assertOk();
        $sitemap->assertSee('<urlset', false);
        $sitemap->assertSee('/ar', false);
        $sitemap->assertSee('/en/terms', false);
        $sitemap->assertSee('/ar/privacy', false);
        $sitemap->assertSee('hreflang="ar"', false);
        $sitemap->assertSee('hreflang="en"', false);
    }

    public function test_terms_and_privacy_stubs_render(): void
    {
        $this->get('/ar/terms')
            ->assertOk()
            ->assertSee(__('legal.terms.title'))
            ->assertSee(__('legal.stub_notice'))
            ->assertSee(__('legal.terms.body.0'));

        $this->get('/en/privacy')
            ->assertOk()
            ->assertSee('Privacy notice')
            ->assertSee(__('legal.privacy.body.0', [], 'en'));
    }

    public function test_footer_links_to_legal_pages(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee(route('legal.terms', ['locale' => 'en'], false), false)
            ->assertSee(route('legal.privacy', ['locale' => 'en'], false), false);
    }

    public function test_home_and_legal_pages_include_risk_disclaimer(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('data-disclaimer="risk"', false)
            ->assertSee(__('legal.risk_disclaimer', [], 'en'))
            ->assertSee('DFSA')
            ->assertSee('ADGM')
            ->assertSee('CMA');

        $this->get('/ar/terms')
            ->assertOk()
            ->assertSee('data-disclaimer="risk"', false)
            ->assertSee('DFSA')
            ->assertSee('ADGM')
            ->assertSee('CMA');

        $this->get('/en/privacy')
            ->assertOk()
            ->assertSee('data-disclaimer="risk"', false)
            ->assertSee('qualified investors');
    }

    public function test_third_party_analytics_are_omitted_until_configured(): void
    {
        $this->get('/ar')
            ->assertOk()
            ->assertDontSee('googletagmanager.com', false)
            ->assertDontSee('mc.yandex.ru', false)
            ->assertDontSee('hm.baidu.com', false);
    }

    public function test_configured_analytics_hooks_render(): void
    {
        config([
            'site.analytics.ga4' => 'G-TEST1234',
            'site.analytics.yandex' => '12345678',
            'site.analytics.baidu' => 'abcdef',
        ]);

        $this->get('/en')
            ->assertOk()
            ->assertSee('G-TEST1234', false)
            ->assertSee('googletagmanager.com/gtag/js?id=G-TEST1234', false)
            ->assertSee('mc.yandex.ru/metrika/tag.js', false)
            ->assertSee('hm.baidu.com/hm.js?', false)
            ->assertSee('name="analytics-ga4"', false)
            ->assertSee('name="analytics-yandex"', false)
            ->assertSee('name="analytics-baidu"', false);
    }
}
