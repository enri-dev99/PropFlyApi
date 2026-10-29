<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\UserResource;
use App\Http\Resources\PublicUserResource;
use Illuminate\Support\Facades\Auth;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserRoleRequest;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService
    ) {}

    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = $this->userService->list();

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);
        if (Auth::user()->isAdmin()) {
            return new UserResource($user);
        }
        return new PublicUserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        $user = $this->userService->update($user->id, $request->validated());
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $this->userService->delete($user->id);
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
    public function updateRole(UpdateUserRoleRequest $request, User $user)
    {
        Gate::authorize('updateRole', $user);
        $user = $this->userService->updateRole($user->id, $request->validated('role'));
        return new UserResource($user);
    }
}
