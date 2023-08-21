<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;

class ImagePolicy
{

    public function upload(User $user)
    {
        return $user->hasPermissions('upload', Image::class);
    }

    public function delete(User $user)
    {
        return $user->hasPermissions('delete', Image::class);
    }

}
