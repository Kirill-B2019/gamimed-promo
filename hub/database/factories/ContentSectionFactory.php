<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\ContentSection;
use App\Models\Site;
use App\Models\SiteGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContentSection>
 */
class ContentSectionFactory extends Factory
{
    protected $model = ContentSection::class;

    public function definition(): array
    {
        return [
            'site_id' => null,
            'site_group_id' => null,
            'section_key' => fake()->unique()->slug(2),
            'locale' => 'en',
            'payload' => ['title' => fake()->sentence()],
            'status' => ContentStatus::Published,
        ];
    }

    public function global(): static
    {
        return $this->state(fn () => [
            'site_id' => null,
            'site_group_id' => null,
        ]);
    }

    public function forGroup(SiteGroup $group): static
    {
        return $this->state(fn () => [
            'site_id' => null,
            'site_group_id' => $group->id,
        ]);
    }

    public function forSite(Site $site): static
    {
        return $this->state(fn () => [
            'site_id' => $site->id,
            'site_group_id' => null,
        ]);
    }
}
