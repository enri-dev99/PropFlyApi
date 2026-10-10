<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\City;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name', 'city_id'])]
class Locality extends Model
{
    use HasFactory;
    
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function properties(){
        return $this->hasMany(Property::class);
    }
}
