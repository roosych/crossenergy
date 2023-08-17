<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Driver;
use App\Models\Equipment;
use App\Models\Image;
use App\Models\Owner;
use App\Models\User;
use App\Models\VehicleType;
use App\Policies\DriverPolicy;
use App\Policies\EquipmentPolicy;
use App\Policies\ImagePolicy;
use App\Policies\OwnerPolicy;
use App\Policies\UserPolicy;
use App\Policies\VehicleTypePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Equipment::class => EquipmentPolicy::class,
        VehicleType::class => VehicleTypePolicy::class,
        Owner::class => OwnerPolicy::class,
        Driver::class => DriverPolicy::class,
        Image::class => ImagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
