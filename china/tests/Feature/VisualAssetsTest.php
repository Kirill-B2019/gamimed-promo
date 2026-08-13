<?php

namespace Tests\Feature;

use App\Support\PublicDownload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class VisualAssetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_layout_loads_chinese_and_latin_fonts_from_bunny(): void
    {
        $this->fakeHubDown();

        $this->get('/zh_CN')
            ->assertOk()
            ->assertSee('fonts.bunny.net', false)
            ->assertSee('inter:400,500,600,700', false)
            ->assertSee('roboto:400,500,700', false)
            ->assertSee('noto-sans-sc:400,500,600,700', false);
    }

    public function test_css_stack_includes_noto_pingfang_roboto_inter_and_cultural_patterns(): void
    {
        $css = File::get(resource_path('css/app.css'));

        $this->assertStringContainsString('"Inter"', $css);
        $this->assertStringContainsString('"Roboto"', $css);
        $this->assertStringContainsString('"Noto Sans SC"', $css);
        $this->assertStringContainsString('"PingFang SC"', $css);
        $this->assertStringContainsString('.bg-pattern-cloud', $css);
        $this->assertStringContainsString('.bg-pattern-phoenix', $css);
        $this->assertStringContainsString('.bg-pattern-dragon', $css);
        $this->assertStringContainsString('.bg-pattern-tech', $css);
        $this->assertStringContainsString('.bg-pattern-lattice', $css);
        $this->assertStringContainsString('.hero-scrim', $css);
        $this->assertStringContainsString('.hero-type', $css);
        $this->assertStringContainsString('/images/pattern-cloud.svg', $css);
        $this->assertStringContainsString('/images/pattern-phoenix.svg', $css);
        $this->assertStringContainsString('/images/pattern-dragon.svg', $css);
        $this->assertStringContainsString('/images/pattern-tech.svg', $css);
        $this->assertStringContainsString('/images/pattern-lattice.svg', $css);
    }

    public function test_home_renders_cloud_tech_phoenix_blue_technology_accent_and_keeps_hero_onerror_hide(): void
    {
        $this->fakeHubDown();

        $this->get('/zh_CN')
            ->assertOk()
            ->assertSee('bg-pattern-cloud', false)
            ->assertSee('bg-pattern-tech', false)
            ->assertSee('ornament-phoenix', false)
            ->assertSee('ornament-dragon', false)
            ->assertSee('hero-scrim', false)
            ->assertSee('hero-type', false)
            ->assertSee('window-round', false)
            ->assertSee('cta-luck', false)
            ->assertSee('id="technology"', false)
            ->assertSee('to-blue/10', false)
            ->assertSee("onerror=\"this.style.display='none'\"", false)
            ->assertSee('/images/hero-china.jpg', false)
            ->assertDontSee('>04<', false);
    }

    public function test_whitepaper_cta_is_hidden_when_pdf_is_missing(): void
    {
        $this->fakeHubDown();

        $relative = ltrim((string) config('site.fallback.zh_CN.hero.whitepaper_url'), '/');
        File::delete(public_path($relative));
        clearstatcache(true, public_path($relative));

        $this->assertFalse(PublicDownload::exists(config('site.fallback.zh_CN.hero.whitepaper_url')));

        $this->get('/zh_CN')
            ->assertOk()
            ->assertDontSee('data-track="whitepaper_download"', false)
            ->assertDontSee(__('ui.whitepaper', [], 'zh_CN'));
    }

    public function test_whitepaper_cta_shows_when_pdf_exists(): void
    {
        $this->fakeHubDown();

        $relative = ltrim((string) config('site.fallback.zh_CN.hero.whitepaper_url'), '/');
        $path = public_path($relative);
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "%PDF-1.4\n");
        clearstatcache(true, $path);

        try {
            $this->assertTrue(PublicDownload::exists('/'.$relative));

            $this->get('/zh_CN')
                ->assertOk()
                ->assertSee('data-track="whitepaper_download"', false)
                ->assertSee('/'.$relative, false);
        } finally {
            File::delete($path);
            clearstatcache(true, $path);
        }
    }

    public function test_public_download_rejects_non_pdf_and_path_traversal(): void
    {
        $this->assertFalse(PublicDownload::exists(null));
        $this->assertFalse(PublicDownload::exists(''));
        $this->assertFalse(PublicDownload::exists('/images/pattern-cloud.svg'));
        $this->assertFalse(PublicDownload::exists('/downloads/../.env'));
        $this->assertTrue(PublicDownload::exists('https://cdn.example.com/overview.pdf'));
    }

    private function fakeHubDown(): void
    {
        Cache::flush();
        Queue::fake();
        Http::fake([
            '*' => Http::response(null, 503),
        ]);
    }
}
