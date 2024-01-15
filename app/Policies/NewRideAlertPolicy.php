<?php

namespace App\Policies;

use App\Models\NewRideAlert;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewRideAlertPolicy
{
    private static function interact(User $user, NewRideAlert $newRideAlert): bool
    {
        return $user->id === $newRideAlert->user_id;
    }

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
    public function view(User $user, NewRideAlert $newRideAlert): bool
    {
        return self::interact($user, $newRideAlert);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NewRideAlert $newRideAlert): bool
    {
        return self::interact($user, $newRideAlert);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NewRideAlert $newRideAlert): bool
    {
        return self::interact($user, $newRideAlert);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NewRideAlert $newRideAlert): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NewRideAlert $newRideAlert): bool
    {
        return false;
    }
}
