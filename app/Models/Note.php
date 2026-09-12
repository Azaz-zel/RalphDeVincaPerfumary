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

    /**
     * URL of the photo to display, falling back to the generated placeholder.
     *
     * The image column keeps its slug-derived path even when no file has been
     * supplied yet, so that dropping one in makes it appear without a database
     * change. Checking here means a missing file renders the placeholder
     * straight away instead of costing every card a 404 and a visible flash of
     * a broken image before the onerror handler swaps it out.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return route('placeholder.note', $this->slug);
    }
}
