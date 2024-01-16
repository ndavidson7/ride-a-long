<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request as HttpRequest;

class RequestPolicy
{
    private $ride;

    // https://github.com/laravel/ideas/issues/1612
    public function __construct(HttpRequest $request)
    {
        $this->ride = $request->route('ride') ?? null;
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
    public function view(User $user, Request $request): bool
    {
        return $request->user_id === $user->id || $request->ride->driver_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->ride->user_relation === 'none' && $this->ride->seats_open > 0;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Request $request): bool
    {
        return in_array($request->ride->user_relation, ["driver", "passenger", "requester"]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Request $request): bool
    {
        return $request->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Request $request): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Request $request): bool
    {
        return false;
    }
}
