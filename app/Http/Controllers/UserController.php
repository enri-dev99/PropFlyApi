<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(
      private UserServiceInterface $userService
    ){}

    public function index()
    {
        $users = $this->userService->list();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return new UserResource($user);
    }

    public function show(int $id)
    {
        $user = $this->userService->find($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userService->update($id, $request->validated());
        return new UserResource($user);
    }

    public function destroy(int $id)
    {
        $this->userService->delete($id);
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
