<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Note extends Model
{
    protected $table = 'notes';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'aroma',
        'fragrance_family',
        'origin',
        'extraction',
        'common_role',
        'ingredient_type',
        'longevity',
        'intensity',
        'best_paired_with',
        'image',
        'about_image',
    ];

    public function perfumes(): BelongsToMany
    {
        return $this->belongsToMany(
            Perfume::class,
            'perfume_notes'
        )->withPivot('note_type');
    }

    public function characteristics(): HasOne
    {
        return $this->hasOne(NoteCharacteristic::class);
    }
}