<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Perfume extends Model
{
    protected $table = 'perfumes';

    protected $fillable = [
        'brand_id',
        'fragrance_family_id',
        'name',
        'slug',
        'gender',
        'concentration',
        'release_year',
        'perfumer',
        'description',
        'rating',
        'rating_count',
        'is_trending',
        'image',
        'origin',
        'bottle_sizes',
        'status',
        'availability',
        'retail_price',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_trending' => 'boolean',
        'release_year' => 'integer',
        'rating_count' => 'integer',
        'retail_price' => 'decimal:2',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function fragranceFamily(): BelongsTo
    {
        return $this->belongsTo(
            FragranceFamily::class,
            'fragrance_family_id'
        );
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(
            Note::class,
            'perfume_notes'
        )->withPivot('note_type');
    }

    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany(
            Season::class,
            'perfume_seasons'
        );
    }

    public function occasions(): BelongsToMany
    {
        return $this->belongsToMany(
            Occasion::class,
            'perfume_occasions'
        );
    }

    /**
     * Notes split by pyramid position.
     *
     * These filter the already-loaded `notes` relation in memory rather than
     * issuing a fresh query per position — the detail page eager-loads notes
     * with their pivot note_type, so re-querying cost three extra round trips.
     */
    public function getTopNotesAttribute()
    {
        return $this->notesOfType('top');
    }

    public function getMiddleNotesAttribute()
    {
        return $this->notesOfType('middle');
    }

    public function getBaseNotesAttribute()
    {
        return $this->notesOfType('base');
    }

    private function notesOfType(string $type)
    {
        return $this->notes->filter(
            fn ($note) => $note->pivot->note_type === $type
        )->values();
    }

    /**
     * Eight-dimension scent profile, derived from the note_characteristics
     * of this perfume's own top/middle/base notes (base notes weighted
     * heaviest since they define a scent's lasting character) plus keyword
     * matching against note names for dimensions note_characteristics
     * doesn't track directly (spiciness, smokiness, powdery).
     */
    public function scentProfile(): array
    {
        $notes = $this->notes;

        if ($notes->isEmpty()) {
            return [
                ['Freshness', 50], ['Sweetness', 50], ['Spiciness', 50], ['Woodiness', 50],
                ['Smokiness', 50], ['Powdery', 50], ['Citrusy', 50], ['Warmth', 50],
            ];
        }

        $weights = ['top' => 1.0, 'middle' => 1.2, 'base' => 1.5];
        $totalWeight = 0;
        $sums = ['fresh' => 0, 'sweet' => 0, 'woody' => 0, 'warm' => 0, 'bright' => 0, 'floral' => 0];

        foreach ($notes as $note) {
            $weight = $weights[$note->pivot->note_type] ?? 1.0;
            $totalWeight += $weight;
            $characteristics = $note->characteristics;

            foreach (array_keys($sums) as $key) {
                $sums[$key] += ($characteristics?->{$key} ?? 0) * $weight;
            }
        }

        $avg = fn (string $key) => $totalWeight > 0 ? (int) round($sums[$key] / $totalWeight) : 50;

        $matchScore = function (array $keywords) use ($notes) {
            $matches = $notes->filter(function ($note) use ($keywords) {
                $name = strtolower($note->name);

                foreach ($keywords as $keyword) {
                    if (str_contains($name, $keyword)) {
                        return true;
                    }
                }

                return false;
            })->count();

            if ($matches === 0) {
                return 5;
            }

            return (int) min(100, round(($matches / $notes->count()) * 240) + 20);
        };

        $citrusy = $avg('bright');
        if (in_array($this->fragranceFamily?->name, ['Citrus', 'Fresh'], true)) {
            $citrusy = min(100, $citrusy + 15);
        }

        $spiciness = $matchScore(['pepper', 'cardamom', 'clove', 'cinnamon', 'ginger', 'saffron', 'nutmeg', 'anise']);
        $smokiness = $matchScore(['oud', 'agarwood', 'incense', 'leather', 'tobacco', 'birch', 'smoke', 'guaiac', 'cade']);
        $powderyKeywordScore = $matchScore(['iris', 'violet', 'musk', 'heliotrope', 'orris', 'powder']);
        $powdery = (int) round(($avg('floral') * 0.4) + ($powderyKeywordScore * 0.6));

        return [
            ['Freshness', $avg('fresh')],
            ['Sweetness', $avg('sweet')],
            ['Spiciness', $spiciness],
            ['Woodiness', $avg('woody')],
            ['Smokiness', $smokiness],
            ['Powdery', $powdery],
            ['Citrusy', $citrusy],
            ['Warmth', $avg('warm')],
        ];
    }

    /**
     * Climate suitability, derived from this perfume's actual seasons and
     * its concentration (heavier concentrations read warmer/colder-weather,
     * lighter ones read fresher/hot-weather).
     */
    public function climateCompatibility(): array
    {
        $seasonNames = $this->seasons->pluck('name');
        $allSeason = $seasonNames->contains('All Season');
        $heavy = in_array($this->concentration, ['Parfum', 'Extrait de Parfum'], true);
        $light = in_array($this->concentration, ['Cologne', 'Eau de Toilette'], true);

        $clamp = fn (int $value) => max(10, min(98, $value));

        $cold = ($allSeason ? 70 : 45)
            + ($seasonNames->contains('Winter') ? 30 : 0)
            + ($seasonNames->contains('Autumn') ? 15 : 0)
            + ($heavy ? 10 : ($light ? -10 : 0));

        $mild = ($allSeason ? 80 : 55)
            + ($seasonNames->contains('Spring') ? 25 : 0)
            + ($seasonNames->contains('Autumn') ? 10 : 0);

        $hot = ($allSeason ? 60 : 40)
            + ($seasonNames->contains('Summer') ? 35 : 0)
            + ($light ? 15 : ($heavy ? -15 : 0));

        $humid = ($allSeason ? 55 : 35)
            + ($seasonNames->contains('Summer') ? 25 : 0)
            + ($light ? 10 : ($heavy ? -10 : 0));

        return [
            ['Cold Weather', $clamp($cold)],
            ['Mild Weather', $clamp($mild)],
            ['Hot Weather', $clamp($hot)],
            ['Humid Weather', $clamp($humid)],
        ];
    }

    /**
     * Which times of day this fragrance typically performs best at, derived
     * from its own scent profile (fresh/citrus reads as daytime, warm/woody/
     * smoky reads as evening or night) rather than concentration alone —
     * concentration alone is too coarse since ~75% of the catalogue is EDP.
     */
    public function bestTimes(): array
    {
        $profile = collect($this->scentProfile())->pluck(1, 0);

        $lightness = (($profile['Freshness'] + $profile['Citrusy']) / 2)
            - (($profile['Warmth'] + $profile['Woodiness'] + $profile['Smokiness']) / 3);

        $lightness += match ($this->fragranceFamily?->name) {
            'Citrus', 'Fresh', 'Aquatic' => 15,
            'Amber', 'Woody', 'Musky' => -10,
            default => 0,
        };

        $lightness += match ($this->concentration) {
            'Cologne' => 20,
            'Eau de Toilette' => 10,
            'Parfum' => -10,
            'Extrait de Parfum' => -15,
            default => 0, // Eau de Parfum
        };

        return match (true) {
            $lightness >= 35 => ['Day'],
            $lightness >= 12 => ['Day', 'Evening'],
            $lightness >= -15 => ['Evening'],
            $lightness >= -35 => ['Evening', 'Night'],
            default => ['Night'],
        };
    }

    /**
     * Sub-scores behind the headline rating, derived from concentration,
     * how many seasons/occasions the perfume suits, brand type, and price.
     */
    public function ratingBreakdown(): array
    {
        // The rating column is a 0-5 scale (observed range ~3.75-4.49), so
        // sub-score deltas are kept small to stay proportional to it.
        $rating = (float) ($this->rating ?? 4.0);
        $clamp = fn (float $value) => round(max(0.5, min(5, $value)), 1);

        $performance = match ($this->concentration) {
            'Extrait de Parfum' => $rating + 0.3,
            'Parfum' => $rating + 0.2,
            'Eau de Parfum' => $rating + 0.05,
            'Eau de Toilette' => $rating - 0.15,
            default => $rating - 0.25, // Cologne
        };

        $coverage = $this->seasons->count() + $this->occasions->count();
        $versatility = $rating + ($coverage >= 8 ? 0.25 : ($coverage <= 3 ? -0.25 : 0));

        $uniqueness = $rating
            + ($this->brand?->type === 'niche' ? 0.25 : -0.15)
            + ($this->is_trending ? -0.1 : 0.1);

        $value = $rating;
        if ($this->retail_price) {
            $value += $this->retail_price >= 3_000_000 ? -0.3 : ($this->retail_price <= 1_500_000 ? 0.2 : 0);
        }

        return [
            ['Scent Quality', $clamp($rating + 0.15)],
            ['Performance', $clamp($performance)],
            ['Versatility', $clamp($versatility)],
            ['Uniqueness', $clamp($uniqueness)],
            ['Value', $clamp($value)],
        ];
    }

    /**
     * Short descriptor tags summarising the fragrance's character.
     */
    public function overallImpressionTags(): array
    {
        $familyTags = [
            'Citrus' => ['Zesty', 'Uplifting'],
            'Floral' => ['Elegant', 'Romantic'],
            'Woody' => ['Grounded', 'Earthy'],
            'Amber' => ['Warm', 'Sensual'],
            'Vanilla' => ['Cozy', 'Sweet'],
            'Fresh' => ['Clean', 'Crisp'],
            'Aquatic' => ['Clean', 'Airy'],
            'Musky' => ['Sensual', 'Intimate'],
            'Gourmand' => ['Sweet', 'Inviting'],
        ];

        $genderTags = [
            'men' => 'Confident',
            'women' => 'Graceful',
            'unisex' => 'Versatile',
        ];

        $tags = $familyTags[$this->fragranceFamily?->name] ?? ['Distinctive', 'Refined'];
        $tags[] = $genderTags[$this->gender] ?? 'Modern';

        if ($this->rating && $this->rating >= 4.3) {
            $tags[] = 'Exceptional';
        } elseif ($this->is_trending) {
            $tags[] = 'Popular';
        } elseif ($this->brand?->type === 'niche') {
            $tags[] = 'Distinctive';
        } else {
            $tags[] = 'Memorable';
        }

        return array_values(array_unique($tags));
    }

    /**
     * "Community opinion" pros, inferred from this perfume's own
     * concentration, brand type, seasonal/occasion coverage, and ratings.
     */
    public function communityPros(): array
    {
        $pros = [];

        if (in_array($this->concentration, ['Parfum', 'Extrait de Parfum'], true)) {
            $pros[] = 'Excellent longevity thanks to its high concentration';
        } elseif ($this->concentration === 'Eau de Parfum') {
            $pros[] = 'Solid longevity for everyday wear';
        } else {
            $pros[] = 'Light, easy-to-wear projection for daytime use';
        }

        if ($this->brand?->type === 'niche') {
            $pros[] = 'Distinctive character, not overexposed like mainstream releases';
        } else {
            $pros[] = 'Widely loved, easy to compliment-hunt with';
        }

        if (($this->seasons->count() + $this->occasions->count()) >= 7) {
            $pros[] = 'Highly versatile across seasons and occasions';
        }

        if ($this->rating && $this->rating >= 4.2) {
            $pros[] = 'Consistently high praise from wearers';
        }

        if ($this->rating_count && $this->rating_count >= 500) {
            $pros[] = 'Proven crowd-pleaser with a large fan base';
        }

        $pros[] = 'Reflects the craftsmanship of '.($this->brand?->name ?? 'the house');

        return array_slice(array_values(array_unique($pros)), 0, 6);
    }

    /**
     * "Community opinion" cons, inferred the same way as communityPros().
     */
    public function communityCons(): array
    {
        $cons = [];

        if (in_array($this->concentration, ['Cologne', 'Eau de Toilette'], true)) {
            $cons[] = 'Lighter concentration may need reapplication through the day';
        }

        if ($this->retail_price && $this->retail_price >= 3_000_000) {
            $cons[] = 'Sits at a premium price point';
        }

        $seasonNames = $this->seasons->pluck('name');
        if (! $seasonNames->contains('Summer') && ! $seasonNames->contains('All Season')) {
            $cons[] = 'Not the first choice for hot, humid weather';
        }

        if ($this->brand?->type === 'designer' && $this->is_trending) {
            $cons[] = "Popularity means you'll likely smell it on others too";
        }

        if ($this->brand?->type === 'niche') {
            $cons[] = 'Limited availability compared to mainstream designer releases';
        }

        if (($this->seasons->count() + $this->occasions->count()) <= 3) {
            $cons[] = 'Best suited to a narrower set of occasions';
        }

        if (empty($cons)) {
            $cons[] = 'A fairly safe, crowd-pleasing scent with few standout drawbacks';
        }

        return array_slice(array_values(array_unique($cons)), 0, 5);
    }
}