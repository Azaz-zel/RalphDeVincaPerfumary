<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteCharacteristic extends Model
{
    protected $table = 'note_characteristics';

    protected $fillable = [
        'note_id',
        'floral',
        'woody',
        'warm',
        'sweet',
        'bright',
        'fresh',
    ];

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The top N characteristics by relative value, so "Overall Character"
     * always has something to show instead of relying on a fixed 70%
     * threshold that most notes never reach (no dimension dominates them).
     */
    public function dominantTraits(int $limit = 2): array
    {
        $traits = [
            'Floral' => $this->floral,
            'Woody' => $this->woody,
            'Warm' => $this->warm,
            'Sweet' => $this->sweet,
            'Bright' => $this->bright,
            'Fresh' => $this->fresh,
        ];

        arsort($traits);

        return collect($traits)
            ->filter(fn ($value) => $value > 0)
            ->take($limit)
            ->keys()
            ->all();
    }
}