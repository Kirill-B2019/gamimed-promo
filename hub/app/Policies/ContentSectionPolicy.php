<?php

namespace App\Policies;

use App\Models\ContentSection;
use App\Models\User;

class ContentSectionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ContentSection $section): bool
    {
        return $this->userCanAccessSection($user, $section);
    }

    public function create(User $user): bool
    {
        return $user->canWrite();
    }

    public function update(User $user, ContentSection $section): bool
    {
        return $user->canWrite() && $this->userCanAccessSection($user, $section);
    }

    public function delete(User $user, ContentSection $section): bool
    {
        return $user->canWrite() && $this->userCanAccessSection($user, $section);
    }

    private function userCanAccessSection(User $user, ContentSection $section): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($section->isGlobalScoped() || $section->isGroupScoped()) {
            return $user->canWrite() || $user->isViewer();
        }

        return $section->site_id && $user->canAccessSite($section->site_id);
    }
}
