<?php

namespace App\Policies;

use App\Models\Capacity;
use Illuminate\Auth\Access\Response;
use App\Models\User;

class CapacityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any capacity');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Capacity $capacity): bool
    {
        return $user->checkPermissionTo('view capacity');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create capacity');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Capacity $capacity): bool
    {
        return $user->checkPermissionTo('update capacity');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Capacity $capacity): bool
    {
        return $user->checkPermissionTo('delete capacity');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Capacity $capacity): bool
    {
        return $user->checkPermissionTo('restore capacity');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Capacity $capacity): bool
    {
        return $user->checkPermissionTo('force-delete capacity');
    }
}
