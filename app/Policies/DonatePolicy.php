<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Donate;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_donate');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Donate $donate): bool
    {
        return $user->can('view_donate');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_donate');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Donate $donate): bool
    {
        return $user->can('update_donate');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Donate $donate): bool
    {
        return $user->can('delete_donate');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_donate');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Donate $donate): bool
    {
        return $user->can('force_delete_donate');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_donate');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Donate $donate): bool
    {
        return $user->can('restore_donate');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_donate');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Donate $donate): bool
    {
        return $user->can('replicate_donate');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_donate');
    }
}
