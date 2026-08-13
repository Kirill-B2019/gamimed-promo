<?php

namespace Database\Seeders;

use App\Enums\SiteStatus;
use App\Models\Site;
use App\Models\SiteGroup;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $allPreIco = SiteGroup::query()->updateOrCreate(
            ['slug' => 'all-preico'],
            [
                'name' => 'All Pre-ICO',
                'description' => 'Shared content scope for ARAB and CHINA promo sites.',
            ]
        );

        $mena = SiteGroup::query()->updateOrCreate(
            ['slug' => 'mena'],
            [
                'name' => 'MENA',
                'description' => 'ARAB promo (UAE, Saudi Arabia, Qatar, and wider MENA).',
            ]
        );

        $apac = SiteGroup::query()->updateOrCreate(
            ['slug' => 'apac'],
            [
                'name' => 'APAC',
                'description' => 'CHINA promo (Greater China and APAC).',
            ]
        );

        $arab = Site::query()->updateOrCreate(
            ['slug' => 'arab'],
            [
                'name' => 'ARAB Promo',
                'domain' => env('ARAB_DOMAIN', 'arab.example.test'),
                'locales' => ['ar', 'en'],
                'default_locale' => 'ar',
                'status' => SiteStatus::Active,
                'settings' => [
                    'currencies' => ['USD', 'AED', 'SAR', 'QAR'],
                    'messenger' => 'whatsapp',
                    'whatsapp' => [
                        'url' => 'https://wa.me/971500000000',
                        'label' => 'WhatsApp',
                    ],
                    'social' => [
                        ['key' => 'x', 'url' => 'https://x.com/gamimed', 'label' => 'X'],
                        ['key' => 'telegram', 'url' => 'https://t.me/gamimed', 'label' => 'Telegram'],
                        ['key' => 'snapchat', 'url' => 'https://www.snapchat.com/add/gamimed', 'label' => 'Snapchat'],
                        ['key' => 'linkedin', 'url' => 'https://www.linkedin.com/company/gamimed', 'label' => 'LinkedIn'],
                        ['key' => 'whatsapp', 'url' => 'https://wa.me/971500000000', 'label' => 'WhatsApp'],
                    ],
                ],
            ]
        );

        $china = Site::query()->updateOrCreate(
            ['slug' => 'china'],
            [
                'name' => 'CHINA Promo',
                'domain' => env('CHINA_DOMAIN', 'china.example.test'),
                'locales' => ['zh_CN', 'en'],
                'default_locale' => 'zh_CN',
                'status' => SiteStatus::Active,
                'settings' => [
                    'currencies' => ['USD', 'CNY'],
                    'messenger' => 'wechat',
                    'wechat' => [
                        'url' => 'https://weixin.qq.com/',
                        'label' => 'WeChat',
                        'id' => 'GAMIMED',
                        'qr' => '/images/wechat-qr.svg',
                    ],
                    'social' => [
                        ['key' => 'weibo', 'url' => 'https://weibo.com/gamimed', 'label' => 'Weibo'],
                        ['key' => 'telegram', 'url' => 'https://t.me/gamimed', 'label' => 'Telegram'],
                        ['key' => 'wechat', 'url' => 'https://weixin.qq.com/', 'label' => 'WeChat'],
                    ],
                ],
            ]
        );

        $allPreIco->sites()->syncWithoutDetaching([$arab->id, $china->id]);
        $mena->sites()->syncWithoutDetaching([$arab->id]);
        $apac->sites()->syncWithoutDetaching([$china->id]);

        foreach ([$arab, $china] as $site) {
            if ($site->tokens()->count() > 0) {
                continue;
            }

            $plain = $site->createToken('site-api', ['site:api'])->plainTextToken;

            if (app()->environment('testing')) {
                continue;
            }

            $path = storage_path('app/site-tokens/'.$site->slug.'.token');

            if (! is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            file_put_contents($path, $plain.PHP_EOL);
        }

        $this->command?->info('Site API tokens written to storage/app/site-tokens/ (gitignored).');
    }
}
