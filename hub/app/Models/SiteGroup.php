<?php

namespace App\Models;

use Database\Factories\SiteGroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteGroup extends Model
{
    /** @use HasFactory<SiteGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
    ];

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'site_group_site')->withTimestamps();
    }

    public function contentSections(): HasMany
    {
        return $this->hasMany(ContentSection::class);
    }
}
