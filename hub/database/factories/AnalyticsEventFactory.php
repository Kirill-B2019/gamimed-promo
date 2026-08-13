<?php

namespace Database\Factories;

use App\Http\Controllers\Api\V1\EventController;
use App\Models\AnalyticsEvent;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnalyticsEvent>
 */
class AnalyticsEventFactory extends Factory
{
    protected $model = AnalyticsEvent::class;

    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'event_type' => fake()->randomElement(EventController::EVENT_TYPES),
            'locale' => 'en',
            'path' => '/',
            'meta' => [],
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'created_at' => now(),
        ];
    }
}
