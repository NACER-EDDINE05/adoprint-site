<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        // clients can view their own orders; operators/admins can view all
        return $user->id === $order->user_id || $user->hasAnyRole(['operator','admin','super-admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('client') || $user->hasAnyRole(['admin','super-admin']);
    }

    public function update(User $user, Order $order): bool
    {
        return $user->hasAnyRole(['admin','super-admin','operator']);
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole('super-admin');
    }
}
