<?php

namespace App\Policies;

use App\Models\User;

class ImagePolicy
{

    public function upload(User $user)
    {
        return $user->hasPermissions('upload', User::class);
    }

    public function delete(User $user)
    {
        return $user->hasPermissions('delete', User::class);
    }

}
