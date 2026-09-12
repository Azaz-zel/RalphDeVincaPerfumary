<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Applies the brand and perfume photographs gathered by
 * scripts/fetch_catalog_photos.py.
 *
 * As with the note manifest, the fetcher writes JSON rather than touching the
 * database, so the images can be reviewed first and the step stays repeatable.
 */
return new class extends Migration
{
    /** manifest file => [table, image column] */
    private const SOURCES = [
        'brand-photo-manifest.json' => ['brands', 'hero_image'],
        'perfume-photo-manifest.json' => ['perfumes', 'image'],
    ];

    public function up(): void
    {
        foreach (self::SOURCES as $file => [$table, $imageColumn]) {
            $path = storage_path('app/'.$file);

            if (! file_exists($path)) {
                continue;
            }

            $manifest = json_decode(file_get_contents($path), true) ?: [];

            foreach ($manifest as $slug => $entry) {
                if (! file_exists(public_path($entry['image'] ?? ''))) {
                    continue;
                }

                DB::table($table)->where('slug', $slug)->update([
                    $imageColumn => $entry['image'],
                    'image_credit' => $entry['credit'] ?: null,
                    'image_license' => $entry['license'] ?: null,
                    'image_source' => $entry['source'] ?: null,
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach (self::SOURCES as [$table]) {
            DB::table($table)->whereNotNull('image_license')->update([
                'image_credit' => null,
                'image_license' => null,
                'image_source' => null,
            ]);
        }
    }
};
