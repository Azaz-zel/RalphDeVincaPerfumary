<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The previous data-correction migration skipped updating `perfumer` when
 * research found no publicly documented perfumer (to avoid overwriting with
 * an empty string), but that also meant it silently left the OLD fabricated
 * placeholder perfumer name in place instead of clearing it. This nulls out
 * `perfumer` for exactly the fragrances research could not attribute.
 */
return new class extends Migration
{
    private array $slugs = [
        '40-knots', 'alexandria-ii', 'cruz-del-sur-ii', 'dama-bianca', 'golden-green',
        'italica', 'mefisto-gentiluomo', 'naxos', 'renaissance', 'torino-21', 'torino-22', 'uden',
        'aventus-cologne', 'carmina', 'viking-cologne', 'wind-flowers',
        'mojave-ghost', 'tam-dao-eau-de-parfum',
        '1872-masculine', 'cypress-clive-christian', 'matsukita', 'no-1-feminine', 'no-1-masculine', 'rock-rose',
        'le-beau-eau-de-toilette', 'le-beau-le-parfum', 'le-male-elixir', 'scandal-le-parfum', 'scandal-pour-homme', 'so-scandal',
    ];

    public function up(): void
    {
        DB::table('perfumes')->whereIn('slug', $this->slugs)->update(['perfumer' => null]);
    }

    public function down(): void
    {
        // Not reversible; the prior values were fabricated placeholders.
    }
};
