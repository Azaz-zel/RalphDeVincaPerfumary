<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $table = 'brands';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'tagline',
        'founded_year',
        'founded_location',
        'founder',
        'about',
        'hero_image',
        'about_image',
        'philosophy_quote',
        'philosophy_intro',
        'philosophy_pillars',
    ];

    protected $casts = [
        'philosophy_pillars' => 'array',
    ];

    public function perfumes(): HasMany
    {
        return $this->hasMany(Perfume::class);
    }

    /**
     * First paragraph of the about copy — used for meta descriptions and
     * card summaries where the full multi-paragraph text is too long.
     */
    public function getAboutIntroAttribute(): string
    {
        return explode("\n\n", $this->about ?? '')[0] ?? '';
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(BrandMilestone::class)->orderBy('sort_order');
    }
}
