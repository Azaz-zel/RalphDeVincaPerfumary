<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Applies the second photo pass and gives every remaining perfume its house's
 * picture.
 *
 * Three parts:
 *   1. Notes and houses that the first, stricter pass left empty.
 *   2. A distinct About image per note, so that section stops repeating the
 *      hero photograph.
 *   3. Perfumes with no freely-licensed photograph of their own bottle fall
 *      back to their fragrance house's image. Almost no niche fragrance has a
 *      free photo of the actual bottle, and the alternative was leaving most
 *      of the catalogue on placeholders.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->applyManifest('brand-fill-manifest.json', 'brands', 'hero_image');
        $this->applyManifest('note-fill-manifest.json', 'notes', 'image');
        $this->applyAboutImages();
        $this->fallBackToBrandPhotos();
    }

    public function down(): void
    {
        // Only the borrowed house pictures are reversible in a meaningful way.
        DB::table('perfumes')->where('image_is_brand_fallback', true)->update([
            'image' => DB::raw("CONCAT('images/perfumes/', slug, '.jpg')"),
            'image_credit' => null,
            'image_license' => null,
            'image_source' => null,
            'image_is_brand_fallback' => false,
        ]);
    }

    private function applyManifest(string $file, string $table, string $column): void
    {
        foreach ($this->read($file) as $slug => $entry) {
            if (! file_exists(public_path($entry['image'] ?? ''))) {
                continue;
            }

            DB::table($table)->where('slug', $slug)->update([
                $column => $entry['image'],
                'image_credit' => $entry['credit'] ?: null,
                'image_license' => $entry['license'] ?: null,
                'image_source' => $entry['source'] ?: null,
            ]);
        }
    }

    /**
     * The About section only needs the path; its credit rides along with the
     * hero image already shown on the same page.
     */
    private function applyAboutImages(): void
    {
        foreach ($this->read('note-about-manifest.json') as $slug => $entry) {
            if (! file_exists(public_path($entry['image'] ?? ''))) {
                continue;
            }

            DB::table('notes')->where('slug', $slug)->update([
                'about_image' => $entry['image'],
            ]);
        }
    }

    private function fallBackToBrandPhotos(): void
    {
        $brands = DB::table('brands')
            ->whereNotNull('hero_image')
            ->get(['id', 'hero_image', 'image_credit', 'image_license', 'image_source'])
            ->keyBy('id');

        foreach (DB::table('perfumes')->get(['id', 'brand_id', 'image']) as $perfume) {
            // Leave anything that already has a picture of its own alone.
            if ($perfume->image && file_exists(public_path($perfume->image))) {
                continue;
            }

            $brand = $brands->get($perfume->brand_id);

            if (! $brand || ! file_exists(public_path($brand->hero_image))) {
                continue;
            }

            DB::table('perfumes')->where('id', $perfume->id)->update([
                'image' => $brand->hero_image,
                'image_credit' => $brand->image_credit,
                'image_license' => $brand->image_license,
                'image_source' => $brand->image_source,
                'image_is_brand_fallback' => true,
            ]);
        }
    }

    private function read(string $file): array
    {
        $path = storage_path('app/'.$file);

        if (! file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?: [];
    }
};
