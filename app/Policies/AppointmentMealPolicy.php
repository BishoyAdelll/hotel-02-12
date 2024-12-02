<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AppointmentMeal;
use App\Models\User;

class AppointmentMealPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any AppointmentMeal');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppointmentMeal $appointmentmeal): bool
    {
        return $user->checkPermissionTo('view AppointmentMeal');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create AppointmentMeal');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppointmentMeal $appointmentmeal): bool
    {
        return $user->checkPermissionTo('update AppointmentMeal');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppointmentMeal $appointmentmeal): bool
    {
        return $user->checkPermissionTo('delete AppointmentMeal');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppointmentMeal $appointmentmeal): bool
    {
        return $user->checkPermissionTo('restore AppointmentMeal');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppointmentMeal $appointmentmeal): bool
    {
        return $user->checkPermissionTo('force-delete AppointmentMeal');
    }
}
