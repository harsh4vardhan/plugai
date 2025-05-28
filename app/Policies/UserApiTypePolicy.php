<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserApiTypes;
use Illuminate\Auth\Access\Response;

class UserApiTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserApiTypes $userApiType): bool
    {
        return $user->id === $userApiType->user_id;
    }
    //show

    public function view(User $user, UserApiTypes $userApiType): bool
    {
        return $user->id === $userApiType->user_id;
    }

    public function delete(User $user, UserApiTypes $userApiType): bool
    {
        return $user->id === $userApiType->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserApiTypes $userApiTypes): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserApiTypes $userApiTypes): bool
    {
        return false;
    }
}
