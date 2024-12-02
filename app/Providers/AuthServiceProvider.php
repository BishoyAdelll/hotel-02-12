<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Capacity;
use App\Models\User;
use App\Observers\AppointmentObserver;
use App\Observers\UserObserver;
use App\Policies\CapacityPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\ActivityPolicy;
use App\Policies\UserPolicy;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         Activity::class => Activity::class,
         Permission::class=>PermissionPolicy::class,
         Role::class=>RolePolicy::class,
         User::class=>UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true: null;
        });
    }
}
