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

    public function test_layout_loads_arabic_and_latin_fonts_from_bunny(): void
    {
        $this->fakeHubDown();

        $this->get('/ar')
            ->assertOk()
            ->assertSee('fonts.bunny.net', false)
            ->assertSee('cairo:400,500,600,700', false)
            ->assertSee('tajawal:400,500,600,700', false)
            ->assertSee('almarai:400,700', false)
            ->assertSee('montserrat:400,500,600,700', false)
            ->assertSee('poppins:400,500,600,700', false);
    }

    public function test_css_stack_includes_arabic_latin_fonts_and_cultural_patterns(): void
    {
        $css = File::get(resource_path('css/app.css'));

        $this->assertStringContainsString('"Tajawal"', $css);
        $this->assertStringContainsString('"Cairo"', $css);
        $this->assertStringContainsString('"Almarai"', $css);
        $this->assertStringContainsString('"Montserrat"', $css);
        $this->assertStringContainsString('"Poppins"', $css);
        $this->assertStringContainsString('.bg-pattern-geom', $css);
        $this->assertStringContainsString('.bg-pattern-calligraphy', $css);
        $this->assertStringContainsString('.bg-pattern-mashrabiya', $css);
        $this->assertStringContainsString('.hero-scrim', $css);
        $this->assertStringContainsString('.hero-type', $css);
        $this->assertStringContainsString('/images/pattern-geometry.svg', $css);
        $this->assertStringContainsString('/images/pattern-calligraphy.svg', $css);
        $this->assertStringContainsString('/images/pattern-blockchain.svg', $css);
        $this->assertStringContainsString('/images/pattern-mashrabiya.svg', $css);
    }

    public function test_home_renders_geometry_calligraphy_blockchain_and_keeps_hero_onerror_hide(): void
    {
        $this->fakeHubDown();

        $this->get('/ar')
            ->assertOk()
            ->assertSee('bg-pattern-geom', false)
            ->assertSee('bg-pattern-mashrabiya', false)
            ->assertSee('bg-pattern-blockchain', false)
            ->assertSee('ornament-calligraphy', false)
            ->assertSee('hero-scrim', false)
            ->assertSee('hero-type', false)
            ->assertSee("onerror=\"this.style.display='none'\"", false)
            ->assertSee('/images/hero-arab.jpg', false);
    }

    public function test_whitepaper_cta_is_hidden_when_pdf_is_missing(): void
    {
        $this->fakeHubDown();

        $relative = ltrim((string) config('site.fallback.ar.hero.whitepaper_url'), '/');
        File::delete(public_path($relative));
        clearstatcache(true, public_path($relative));

        $this->assertFalse(PublicDownload::exists(config('site.fallback.ar.hero.whitepaper_url')));

        $this->get('/ar')
            ->assertOk()
            ->assertDontSee('data-track="whitepaper_download"', false)
            ->assertDontSee(__('ui.whitepaper', [], 'ar'));
    }

    public function test_whitepaper_cta_shows_when_pdf_exists(): void
    {
        $this->fakeHubDown();

        $relative = ltrim((string) config('site.fallback.ar.hero.whitepaper_url'), '/');
        $path = public_path($relative);
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "%PDF-1.4\n");
        clearstatcache(true, $path);

        try {
            $this->assertTrue(PublicDownload::exists('/'.$relative));

            $this->get('/ar')
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
        $this->assertFalse(PublicDownload::exists('/images/pattern-geometry.svg'));
        $this->assertFalse(PublicDownload::exists('/downloads/../.env'));
        $this->assertTrue(PublicDownload::exists('https://cdn.example.com/whitepaper.pdf'));
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
