<?php

namespace App\Policies;

use App\Models\User;

class BaseAdminPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin','super-admin','operator']);
    }

    public function view(User $user, $model): bool
    {
        return $user->hasAnyRole(['admin','super-admin','operator']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin','super-admin']);
    }

    public function update(User $user, $model): bool
    {
        return $user->hasAnyRole(['admin','super-admin']);
    }

    public function delete(User $user, $model): bool
    {
        return $user->hasRole('super-admin');
    }
}
