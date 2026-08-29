<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Occasion extends Model
{
    protected $table = 'occasions';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function perfumes(): BelongsToMany
    {
        return $this->belongsToMany(
            Perfume::class,
            'perfume_occasions'
        );
    }
}