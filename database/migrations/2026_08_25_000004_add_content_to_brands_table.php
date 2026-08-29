<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * /brand always rendered a single hardcoded "Dior" page regardless of which
 * perfume's brand link was clicked. These columns let the brand page render
 * real, brand-specific editorial content instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('tagline', 255)->nullable()->after('type');
            $table->string('founded_year', 20)->nullable()->after('tagline');
            $table->string('founded_location', 150)->nullable()->after('founded_year');
            $table->string('founder', 150)->nullable()->after('founded_location');
            $table->text('about')->nullable()->after('founder');
            $table->string('hero_image', 255)->nullable()->after('about');
            $table->string('about_image', 255)->nullable()->after('hero_image');
            $table->string('philosophy_quote', 255)->nullable()->after('about_image');
            $table->text('philosophy_intro')->nullable()->after('philosophy_quote');
            $table->json('philosophy_pillars')->nullable()->after('philosophy_intro');
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn([
                'tagline', 'founded_year', 'founded_location', 'founder',
                'about', 'hero_image', 'about_image',
                'philosophy_quote', 'philosophy_intro', 'philosophy_pillars',
            ]);
        });
    }
};
