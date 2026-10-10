<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Locality;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name'])]
class City extends Model
{
    use HasFactory;
    public function localities(){
        return $this->hasMany(Locality::class);
    }
}
