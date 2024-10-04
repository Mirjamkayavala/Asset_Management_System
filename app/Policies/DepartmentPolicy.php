<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // return $user->hasRole('admin') || $user->hasPermission('view-department');
        return true;
    }

    public function access(User $user): bool
    {
        // return false;
        return $user->hasPermission('access-department');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Department $department): bool
    {
        return $user->hasPermission('view-department');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-department');
        // return $user->hasRole('admin') || $user->hasPermission('create-department');
    }
    /**
     * Determine whether the user can edit models.
     */
    public function edit(User $user): bool
    {
        return $user->hasPermission('edit-department');
        // return $user->hasRole('admin') || $user->hasPermission('create-department');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Department $department): bool
    {
        return $user->hasPermission('update-department');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->hasPermission('delete-department');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Department $department): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Department $department): bool
    {
        //
    }
}
