<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Applies the note photographs gathered by scripts/fetch_note_photos.py.
 *
 * The script writes storage/app/note-photo-manifest.json rather than touching
 * the database itself, so the images can be reviewed before anything goes
 * live, and so this step stays repeatable.
 *
 * Only notes whose image file actually landed on disk are updated; the rest
 * keep their generated placeholder.
 */
return new class extends Migration
{
    private const MANIFEST = 'note-photo-manifest.json';

    public function up(): void
    {
        $path = storage_path('app/'.self::MANIFEST);

        if (! file_exists($path)) {
            return;
        }

        $manifest = json_decode(file_get_contents($path), true) ?: [];

        foreach ($manifest as $slug => $entry) {
            if (! file_exists(public_path($entry['image'] ?? ''))) {
                continue;
            }

            DB::table('notes')->where('slug', $slug)->update([
                'image' => $entry['image'],
                'image_credit' => $entry['credit'] ?: null,
                'image_license' => $entry['license'] ?: null,
                'image_source' => $entry['source'] ?: null,
            ]);
        }
    }

    public function down(): void
    {
        // Clear only the attribution; the image paths were already the
        // expected slug-based names before this ran.
        DB::table('notes')->whereNotNull('image_license')->update([
            'image_credit' => null,
            'image_license' => null,
            'image_source' => null,
        ]);
    }
};
