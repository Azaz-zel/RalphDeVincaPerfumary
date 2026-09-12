<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Brand and perfume photographs come from Wikimedia Commons under CC licences
 * that require crediting the author, exactly as the note images do.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('image_credit', 200)->nullable();
            $table->string('image_license', 60)->nullable();
            $table->string('image_source', 255)->nullable();
        });

        Schema::table('perfumes', function (Blueprint $table) {
            $table->string('image_credit', 200)->nullable();
            $table->string('image_license', 60)->nullable();
            $table->string('image_source', 255)->nullable();
        });
    }

    public function down(): void
    {
        $columns = ['image_credit', 'image_license', 'image_source'];

        Schema::table('brands', fn (Blueprint $table) => $table->dropColumn($columns));
        Schema::table('perfumes', fn (Blueprint $table) => $table->dropColumn($columns));
    }
};
