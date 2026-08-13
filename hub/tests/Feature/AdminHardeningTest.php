<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Pages\TwoFactorChallenge;
use App\Models\User;
use App\Services\Totp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_admin(): void
    {
        $this->get('/')->assertRedirect('/admin');
    }

    public function test_admin_login_is_noindexed(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('noindex', false);
    }

    public function test_empty_ip_allowlist_permits_admin(): void
    {
        config(['hub.admin_allowed_ips' => []]);

        $user = User::factory()->superAdmin()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk();
    }

    public function test_admin_is_forbidden_when_client_ip_is_not_allowlisted(): void
    {
        config(['hub.admin_allowed_ips' => ['203.0.113.10']]);

        $user = User::factory()->superAdmin()->create();

        $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '198.51.100.1'])
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_is_allowed_when_client_ip_matches_cidr(): void
    {
        config(['hub.admin_allowed_ips' => ['127.0.0.0/8']]);

        $user = User::factory()->superAdmin()->create();

        $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->get('/admin')
            ->assertOk();
    }

    public function test_site_api_is_not_blocked_by_admin_ip_allowlist(): void
    {
        config(['hub.admin_allowed_ips' => ['203.0.113.10']]);

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.1'])
            ->getJson('/api/v1/content')
            ->assertUnauthorized();
    }

    public function test_required_2fa_redirects_unenrolled_user_to_setup(): void
    {
        config(['hub.admin_2fa' => true]);

        $user = User::factory()->create(['role' => UserRole::Viewer]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect('/admin/two-factor-setup');
    }

    public function test_required_2fa_redirects_enrolled_user_to_challenge(): void
    {
        config(['hub.admin_2fa' => true]);

        $totp = app(Totp::class);
        $secret = $totp->generateSecret();
        $user = User::factory()->superAdmin()->create([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect('/admin/two-factor-challenge');
    }

    public function test_valid_totp_code_unlocks_the_admin_session(): void
    {
        config(['hub.admin_2fa' => true]);

        $totp = app(Totp::class);
        $secret = $totp->generateSecret();
        $user = User::factory()->superAdmin()->create([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(TwoFactorChallenge::class)
            ->set('code', $totp->current($secret))
            ->call('verify')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertTrue(session('hub.2fa_passed'));
    }
}
