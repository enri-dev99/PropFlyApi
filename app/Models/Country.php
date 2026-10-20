<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[fillable(['name', 'code'])]

class Country extends Model
{
use HasFactory;

public function cities(): HasMany
{
    return $this->hasMany(City::class);
}
}
