<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
         return $user->checkPermissionTo('view-any Customer');
//        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Customer $customer): bool
    {
         return $user->checkPermissionTo('view Customer');
//        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->checkPermissionTo('create Customer');
//        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Customer $customer): bool
    {
         return $user->checkPermissionTo('update Customer');
//        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Customer $customer): bool
    {
         return $user->checkPermissionTo('delete Customer');
//        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Customer $customer): bool
    {
         return $user->checkPermissionTo('restore Customer');
//        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
         return $user->checkPermissionTo('force-delete Customer');
//        return true;
    }
}
