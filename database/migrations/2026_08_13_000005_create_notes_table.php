<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->string('aroma', 255)->nullable();
            $table->string('fragrance_family', 100)->nullable();
            $table->string('origin', 150)->nullable();
            $table->string('extraction', 150)->nullable();
            $table->enum('common_role', ['Top Note', 'Middle Note', 'Base Note'])->nullable();
            $table->enum('ingredient_type', ['Natural', 'Synthetic'])->nullable();
            $table->enum('longevity', ['Short', 'Moderate', 'Long', 'Very Long'])->nullable();
            $table->enum('intensity', ['Light', 'Moderate', 'Strong'])->nullable();
            $table->string('best_paired_with', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('about_image', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
