<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HubSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function globalValues(): array
    {
        return Cache::remember('hub_settings.global', 300, function () {
            $settings = [];

            foreach (self::query()->get() as $setting) {
                $value = $setting->value ?? [];

                $settings[$setting->key] = match ($setting->key) {
                    'presale_price_usd' => (float) ($value['value'] ?? 0),
                    default => $value,
                };
            }

            return $settings;
        });
    }

    public static function set(string $key, mixed $value): self
    {
        $setting = self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? $value : ['value' => $value]],
        );

        Cache::forget('hub_settings.global');

        return $setting;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $values = self::globalValues();

        return $values[$key] ?? $default;
    }
}
