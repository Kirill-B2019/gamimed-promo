<?php

namespace App\Policies;

use App\Models\ContactMessage;
use App\Models\User;

class ContactMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ContactMessage $message): bool
    {
        return $user->canAccessSite($message->site_id);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ContactMessage $message): bool
    {
        return $user->canWrite() && $user->canAccessSite($message->site_id);
    }

    public function delete(User $user, ContactMessage $message): bool
    {
        return $user->isSuperAdmin() && $user->canAccessSite($message->site_id);
    }
}
