<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Locality;
#[Fillable(['name'])]
class City extends Model
{

    public function localities(){
        return $this->hasMany(Locality::class);
    }
}
