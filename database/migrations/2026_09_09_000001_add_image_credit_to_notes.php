<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Note photographs sourced from Wikimedia Commons carry CC licences that
 * require crediting the author, so the attribution travels with the image
 * rather than living in a separate list that could drift out of sync.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->string('image_credit', 200)->nullable()->after('about_image');
            $table->string('image_license', 60)->nullable()->after('image_credit');
            $table->string('image_source', 255)->nullable()->after('image_license');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['image_credit', 'image_license', 'image_source']);
        });
    }
};
