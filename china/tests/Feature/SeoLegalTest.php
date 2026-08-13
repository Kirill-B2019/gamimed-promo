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
        $response = $this->get('/zh_CN');

        $response->assertOk();
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta property="og:description"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('hreflang="zh-CN"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="x-default"', false);
        $response->assertSee(config('site.fallback.zh_CN.hero.headline'));
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
        $sitemap->assertSee('/zh_CN', false);
        $sitemap->assertSee('/en/terms', false);
        $sitemap->assertSee('/zh_CN/privacy', false);
        $sitemap->assertSee('hreflang="zh-CN"', false);
        $sitemap->assertSee('hreflang="en"', false);
    }

    public function test_terms_and_privacy_stubs_render_without_restricted_wording(): void
    {
        foreach (['/zh_CN/terms', '/zh_CN/privacy', '/en/terms', '/en/privacy'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            $this->assertDoesNotMatchRegularExpression('/\bICO\b/i', $html);
            $this->assertDoesNotMatchRegularExpression('/cryptocurrenc(y|ies)/i', $html);
            $this->assertStringNotContainsString('加密货币', $html);
            $this->assertStringNotContainsString('WhatsApp', $html);
        }

        $this->get('/zh_CN/terms')
            ->assertSee(__('legal.terms.title'))
            ->assertSee(__('legal.stub_notice'));
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
            ->assertSee('not an offer')
            ->assertSee('tokenization')
            ->assertDontSee('DFSA');

        $this->get('/zh_CN/terms')
            ->assertOk()
            ->assertSee('data-disclaimer="risk"', false)
            ->assertSee('不构成要约')
            ->assertSee('通证化');

        $this->get('/en/privacy')
            ->assertOk()
            ->assertSee('data-disclaimer="risk"', false)
            ->assertSee('not speculation');
    }

    public function test_third_party_analytics_are_omitted_until_configured(): void
    {
        $this->get('/zh_CN')
            ->assertOk()
            ->assertDontSee('googletagmanager.com', false)
            ->assertDontSee('mc.yandex.ru', false)
            ->assertDontSee('hm.baidu.com', false);
    }

    public function test_configured_analytics_hooks_render(): void
    {
        config([
            'site.analytics.ga4' => 'G-CNTEST01',
            'site.analytics.baidu' => 'baidu-token',
        ]);

        $this->get('/en')
            ->assertOk()
            ->assertSee('G-CNTEST01', false)
            ->assertSee('googletagmanager.com/gtag/js?id=G-CNTEST01', false)
            ->assertSee('hm.baidu.com/hm.js?', false)
            ->assertSee('name="analytics-ga4"', false)
            ->assertSee('name="analytics-baidu"', false)
            ->assertDontSee('mc.yandex.ru', false);
    }
}
