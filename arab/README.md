# GAMIMED ARAB

Laravel 11 + Livewire 3 + Tailwind CSS 4 promo front (`ar` + `en`, RTL default).

Production deploy (VDS, Cloudflare, Hub API keys) is documented in the **[root README](../README.md)**.

## Setup

```bash
cp .env.example .env
php artisan key:generate
# set HUB_URL, SITE_KEY=arab, SITE_SECRET=<Sanctum site token from Hub>
touch database/database.sqlite
php artisan migrate
composer install
npm install && npm run build
php artisan serve --port=8001
php artisan queue:work   # retries Hub leads/events
```

## Hub client

- `App\Services\HubClient` — GET `/api/v1/content`, POST leads/events
- Content cached 5–15 min (`HUB_CONTENT_CACHE_TTL`); falls back to `config/site.php`
- Failed leads/events dispatch `RetryHubLeadJob` / `RetryHubEventJob` (database queue)

## Locales

- `/` → `/ar`
- `/ar`, `/en` — `SetLocale` middleware + Livewire `LocaleSwitcher`

## Deploy

See the [root README](../README.md) for three-VDS layout, `HUB_URL` / `SITE_KEY` / `SITE_SECRET`, Cloudflare, Hub IP allowlist + 2FA, and the content publication checklist.

Public routes: `/{locale}`, `/{locale}/terms`, `/{locale}/privacy`, `/sitemap.xml`, `/robots.txt`. Optional `GA4_MEASUREMENT_ID` / `YANDEX_METRIKA_ID` / `BAIDU_ANALYTICS_ID`.

## SEO & legal

- Meta / Open Graph / hreflang / JSON-LD on public pages
- `/robots.txt` and `/sitemap.xml`
- Privacy and terms stubs: `/ar/privacy`, `/ar/terms` (and `en`)

## Analytics

Hub first-party events are always queued (`page_view`, `cta_click`, `calculator_use`, `contact_submit`, `whitepaper_download`). Optional extras (leave empty to skip):

- `GA4_MEASUREMENT_ID`
- `YANDEX_METRIKA_ID`
- `BAIDU_ANALYTICS_ID`
