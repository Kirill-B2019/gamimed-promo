<?php

namespace App\Policies;

use App\Models\SiteGroup;
use App\Models\User;

class SiteGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SiteGroup $siteGroup): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $groupSiteIds = $siteGroup->sites()->pluck('sites.id')->all();
        $allowed = $user->accessibleSiteIds();

        return count(array_intersect($groupSiteIds, $allowed)) > 0;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, SiteGroup $siteGroup): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, SiteGroup $siteGroup): bool
    {
        return $user->isSuperAdmin();
    }
}
