<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'site_id',
        'locale',
        'name',
        'email',
        'phone',
        'messenger',
        'message',
        'meta',
        'ip_hash',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'status' => LeadStatus::class,
        ];
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
