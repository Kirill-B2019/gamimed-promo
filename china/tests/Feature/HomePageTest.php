<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_all_sections_from_fallback_when_hub_is_down(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*' => Http::response(null, 503),
        ]);

        $response = $this->get('/zh_CN');

        $response->assertOk();
        $response->assertSee('dir="ltr"', false);
        $response->assertSee('id="about"', false);
        $response->assertSee('id="tokenomics"', false);
        $response->assertSee('id="roadmap"', false);
        $response->assertSee('id="team"', false);
        $response->assertSee('id="partners"', false);
        $response->assertSee('id="security"', false);
        $response->assertSee('id="technology"', false);
        $response->assertDontSee('id="sharia"', false);
        $response->assertSee('id="testimonials"', false);
        $response->assertSee('id="faq"', false);
        $response->assertSee('id="contact"', false);
        $response->assertSee(config('site.fallback.zh_CN.hero.headline'));
        $response->assertSee(config('site.fallback.zh_CN.technology.title'));
        $response->assertSee(config('site.fallback.zh_CN.technology.layers.0.title'));
        $response->assertSee(config('site.fallback.zh_CN.technology.layers.4.title'));
        $response->assertSee(__('ui.wechat_cta'));
        $response->assertSee(__('ui.wechat_scan'));
        $response->assertSee(__('ui.wechat_id_label'));
        $response->assertSee('GAMIMED');
        $response->assertSee('/images/wechat-qr.svg', false);
        $response->assertSee('alt="'.__('ui.wechat_qr').'"', false);
        $response->assertSee('CNY');
        $response->assertDontSee('AED');
        $response->assertDontSee('SAR');
        $response->assertDontSee('QAR');
        $response->assertSeeLivewire('investment-calculator');
        $response->assertSeeLivewire('contact-form');
        $response->assertSeeLivewire('locale-switcher');
        $response->assertSee('data-track="cta_click"', false);
        $response->assertDontSee('data-track="whitepaper_download"', false);
        $response->assertSee('fonts.bunny.net', false);
        $response->assertSee('family=inter:400,500,600,700|roboto:400,500,700|noto-sans-sc:400,500,600,700', false);
        $response->assertSee('bg-pattern-cloud', false);
        $response->assertSee('bg-pattern-tech', false);
        $response->assertSee('window-round', false);
        $response->assertSee('cta-luck', false);
        $response->assertSee('ornament-phoenix', false);
        $response->assertSee('ornament-dragon', false);
        $response->assertSee('w-1.5 bg-blue', false);
        $response->assertSee('onerror="this.style.display=\'none\'"', false);
        $response->assertSee('data-track-meta=\'{"target":"wechat"}\'', false);
        $response->assertSee('https://weibo.com/gamimed', false);
        $response->assertSee('https://t.me/gamimed', false);
        $response->assertSee('https://www.linkedin.com/in/example-wei-chen', false);
        $response->assertSee(__('ui.social_nav'), false);
        $response->assertSee(__('ui.linkedin'));
        $response->assertDontSee('snapchat.com', false);
        $response->assertDontSee('x.com/gamimed', false);
        $this->assertTokenomicsAndRoadmapFallback($response, 'zh_CN');
    }

    public function test_stylesheet_declares_inter_pingfang_and_cloud_pattern(): void
    {
        $css = (string) file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString('Inter', $css);
        $this->assertStringContainsString('Roboto', $css);
        $this->assertStringContainsString('PingFang SC', $css);
        $this->assertStringContainsString('Noto Sans SC', $css);
        $this->assertStringContainsString('bg-pattern-cloud', $css);
        $this->assertStringContainsString('bg-pattern-phoenix', $css);
        $this->assertStringContainsString('bg-pattern-dragon', $css);
        $this->assertStringContainsString('bg-pattern-tech', $css);
        $this->assertStringContainsString('bg-pattern-lattice', $css);
        $this->assertStringContainsString('/images/pattern-cloud.svg', $css);
        $this->assertStringContainsString('/images/pattern-phoenix.svg', $css);
        $this->assertStringContainsString('/images/pattern-dragon.svg', $css);
        $this->assertStringContainsString('/images/pattern-tech.svg', $css);
        $this->assertStringContainsString('/images/pattern-lattice.svg', $css);
        $this->assertFileExists(public_path('images/pattern-cloud.svg'));
        $this->assertFileExists(public_path('images/pattern-phoenix.svg'));
        $this->assertFileExists(public_path('images/pattern-dragon.svg'));
        $this->assertFileExists(public_path('images/pattern-tech.svg'));
        $this->assertFileExists(public_path('images/pattern-lattice.svg'));
        $this->assertFileExists(public_path('images/README.md'));
    }

    public function test_home_merges_hub_sections_over_fallback(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'china',
                    'name' => 'CHINA Promo',
                    'settings' => config('site.settings'),
                ],
                'sections' => [
                    [
                        'section_key' => 'hero',
                        'payload' => [
                            'headline' => 'Hub headline override',
                        ],
                    ],
                ],
            ], 200),
            '*/api/v1/events*' => Http::response(['ok' => true], 200),
        ]);

        $response = $this->get('/en');

        $response->assertOk();
        $response->assertSee('Hub headline override');
        $response->assertSee(config('site.fallback.en.about.title'));
    }

    public function test_hub_settings_keep_wechat_fallback_when_omitted(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'china',
                    'name' => 'CHINA Promo',
                    'settings' => [
                        'currencies' => ['USD', 'CNY'],
                        'presale_price_usd' => 0.05,
                    ],
                ],
                'sections' => [],
            ], 200),
            '*/api/v1/events*' => Http::response(['ok' => true], 200),
        ]);

        $this->get('/zh_CN')
            ->assertOk()
            ->assertSee('/images/wechat-qr.svg', false)
            ->assertSee(__('ui.wechat_cta'))
            ->assertSee(__('ui.wechat_id_label'))
            ->assertSee('https://weibo.com/gamimed', false)
            ->assertSee('https://t.me/gamimed', false)
            ->assertSee('https://www.linkedin.com/in/example-wei-chen', false);
    }

    public function test_public_copy_avoids_restricted_compliance_terms(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*' => Http::response(null, 503),
        ]);

        foreach (['/zh_CN', '/en', '/zh_CN/terms', '/zh_CN/privacy', '/en/terms', '/en/privacy'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            $this->assertDoesNotMatchRegularExpression('/\bICO\b/i', $html);
            $this->assertDoesNotMatchRegularExpression('/cryptocurrenc(y|ies)/i', $html);
            $this->assertStringNotContainsString('加密货币', $html);
            $this->assertStringNotContainsString('WhatsApp', $html);
            $this->assertStringNotContainsString('id="sharia"', $html);
        }
    }

    public function test_tokenomics_shows_hub_presale_price_and_hub_vesting_override(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'china',
                    'name' => 'CHINA Promo',
                    'settings' => array_replace_recursive(config('site.settings'), [
                        'presale_price_usd' => 0.08,
                    ]),
                ],
                'sections' => [
                    [
                        'section_key' => 'tokenomics',
                        'payload' => [
                            'vesting' => ['Hub release window 18 months'],
                            'utility' => ['Hub digital unit utility override'],
                        ],
                    ],
                    [
                        'section_key' => 'roadmap',
                        'payload' => [
                            'items' => [
                                ['period' => 'Q4 2028', 'title' => 'Hub maturity', 'body' => 'Hub roadmap body'],
                            ],
                        ],
                    ],
                ],
            ], 200),
            '*/api/v1/events*' => Http::response(['ok' => true], 200),
        ]);

        $this->get('/en')
            ->assertOk()
            ->assertSee(__('ui.presale_price', [], 'en'))
            ->assertSee('0.0800')
            ->assertSee('Hub release window 18 months')
            ->assertSee('Hub digital unit utility override')
            ->assertSee('Hub maturity')
            ->assertSee('Q4 2028');
    }

    private function assertTokenomicsAndRoadmapFallback(\Illuminate\Testing\TestResponse $response, string $locale): void
    {
        $items = config("site.fallback.{$locale}.roadmap.items");
        $this->assertIsArray($items);
        $this->assertGreaterThanOrEqual(8, count($items));
        $this->assertSame('Q3 2026', $items[0]['period']);
        $this->assertSame('Q4 2028', $items[array_key_last($items)]['period']);

        $response->assertSee(__('ui.presale_price', [], $locale));
        $response->assertSee(__('ui.vesting', [], $locale));
        $response->assertSee(__('ui.utility', [], $locale));
        $response->assertSee('0.0500');
        $response->assertSee(config("site.fallback.{$locale}.tokenomics.vesting.0"));
        $response->assertSee(config("site.fallback.{$locale}.tokenomics.utility.0"));
        $response->assertSee('Q3 2026');
        $response->assertSee('Q4 2028');
        $response->assertSee($items[array_key_last($items)]['title']);
    }

    public function test_home_copy_uses_brief_tone_named_testimonials_disclaimer_and_security_points(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*' => Http::response(null, 503),
        ]);

        $chinese = $this->get('/zh_CN')->assertOk();
        $chinese->assertSee('区块链', false);
        $chinese->assertSee('数字资产', false);
        $chinese->assertSee('通证化', false);
        $chinese->assertSee('KYC/AML', false);
        $chinese->assertSee('不构成要约', false);
        $chinese->assertSee('data-disclaimer="risk"', false);
        $chinese->assertSee(config('site.fallback.zh_CN.testimonials.items.0.author'));
        $chinese->assertSee(config('site.fallback.zh_CN.testimonials.items.0.role'));
        $chinese->assertSee(config('site.fallback.zh_CN.testimonials.items.1.author'));
        $chinese->assertSee(config('site.fallback.zh_CN.security.points.0'));
        $chinese->assertSee(config('site.fallback.zh_CN.security.points.2'));
        $chinese->assertDontSee('参与者 — 上海', false);

        $english = $this->get('/en')->assertOk();
        $english->assertSee('tokenization');
        $english->assertSee('digital assets');
        $english->assertSee('not an offer');
        $english->assertSee('not speculation');
        $english->assertSee('KYC/AML');
        $english->assertSee('qualified investors');
        $english->assertSee('Independent platform and contract audit before launch');
        $english->assertSee('Li Wei');
        $english->assertSee('Blockchain architect — Shanghai');
        $english->assertSee('Zhang Min');
        $english->assertSee('Digital-asset researcher — Singapore');
        $english->assertDontSee('Participant — Shanghai');
        $english->assertDontSee('Investor — Dubai');
    }

    public function test_whitepaper_cta_renders_when_local_pdf_exists(): void
    {
        Cache::flush();
        Queue::fake();
        Http::fake([
            '*' => Http::response(null, 503),
        ]);

        $url = (string) config('site.fallback.zh_CN.hero.whitepaper_url');
        $path = public_path(ltrim($url, '/'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "%PDF-1.4\n%test\n");
        clearstatcache(true, $path);

        try {
            $this->get('/zh_CN')
                ->assertOk()
                ->assertSee('data-track="whitepaper_download"', false)
                ->assertSee($url, false);
        } finally {
            File::delete($path);
            clearstatcache(true, $path);
        }
    }
}
