# GAMIMED Hub

Laravel 11 + Filament 3 control plane for ARAB / CHINA promo sites.

Deploy topology, env vars, Cloudflare, and the content-publish checklist live in the **[root README](../README.md)**.

## Stack

- Laravel 11, Filament 3 admin (`/admin`)
- Sanctum site API tokens (`/api/v1/*`)
- RBAC: `super_admin` | `site_manager` | `viewer`

## Quick start

```bash
cd hub
cp .env.example .env
php artisan key:generate
# ensure database/database.sqlite exists when using sqlite
php artisan migrate --seed
php artisan serve
```

Admin UI: `http://localhost:8000/admin`  
Credentials come from `HUB_ADMIN_EMAIL` / `HUB_ADMIN_PASSWORD` in `.env`.

Site API tokens are written once to `storage/app/site-tokens/{slug}.token` by the seeder (gitignored). Re-issue from Filament → Sites → **Issue API token**. Copy each token into the matching front as `SITE_SECRET`.

## API (site Bearer token)

| Method | Path | Purpose |
|--------|------|---------|
| GET | `/api/v1/content` | Published sections (+ site settings) |
| POST | `/api/v1/leads` | Contact / lead intake |
| POST | `/api/v1/events` | First-party analytics |

Rate limit: `hub-api` (120/min per site). CORS: `CORS_ALLOWED_ORIGINS`.

## Hardening

- `HUB_ADMIN_ALLOWED_IPS` — comma-separated IPv4/IPv6 or CIDR for `/admin` (empty = allow all, local only). Does **not** apply to `/api/v1/*`.
- `HUB_TRUSTED_PROXIES` — set to `*` (or Cloudflare/nginx IPs) so `Request::ip()` is the client.
- `HUB_ADMIN_2FA=true` — require authenticator TOTP (or a one-time recovery code) on every Filament session.
- Prefer VPN / private network in front of the Hub VDS. `/admin` sends `noindex, nofollow`; `public/robots.txt` disallows crawlers.

## Models

`Site`, `SiteGroup`, `ContentSection`, `ContactMessage`, `AnalyticsEvent`, `User` (+ `site_user`, `site_group_site` pivots).
