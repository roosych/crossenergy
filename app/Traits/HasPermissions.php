<?php

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermissions(string $action, string $model) :bool
    {
        return $this->hasDirectPermission($action, $model);
    }

    //прямые полномочия
    public function hasDirectPermission(string $action, string $model):bool
    {
        // $this это экземпляр данного трейта(модели) - User
        return $this->permissions
            ->where('action', $action)
            ->where('model', $model)
            ->isNotEmpty();
    }
}
