<?php

namespace Database\Seeders;

use App\Models\HubSetting;
use Illuminate\Database\Seeder;

class HubSettingSeeder extends Seeder
{
    public function run(): void
    {
        HubSetting::set('presale_price_usd', ['value' => 0.05]);
        HubSetting::set('fx_rates', [
            'USD' => 1,
            'AED' => 3.67,
            'SAR' => 3.75,
            'QAR' => 3.64,
            'CNY' => 7.25,
        ]);
        HubSetting::set('feature_flags', [
            'calculator_enabled' => true,
            'whitepaper_download' => true,
            'contact_form' => true,
        ]);
    }
}
