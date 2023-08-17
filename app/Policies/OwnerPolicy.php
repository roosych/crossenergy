<?php

namespace App\Policies;

use App\Models\Owner;
use App\Models\User;

class OwnerPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissions('create', Owner::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissions('update', Owner::class);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissions('delete', Owner::class);
    }
}
