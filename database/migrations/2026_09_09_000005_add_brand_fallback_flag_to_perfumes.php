<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Marks perfumes that are showing their fragrance house's picture because no
 * freely-licensed photograph of the bottle itself exists.
 *
 * The flag lets the page label the image honestly, so a visitor is not left
 * thinking a boutique photo is the product.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->boolean('image_is_brand_fallback')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->dropColumn('image_is_brand_fallback');
        });
    }
};
