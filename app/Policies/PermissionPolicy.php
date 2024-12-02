<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
         return $user->checkPermissionTo('view-any Permission');
//        return true;

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, \Spatie\Permission\Models\Permission $permission): bool
    {
         return $user->checkPermissionTo('view Permission');
//        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->checkPermissionTo('create Permission');
//        return true;

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, \Spatie\Permission\Models\Permission $permission): bool
    {
         return $user->checkPermissionTo('update Permission');
//        return true;

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, \Spatie\Permission\Models\Permission $permission): bool
    {
         return $user->checkPermissionTo('delete Permission');
//        return true;

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, \Spatie\Permission\Models\Permission $permission): bool
    {
         return $user->checkPermissionTo('restore Permission');
//        return true;

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, \Spatie\Permission\Models\Permission $permission): bool
    {

          return $user->checkPermissionTo('force-delete Permission');
//        return true;

    }
}
