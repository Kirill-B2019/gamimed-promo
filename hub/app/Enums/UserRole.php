<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case SiteManager = 'site_manager';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super admin',
            self::SiteManager => 'Site manager',
            self::Viewer => 'Viewer',
        };
    }

    public function canWrite(): bool
    {
        return match ($this) {
            self::SuperAdmin, self::SiteManager => true,
            self::Viewer => false,
        };
    }
}
