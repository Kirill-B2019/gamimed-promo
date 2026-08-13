<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Database\Factories\ContentSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentSection extends Model
{
    /** @use HasFactory<ContentSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'site_id',
        'site_group_id',
        'section_key',
        'locale',
        'payload',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'status' => ContentStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $section): void {
            if ($section->site_id) {
                $section->site_group_id = null;
                $section->scope_key = 'site:'.$section->site_id;
            } elseif ($section->site_group_id) {
                $section->scope_key = 'group:'.$section->site_group_id;
            } else {
                $section->scope_key = 'global';
            }
        });
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function siteGroup(): BelongsTo
    {
        return $this->belongsTo(SiteGroup::class);
    }

    public function isSiteOverride(): bool
    {
        return $this->site_id !== null;
    }

    public function isGroupScoped(): bool
    {
        return $this->site_group_id !== null && $this->site_id === null;
    }

    public function isGlobalScoped(): bool
    {
        return $this->site_id === null && $this->site_group_id === null;
    }

    public function scopeLabel(): string
    {
        if ($this->isSiteOverride()) {
            return 'site';
        }

        if ($this->isGroupScoped()) {
            return 'group';
        }

        return 'global';
    }
}
