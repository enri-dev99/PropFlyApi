<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[fillable(['name', 'code'])]

class Country extends Model
{
public function cities(): HasMany
{
    return $this->hasMany(City::class);
}
}
