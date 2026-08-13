<?php

namespace App\Filament\Pages;

use App\Services\Totp;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TwoFactorChallenge extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $slug = 'two-factor-challenge';

    protected static ?string $title = 'Verify your identity';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $layout = 'filament-panels::components.layout.simple';

    protected static string $view = 'filament.pages.two-factor-challenge';

    public string $code = '';

    public function hasLogo(): bool
    {
        return true;
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function mount(): void
    {
        abort_unless((bool) config('hub.admin_2fa'), 403);

        $user = auth()->user();

        if (! $user->two_factor_confirmed_at) {
            $this->redirect('/admin/two-factor-setup');

            return;
        }

        if (session('hub.2fa_passed')) {
            $this->redirect(Filament::getUrl());
        }
    }

    public function verify(Totp $totp): void
    {
        $this->validate([
            'code' => ['required', 'string'],
        ]);

        $user = auth()->user();
        $code = strtoupper(trim($this->code));

        if ($totp->verify((string) $user->two_factor_secret, preg_replace('/\s+/', '', $this->code) ?? '')) {
            session(['hub.2fa_passed' => true]);
            $this->redirect(Filament::getUrl());

            return;
        }

        $hashes = $user->two_factor_recovery_codes ?? [];

        foreach ($hashes as $index => $hash) {
            if (is_string($hash) && Hash::check($code, $hash)) {
                unset($hashes[$index]);
                $user->forceFill([
                    'two_factor_recovery_codes' => array_values($hashes),
                ])->save();

                session(['hub.2fa_passed' => true]);
                $this->redirect(Filament::getUrl());

                return;
            }
        }

        throw ValidationException::withMessages([
            'code' => 'Invalid authentication or recovery code.',
        ]);
    }
}
