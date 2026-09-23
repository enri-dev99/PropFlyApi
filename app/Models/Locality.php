<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\City;
use App\Models\Property;

#[Fillable(['name', 'city_id'])]
class Locality extends Model
{
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function properties(){
        return $this->hasMany(Property::class);
    }
}
