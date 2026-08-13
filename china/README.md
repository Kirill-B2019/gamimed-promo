# GAMIMED CHINA

Laravel 11 + Livewire 3 + Tailwind CSS 4 promo front (`zh_CN` + `en`, LTR).

Public copy (including meta and legal stubs) avoids «ICO» / «cryptocurrency» wording. Primary contact channel is WeChat.

Production deploy (VDS, Hub API keys) is documented in the **[root README](../README.md)**.

## Setup

```bash
cp .env.example .env
php artisan key:generate
# set HUB_URL, SITE_KEY=china, SITE_SECRET=<Sanctum site token from Hub>
touch database/database.sqlite
php artisan migrate
composer install
npm install && npm run build
php artisan serve --port=8002
php artisan queue:work   # retries Hub leads/events
```

## Hub client

- `App\Services\HubClient` — GET `/api/v1/content`, POST leads/events
- Content cached 5–15 min (`HUB_CONTENT_CACHE_TTL`); falls back to `config/site.php`
- Failed leads/events dispatch `RetryHubLeadJob` / `RetryHubEventJob` (database queue)

## Locales

- `/` → `/zh_CN`
- `/zh_CN`, `/en` — `SetLocale` middleware + Livewire `LocaleSwitcher`

## SEO & legal

- Meta / Open Graph / hreflang / JSON-LD on public pages
- `/robots.txt` and `/sitemap.xml`
- Privacy and terms stubs: `/zh_CN/privacy`, `/zh_CN/terms` (and `en`)

## Analytics

Hub first-party events are always queued. Optional extras (leave empty to skip):

- `GA4_MEASUREMENT_ID`
- `YANDEX_METRIKA_ID`
- `BAIDU_ANALYTICS_ID` (typical for this front)
