<?php

namespace Tests\Feature;

use App\Enums\ContentStatus;
use App\Enums\UserRole;
use App\Models\ContentSection;
use App\Models\HubSetting;
use App\Models\Site;
use App\Models\SiteGroup;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Pages\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_scoped_sites_users_and_full_payloads(): void
    {
        $this->seed(DatabaseSeeder::class);

        $arab = Site::query()->where('slug', 'arab')->first();
        $china = Site::query()->where('slug', 'china')->first();

        $this->assertNotNull($arab);
        $this->assertNotNull($china);
        $this->assertSame(['ar', 'en'], $arab->locales);
        $this->assertSame(['zh_CN', 'en'], $china->locales);
        $this->assertSame(['USD', 'AED', 'SAR', 'QAR'], $arab->settings['currencies'] ?? null);
        $this->assertSame(['USD', 'CNY'], $china->settings['currencies'] ?? null);

        $this->assertTrue(SiteGroup::query()->where('slug', 'all-preico')->exists());
        $this->assertTrue(SiteGroup::query()->where('slug', 'mena')->exists());
        $this->assertTrue(SiteGroup::query()->where('slug', 'apac')->exists());

        $admin = User::query()->where('email', config('hub.admin_email'))->first();

        $this->assertSame(UserRole::SuperAdmin, $admin?->role);
        $this->assertSame(UserRole::SiteManager, User::query()->where('email', config('hub.arab_manager_email'))->first()?->role);
        $this->assertSame(UserRole::Viewer, User::query()->where('email', config('hub.viewer_email'))->first()?->role);
        $this->assertTrue(Hash::check((string) config('hub.admin_password'), $admin->password));
        $this->assertTrue(Auth::attempt([
            'email' => (string) config('hub.admin_email'),
            'password' => (string) config('hub.admin_password'),
        ]));
        Auth::logout();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => (string) config('hub.admin_email'),
                'password' => (string) config('hub.admin_password'),
            ])
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertSame(0.05, HubSetting::get('presale_price_usd'));
        $this->assertTrue(HubSetting::get('feature_flags')['calculator_enabled'] ?? false);

        $arabSharia = ContentSection::query()
            ->where('site_id', $arab->id)
            ->where('section_key', 'sharia')
            ->where('locale', 'ar')
            ->first();

        $this->assertNotNull($arabSharia);
        $this->assertSame(ContentStatus::Published, $arabSharia->status);
        $this->assertSame('site:'.$arab->id, $arabSharia->scope_key);
        $this->assertNotEmpty($arabSharia->payload['principles'] ?? []);

        $arabTokenomics = ContentSection::query()
            ->where('site_id', $arab->id)
            ->where('section_key', 'tokenomics')
            ->where('locale', 'en')
            ->first();

        $this->assertCount(4, $arabTokenomics?->payload['allocations'] ?? []);

        $chinaTechnology = ContentSection::query()
            ->where('site_id', $china->id)
            ->where('section_key', 'technology')
            ->where('locale', 'zh_CN')
            ->first();

        $this->assertNotNull($chinaTechnology);
        $this->assertNotEmpty($chinaTechnology->payload['layers'] ?? []);
        $this->assertNotEmpty($chinaTechnology->payload['capabilities'] ?? []);

        $chinaCopy = ContentSection::query()
            ->where('site_id', $china->id)
            ->get()
            ->map(fn (ContentSection $section) => json_encode($section->payload, JSON_UNESCAPED_UNICODE))
            ->implode(' ');

        $this->assertDoesNotMatchRegularExpression('/\bICO\b/i', $chinaCopy);
        $this->assertDoesNotMatchRegularExpression('/cryptocurrenc/i', $chinaCopy);

        $this->assertFalse(
            ContentSection::query()
                ->where('site_id', $china->id)
                ->where('section_key', 'sharia')
                ->exists()
        );
    }
}
