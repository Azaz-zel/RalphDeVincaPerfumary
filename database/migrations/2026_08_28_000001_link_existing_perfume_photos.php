<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Points perfumes at the photos already sitting in public/images/perfumes.
 *
 * Those files were named by product nickname (e.g. "br540.jpg", "dior-sauvage.jpg")
 * while the seeded image column expected slug-style names ("baccarat-rouge-540.jpg",
 * "sauvage-edt.jpg"), so every one of them fell through to the generated
 * placeholder even though a real photo was sitting right there.
 *
 * Only mappings that were manually verified are included. Photos in that folder
 * for fragrances outside the catalogue (Eau Sauvage, Fahrenheit, LV Imagination,
 * Ombré, Prada Carbon, YSL Libre) are intentionally left unused.
 */
return new class extends Migration
{
    private array $map = [
        'acqua-di-gio-eau-de-toilette' => 'acqua-di-gio.jpg',
        'acqua-di-gio-profondo' => 'profondo.jpg',
        'alexandria-ii' => 'alexandria-ii.jpg',
        'aventus' => 'aventus.jpg',
        'baccarat-rouge-540' => 'br540.jpg',
        'bleu-de-chanel-eau-de-parfum' => 'bleu-de-chanel.jpg',
        'dior-homme-intense' => 'dior-homme.jpg',
        'sauvage-eau-de-toilette' => 'dior-sauvage.jpg',
        'terre-d-hermes-eau-de-toilette' => 'terre-hermes.jpg',
        'y-eau-de-parfum' => 'ysl-y.jpg',
    ];

    public function up(): void
    {
        foreach ($this->map as $slug => $filename) {
            $path = 'images/perfumes/'.$filename;

            // Skip silently if the file isn't on disk, so the migration stays
            // safe to run on a checkout without the image assets.
            if (! file_exists(public_path($path))) {
                continue;
            }

            DB::table('perfumes')->where('slug', $slug)->update(['image' => $path]);
        }
    }

    public function down(): void
    {
        // Restore the original slug-derived filenames.
        foreach (array_keys($this->map) as $slug) {
            DB::table('perfumes')
                ->where('slug', $slug)
                ->update(['image' => DB::raw("CONCAT('images/perfumes/', slug, '.jpg')")]);
        }
    }
};
