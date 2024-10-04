<?php

namespace App\Policies;

use App\Models\AssetHistory;
use App\Models\User;

class AssetHistoryPolicy
{
    /**
     * Determine whether the user can view any asset histories.
     */
    public function viewAny(User $user): bool
    {
        // Only allow users with specific roles or permissions
        return $user->hasPermissionTo('view-assetHistory');
    }

    public function access(User $user): bool
    {
        // return false;
        return $user->hasPermission('access-assetHistory');
    }

    /**
     * Determine whether the user can view the asset history.
     */
    public function view(User $user, AssetHistory $assetHistory = null): bool
    {
        // Only allow users with specific roles or permissions
        return $user->hasPermissionTo('view-assetHistory');
    }

    /**
     * Determine whether the user can create asset histories.
     */
    public function create(User $user): bool
    {
        // Only allow users with specific roles or permissions
        return $user->hasPermissionTo('create-assetHistory');
    }

    /**
     * Determine whether the user can update the asset history.
     */
    public function update(User $user, AssetHistory $assetHistory = null): bool
    {
        // Only allow users with specific roles or permissions
        return $user->hasPermissionTo('update-assetHistory');
    }

    /**
     * Determine whether the user can delete the asset history.
     */
    public function delete(User $user, AssetHistory $assetHistory = null): bool
    {
        // Only allow users with specific roles or permissions
        return $user->hasPermissionTo('delete-assetHistory');
    }
}
