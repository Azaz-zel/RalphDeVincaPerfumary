<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Season extends Model
{
    protected $table = 'seasons';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function perfumes(): BelongsToMany
    {
        return $this->belongsToMany(
            Perfume::class,
            'perfume_seasons'
        );
    }
}