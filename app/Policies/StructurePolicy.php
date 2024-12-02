<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Structure;
use App\Models\User;

class StructurePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Structure');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Structure $structure): bool
    {
        return $user->checkPermissionTo('view Structure');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Structure');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Structure $structure): bool
    {
        return $user->checkPermissionTo('update Structure');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Structure $structure): bool
    {
        return $user->checkPermissionTo('delete Structure');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Structure $structure): bool
    {
        return $user->checkPermissionTo('restore Structure');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Structure $structure): bool
    {
        return $user->checkPermissionTo('force-delete Structure');
    }
}
