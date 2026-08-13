<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class)->withTimestamps();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [
            UserRole::SuperAdmin,
            UserRole::SiteManager,
            UserRole::Viewer,
        ], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isSiteManager(): bool
    {
        return $this->role === UserRole::SiteManager;
    }

    public function isViewer(): bool
    {
        return $this->role === UserRole::Viewer;
    }

    public function canWrite(): bool
    {
        return $this->role?->canWrite() ?? false;
    }

    /**
     * @return list<int>
     */
    public function accessibleSiteIds(): array
    {
        if ($this->isSuperAdmin()) {
            return Site::query()->pluck('id')->all();
        }

        return $this->sites()->pluck('sites.id')->all();
    }

    public function canAccessSite(Site|int $site): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $siteId = $site instanceof Site ? $site->id : $site;

        return $this->sites()->where('sites.id', $siteId)->exists();
    }
}
