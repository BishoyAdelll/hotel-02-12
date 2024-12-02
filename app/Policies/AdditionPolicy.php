<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Addition;
use App\Models\User;

class AdditionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
         return $user->checkPermissionTo('view-any Addition');
//        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Addition $addition): bool
    {
         return $user->checkPermissionTo('view Addition');
//        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->checkPermissionTo('create Addition');
//        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Addition $addition): bool
    {
         return $user->checkPermissionTo('update Addition');
//        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Addition $addition): bool
    {
         return $user->checkPermissionTo('delete Addition');
//        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Addition $addition): bool
    {
         return $user->checkPermissionTo('restore Addition');
//        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Addition $addition): bool
    {
        return $user->checkPermissionTo('force-delete Addition');
    }
}
