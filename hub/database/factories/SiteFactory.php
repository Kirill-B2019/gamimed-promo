<?php

namespace Database\Factories;

use App\Enums\SiteStatus;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Site>
 */
class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        $slug = fake()->unique()->slug(2);

        return [
            'slug' => $slug,
            'name' => fake()->company(),
            'domain' => $slug.'.example.test',
            'locales' => ['en'],
            'default_locale' => 'en',
            'status' => SiteStatus::Active,
            'settings' => [],
        ];
    }
}
