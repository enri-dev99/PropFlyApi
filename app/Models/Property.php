<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\User;
use App\Models\Locality;


#[Fillable(['user_id', 'title', 'description', 'price', 'type', 'operation', 'bedrooms', 'surface', 'locality_id', 'address', 'postal_code'])]

class Property extends Model
{
    public const TYPE_APARTMENT = 0;
    public const TYPE_HOUSE = 1;
    public const TYPE_CHALET = 2;
    public const TYPE_LAND = 3;
    public const TYPE_COMMERCIAL = 4;
    public const TYPE_GARAGE = 5;
    public const TYPE_STORAGE = 6;

    public const OPERATION_SALE = 0;
    public const OPERATION_RENT = 1;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }
}
