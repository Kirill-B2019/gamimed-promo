<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Promo fronts have no local content DB. Hub owns sites, copy, leads, and events.
        // Queue / cache / sessions tables come from migrations; retry jobs use the database queue.
    }
}
