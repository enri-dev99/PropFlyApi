<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\User;
use App\Models\Locality;
use App\Enums\PropertyType;
use App\Enums\PropertyOperation;

#[Fillable(['user_id', 'title', 'description', 'price', 'type', 'operation', 'bedrooms', 'surface', 'locality_id', 'address', 'postal_code'])]

class Property extends Model
{

    protected function casts(): array
    {
        return [
            'type' => PropertyType::class,
            'operation' => PropertyOperation::class,
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }
}
