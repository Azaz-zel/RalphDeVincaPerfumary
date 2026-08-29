<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->foreignId('fragrance_family_id')->constrained('fragrance_families')->restrictOnDelete();
            $table->string('name', 180);
            $table->string('slug', 220)->unique();
            $table->enum('gender', ['men', 'women', 'unisex']);
            $table->string('concentration', 80);
            $table->unsignedSmallInteger('release_year')->nullable();
            $table->string('perfumer', 255)->nullable();
            $table->text('description');
            $table->decimal('rating', 3, 2)->nullable();
            $table->unsignedInteger('rating_count')->default(0);
            $table->boolean('is_trending')->default(false);
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfumes');
    }
};
