<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AppointmentTool;
use App\Models\User;

class AppointmentToolPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any AppointmentTool');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppointmentTool $appointmenttool): bool
    {
        return $user->checkPermissionTo('view AppointmentTool');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create AppointmentTool');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppointmentTool $appointmenttool): bool
    {
        return $user->checkPermissionTo('update AppointmentTool');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppointmentTool $appointmenttool): bool
    {
        return $user->checkPermissionTo('delete AppointmentTool');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('{{ deleteAnyPermission }}');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppointmentTool $appointmenttool): bool
    {
        return $user->checkPermissionTo('restore AppointmentTool');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('{{ restoreAnyPermission }}');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, AppointmentTool $appointmenttool): bool
    {
        return $user->checkPermissionTo('{{ replicatePermission }}');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('{{ reorderPermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppointmentTool $appointmenttool): bool
    {
        return $user->checkPermissionTo('force-delete AppointmentTool');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('{{ forceDeleteAnyPermission }}');
    }
}
