<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament admin IP allowlist
    |--------------------------------------------------------------------------
    |
    | Comma-separated IPv4 addresses or CIDR ranges. Empty = allow all (local).
    | Production should list office / VPN egress IPs. Behind Cloudflare or a
    | reverse proxy, also set HUB_TRUSTED_PROXIES so Request::ip() is the client.
    |
    */

    'admin_allowed_ips' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('HUB_ADMIN_ALLOWED_IPS', ''))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Filament TOTP 2FA
    |--------------------------------------------------------------------------
    |
    | When true, every admin must enroll an authenticator app and pass a TOTP
    | (or recovery code) challenge each session. Leave false for local MVP.
    |
    */

    'admin_2fa' => (bool) env('HUB_ADMIN_2FA', false),

    /*
    |--------------------------------------------------------------------------
    | Seeded Filament accounts
    |--------------------------------------------------------------------------
    |
    | Used by UserSeeder (and re-seed) to upsert the first admins. Login still
    | reads the hashed password from the database — run `php artisan db:seed`
    | after changing these values.
    |
    */

    'admin_email' => env('HUB_ADMIN_EMAIL', 'admin@gamimed.local'),
    'admin_password' => env('HUB_ADMIN_PASSWORD', 'password'),
    'arab_manager_email' => env('HUB_ARAB_MANAGER_EMAIL', 'arab.manager@gamimed.local'),
    'china_manager_email' => env('HUB_CHINA_MANAGER_EMAIL', 'china.manager@gamimed.local'),
    'viewer_email' => env('HUB_VIEWER_EMAIL', 'viewer@gamimed.local'),

];
