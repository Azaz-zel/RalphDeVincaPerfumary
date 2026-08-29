<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * All image paths were stored relative to public/images (e.g. "perfumes/sauvage.jpg")
 * but every view resolves them with asset(), which is relative to public/ itself.
 * That made every perfume and note image on the site 404. This backfills the
 * missing "images/" prefix so asset($perfume->image) resolves correctly.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('perfumes')
            ->whereNotNull('image')
            ->where('image', 'not like', 'images/%')
            ->update(['image' => DB::raw("CONCAT('images/', image)")]);

        DB::table('notes')
            ->whereNotNull('image')
            ->where('image', 'not like', 'images/%')
            ->update(['image' => DB::raw("CONCAT('images/', image)")]);

        DB::table('notes')
            ->whereNotNull('about_image')
            ->where('about_image', 'not like', 'images/%')
            ->update(['about_image' => DB::raw("CONCAT('images/', about_image)")]);
    }

    public function down(): void
    {
        DB::table('perfumes')
            ->where('image', 'like', 'images/%')
            ->update(['image' => DB::raw('SUBSTRING(image, 8)')]);

        DB::table('notes')
            ->where('image', 'like', 'images/%')
            ->update(['image' => DB::raw('SUBSTRING(image, 8)')]);

        DB::table('notes')
            ->where('about_image', 'like', 'images/%')
            ->update(['about_image' => DB::raw('SUBSTRING(about_image, 8)')]);
    }
};
