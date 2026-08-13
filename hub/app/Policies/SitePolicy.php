<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;

class SitePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Site $site): bool
    {
        return $user->canAccessSite($site);
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Site $site): bool
    {
        return $user->isSuperAdmin()
            || ($user->isSiteManager() && $user->canAccessSite($site));
    }

    public function delete(User $user, Site $site): bool
    {
        return $user->isSuperAdmin();
    }

    public function manageToken(User $user, Site $site): bool
    {
        return $user->isSuperAdmin();
    }
}
