<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FragranceFamily extends Model
{
    protected $table = 'fragrance_families';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function perfumes(): HasMany
    {
        return $this->hasMany(Perfume::class);
    }
}