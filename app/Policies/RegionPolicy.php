<?php

namespace App\Policies;

use App\Models\Region;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RegionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Region $region): bool
    {
        return $user->hasPermission('view-region');
    }


    public function access(User $user): bool
    {
        // return false;
        return $user->hasPermission('access-region');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-region');
    }
    /**
     * Determine whether the user can edit models.
     */
    public function edit(User $user): bool
    {
        return $user->hasPermission('edit-region');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Region $region): bool
    {
        return $user->hasPermission('update-region');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Region $region): bool
    {
        return $user->hasPermission('delete-region');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Region $region): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Region $region): bool
    {
        //
    }
}
