<?php

namespace App\Policies;

use App\Models\AnalyticsEvent;
use App\Models\User;

class AnalyticsEventPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AnalyticsEvent $event): bool
    {
        return $user->canAccessSite($event->site_id);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AnalyticsEvent $event): bool
    {
        return false;
    }

    public function delete(User $user, AnalyticsEvent $event): bool
    {
        return $user->isSuperAdmin();
    }
}
