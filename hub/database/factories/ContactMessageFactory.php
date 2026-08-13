<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\ContactMessage;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'locale' => 'en',
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'messenger' => fake()->randomElement(['whatsapp', 'wechat', 'telegram']),
            'message' => fake()->paragraph(),
            'meta' => [],
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'status' => LeadStatus::New,
        ];
    }
}
