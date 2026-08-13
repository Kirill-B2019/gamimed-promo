<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) config('hub.admin_password');

        if ($password === '') {
            throw new RuntimeException('HUB_ADMIN_PASSWORD is empty. Set it in hub/.env and re-seed.');
        }

        $admin = User::query()->updateOrCreate(
            ['email' => (string) config('hub.admin_email', 'admin@gamimed.local')],
            [
                'name' => 'Super Admin',
                'password' => $password,
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ]
        );

        $arabManager = User::query()->updateOrCreate(
            ['email' => (string) config('hub.arab_manager_email', 'arab.manager@gamimed.local')],
            [
                'name' => 'ARAB Manager',
                'password' => $password,
                'role' => UserRole::SiteManager,
                'email_verified_at' => now(),
            ]
        );

        $chinaManager = User::query()->updateOrCreate(
            ['email' => (string) config('hub.china_manager_email', 'china.manager@gamimed.local')],
            [
                'name' => 'CHINA Manager',
                'password' => $password,
                'role' => UserRole::SiteManager,
                'email_verified_at' => now(),
            ]
        );

        $viewer = User::query()->updateOrCreate(
            ['email' => (string) config('hub.viewer_email', 'viewer@gamimed.local')],
            [
                'name' => 'Viewer',
                'password' => $password,
                'role' => UserRole::Viewer,
                'email_verified_at' => now(),
            ]
        );

        $arab = Site::query()->where('slug', 'arab')->first();
        $china = Site::query()->where('slug', 'china')->first();

        if ($arab) {
            $arabManager->sites()->syncWithoutDetaching([$arab->id]);
            $viewer->sites()->syncWithoutDetaching([$arab->id]);
        }

        if ($china) {
            $chinaManager->sites()->syncWithoutDetaching([$china->id]);
            $viewer->sites()->syncWithoutDetaching([$china->id]);
        }

        $this->command?->info("Super admin: {$admin->email}");
        $this->command?->info("ARAB manager: {$arabManager->email}");
        $this->command?->info("CHINA manager: {$chinaManager->email}");
        $this->command?->info("Viewer: {$viewer->email}");
    }
}
