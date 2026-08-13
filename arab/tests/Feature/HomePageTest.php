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

        $response = $this->get('/ar');

        $response->assertOk();
        $response->assertSee('id="about"', false);
        $response->assertSee('id="tokenomics"', false);
        $response->assertSee('id="roadmap"', false);
        $response->assertSee('id="team"', false);
        $response->assertSee('id="partners"', false);
        $response->assertSee('id="security"', false);
        $response->assertSee('id="sharia"', false);
        $response->assertSee('id="testimonials"', false);
        $response->assertSee('id="faq"', false);
        $response->assertSee('id="contact"', false);
        $response->assertSee(config('site.fallback.ar.hero.headline'));
        $response->assertSee(config('site.fallback.ar.sharia.title'));
        $response->assertSeeLivewire('investment-calculator');
        $response->assertSeeLivewire('contact-form');
        $response->assertSeeLivewire('locale-switcher');
        $response->assertSee('data-track="cta_click"', false);
        $response->assertDontSee('data-track="whitepaper_download"', false);
        $response->assertSee('fonts.bunny.net', false);
        $response->assertSee('family=cairo:400,500,600,700|tajawal:400,500,600,700|almarai:400,700|montserrat:400,500,600,700|poppins:400,500,600,700', false);
        $response->assertSee('bg-pattern-geom', false);
        $response->assertSee('bg-pattern-mashrabiya', false);
        $response->assertSee('bg-pattern-blockchain', false);
        $response->assertSee('ornament-calligraphy', false);
        $response->assertSee('onerror="this.style.display=\'none\'"', false);
        $response->assertSee('https://x.com/gamimed', false);
        $response->assertSee('https://t.me/gamimed', false);
        $response->assertSee('https://www.snapchat.com/add/gamimed', false);
        $response->assertSee('https://www.linkedin.com/company/gamimed', false);
        $response->assertSee('https://www.linkedin.com/in/example-sara', false);
        $response->assertSee(__('ui.social_nav'), false);
        $response->assertSee(__('ui.linkedin'));
        $response->assertDontSee('weibo.com', false);
        $this->assertTokenomicsAndRoadmapFallback($response, 'ar');
    }

    public function test_stylesheet_declares_complementary_fonts_and_geometry_pattern(): void
    {
        $css = (string) file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString('Tajawal', $css);
        $this->assertStringContainsString('Cairo', $css);
        $this->assertStringContainsString('Almarai', $css);
        $this->assertStringContainsString('Montserrat', $css);
        $this->assertStringContainsString('Poppins', $css);
        $this->assertStringContainsString('bg-pattern-geom', $css);
        $this->assertStringContainsString('/images/pattern-geometry.svg', $css);
        $this->assertStringContainsString('/images/pattern-calligraphy.svg', $css);
        $this->assertStringContainsString('/images/pattern-blockchain.svg', $css);
        $this->assertStringContainsString('/images/pattern-mashrabiya.svg', $css);
        $this->assertFileExists(public_path('images/pattern-geometry.svg'));
        $this->assertFileExists(public_path('images/pattern-calligraphy.svg'));
        $this->assertFileExists(public_path('images/pattern-blockchain.svg'));
        $this->assertFileExists(public_path('images/pattern-mashrabiya.svg'));
        $this->assertFileExists(public_path('images/README.md'));
    }

    public function test_home_merges_hub_sections_over_fallback(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'arab',
                    'name' => 'ARAB Promo',
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

    public function test_hub_settings_keep_social_fallback_when_omitted(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'arab',
                    'name' => 'ARAB Promo',
                    'settings' => [
                        'currencies' => ['USD', 'AED', 'SAR', 'QAR'],
                        'presale_price_usd' => 0.05,
                    ],
                ],
                'sections' => [],
            ], 200),
            '*/api/v1/events*' => Http::response(['ok' => true], 200),
        ]);

        $this->get('/ar')
            ->assertOk()
            ->assertSee('https://x.com/gamimed', false)
            ->assertSee('https://t.me/gamimed', false)
            ->assertSee('https://www.snapchat.com/add/gamimed', false)
            ->assertSee('https://www.linkedin.com/company/gamimed', false)
            ->assertSee('https://www.linkedin.com/in/example-sara', false);
    }

    public function test_tokenomics_shows_hub_presale_price_and_hub_vesting_override(): void
    {
        Cache::flush();
        Queue::fake();

        Http::fake([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'arab',
                    'name' => 'ARAB Promo',
                    'settings' => array_replace_recursive(config('site.settings'), [
                        'presale_price_usd' => 0.08,
                    ]),
                ],
                'sections' => [
                    [
                        'section_key' => 'tokenomics',
                        'payload' => [
                            'vesting' => ['Hub vesting cliff 18 months'],
                            'utility' => ['Hub token utility override'],
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
            ->assertSee('Hub vesting cliff 18 months')
            ->assertSee('Hub token utility override')
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

        $arabic = $this->get('/ar')->assertOk();
        $arabic->assertSee('Pre-ICO', false);
        $arabic->assertSee('بلوكشين', false);
        $arabic->assertSee('KYC/AML', false);
        $arabic->assertSee('DFSA', false);
        $arabic->assertSee('ADGM', false);
        $arabic->assertSee('CMA', false);
        $arabic->assertSee('data-disclaimer="risk"', false);
        $arabic->assertSee(config('site.fallback.ar.testimonials.items.0.author'));
        $arabic->assertSee(config('site.fallback.ar.testimonials.items.0.role'));
        $arabic->assertSee(config('site.fallback.ar.testimonials.items.1.author'));
        $arabic->assertSee(config('site.fallback.ar.security.points.0'));
        $arabic->assertSee(config('site.fallback.ar.security.points.2'));
        $arabic->assertDontSee('مستثمر — دبي', false);
        $arabic->assertDontSee('أول مشروع', false);

        $english = $this->get('/en')->assertOk();
        $english->assertSee('Sharia-compliant Pre-ICO');
        $english->assertSee('regional blockchain', false);
        $english->assertSee('qualified investors', false);
        $english->assertSee('KYC/AML', false);
        $english->assertSee('Independent smart-contract audit before launch');
        $english->assertSee('Yousef Al-Najjar');
        $english->assertSee('MENA fintech commentator — Dubai');
        $english->assertSee('Dr. Amina Al-Harthy');
        $english->assertSee('Islamic finance advisor — Riyadh');
        $english->assertSee('DFSA');
        $english->assertSee('ADGM');
        $english->assertSee('CMA');
        $english->assertDontSee('Investor — Dubai');
        $english->assertDontSee('first in the region', false);
        $english->assertDontSee('first Islamic', false);
    }

    public function test_whitepaper_cta_renders_when_local_pdf_exists(): void
    {
        Cache::flush();
        Queue::fake();
        Http::fake([
            '*' => Http::response(null, 503),
        ]);

        $url = (string) config('site.fallback.ar.hero.whitepaper_url');
        $path = public_path(ltrim($url, '/'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "%PDF-1.4\n%test\n");
        clearstatcache(true, $path);

        try {
            $this->get('/ar')
                ->assertOk()
                ->assertSee('data-track="whitepaper_download"', false)
                ->assertSee($url, false);
        } finally {
            File::delete($path);
            clearstatcache(true, $path);
        }
    }
}
