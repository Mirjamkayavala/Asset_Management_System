<?php

namespace App\Policies;

use App\Models\AssetCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssetCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // return false;
        return true;
    }

    public function access(User $user): bool
    {
        // return false;
        return $user->hasPermission('access-asset_category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AssetCategory $assetCategory): bool
    {
        return $user->hasPermission('view-asset_category');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-asset_category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AssetCategory $assetCategory): bool
    {
        return $user->hasPermission('edit-asset_category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AssetCategory $assetCategory): bool
    {
        return $user->hasPermission('create-asset_category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AssetCategory $assetCategory): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AssetCategory $assetCategory): bool
    {
        //
    }
}
