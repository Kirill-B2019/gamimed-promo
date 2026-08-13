<?php

namespace App\Filament\Pages;

use App\Services\Totp;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TwoFactorSetup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $slug = 'two-factor-setup';

    protected static ?string $title = 'Two-factor authentication';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $layout = 'filament-panels::components.layout.simple';

    protected static string $view = 'filament.pages.two-factor-setup';

    public string $secret = '';

    public string $otpauth = '';

    public string $code = '';

    /** @var list<string>|null */
    public ?array $recoveryCodes = null;

    public function hasLogo(): bool
    {
        return true;
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function mount(Totp $totp): void
    {
        abort_unless((bool) config('hub.admin_2fa'), 403);

        $user = auth()->user();

        if ($user->two_factor_confirmed_at && session('hub.2fa_passed')) {
            $this->redirect(Filament::getUrl());

            return;
        }

        if ($user->two_factor_confirmed_at) {
            $this->redirect('/admin/two-factor-challenge');

            return;
        }

        if (! $user->two_factor_secret) {
            $user->forceFill([
                'two_factor_secret' => $totp->generateSecret(),
            ])->save();
        }

        $this->secret = $user->two_factor_secret;
        $this->otpauth = $totp->otpauthUri($this->secret, (string) $user->email);
    }

    public function confirm(Totp $totp): void
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();

        if (! $totp->verify((string) $user->two_factor_secret, $this->code)) {
            throw ValidationException::withMessages([
                'code' => 'That code is invalid. Try again.',
            ]);
        }

        $plainCodes = $totp->recoveryCodes();

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => array_map(
                fn (string $code) => Hash::make($code),
                $plainCodes,
            ),
        ])->save();

        session(['hub.2fa_passed' => true]);
        $this->recoveryCodes = $plainCodes;
        $this->code = '';

        Notification::make()
            ->title('Two-factor authentication enabled')
            ->success()
            ->send();
    }

    public function continue(): void
    {
        $this->redirect(Filament::getUrl());
    }
}
