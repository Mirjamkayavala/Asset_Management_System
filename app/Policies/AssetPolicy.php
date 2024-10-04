<?php
namespace App\Policies;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true; // or check a condition based on the user's permissions
    }

    public function access(User $user): bool
    {
        // return false;
        return $user->hasPermission('access-asset');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Asset $asset)
    {
        // Check if the user has the required permission
        return $user->hasPermission('view-asset');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-asset');
    }
    /**
     * Determine whether the user can EDIT models.
     */
    public function edit(User $user)
    {
        return $user->hasPermission('edit-asset');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Asset $asset)
    {
        // Ensure the variable name is correct and match the expected argument
        return $user->hasPermission('update-asset'); //|| $user->id === $asset->user_id; // Check if the user is the owner of the asset
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Asset $asset)
    {
        return $user->hasPermission('delete-asset');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Asset $asset)
    {
        // Add logic for restoring an asset, if necessary
        return $user->hasPermission('restore-asset');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Asset $asset)
    {
        // Add logic for permanently deleting an asset, if necessary
        return $user->hasPermission('delete-asset');
    }
}
