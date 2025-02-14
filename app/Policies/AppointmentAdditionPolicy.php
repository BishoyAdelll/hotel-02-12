<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AppointmentAddition;
use App\Models\User;

class AppointmentAdditionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
         return $user->checkPermissionTo('view-any AppointmentAddition');
//        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppointmentAddition $appointmentaddition): bool
    {
         return $user->checkPermissionTo('view AppointmentAddition');
//        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->checkPermissionTo('create AppointmentAddition');
//        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppointmentAddition $appointmentaddition): bool
    {
         return $user->checkPermissionTo('update AppointmentAddition');
//        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppointmentAddition $appointmentaddition): bool
    {
         return $user->checkPermissionTo('delete AppointmentAddition');
//        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppointmentAddition $appointmentaddition): bool
    {
         return $user->checkPermissionTo('restore AppointmentAddition');
//        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppointmentAddition $appointmentaddition): bool
    {
         return $user->checkPermissionTo('force-delete AppointmentAddition');
//        return true;
    }
}
