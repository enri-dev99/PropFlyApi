<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{

    public function create(User $user){
        return !$user->isAdmin();
    }
    
    public function update(User $user, Property $property): bool
    {


        return $user->isAdmin() || $property->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->isAdmin() || $property->user_id === $user->id;
    }

}
