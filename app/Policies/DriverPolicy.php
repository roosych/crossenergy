<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissions('create', Driver::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissions('update', Driver::class);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissions('delete', Driver::class);
    }

    /**
     * Determine whether the user can availability the model.
     */
    public function availability(User $user): bool
    {
        return $user->hasPermissions('availability', Driver::class);
    }
}
