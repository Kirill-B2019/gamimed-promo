<?php

namespace Database\Factories;

use App\Models\SiteGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SiteGroup>
 */
class SiteGroupFactory extends Factory
{
    protected $model = SiteGroup::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($name),
            'name' => Str::title($name),
            'description' => fake()->sentence(),
        ];
    }
}
