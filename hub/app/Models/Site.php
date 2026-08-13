<?php

namespace App\Models;

use App\Enums\SiteStatus;
use Database\Factories\SiteFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Site extends Model implements AuthenticatableContract
{
    /** @use HasFactory<SiteFactory> */
    use Authenticatable, HasApiTokens, HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'domain',
        'locales',
        'default_locale',
        'status',
        'settings',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'locales' => 'array',
            'settings' => 'array',
            'status' => SiteStatus::class,
        ];
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(SiteGroup::class, 'site_group_site')->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function contentSections(): HasMany
    {
        return $this->hasMany(ContentSection::class);
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function analyticsEvents(): HasMany
    {
        return $this->hasMany(AnalyticsEvent::class);
    }

    public function isActive(): bool
    {
        return $this->status === SiteStatus::Active;
    }
}
