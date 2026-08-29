<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_characteristics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->unique()->constrained('notes')->cascadeOnDelete();
            $table->unsignedTinyInteger('floral')->default(0)->comment('Persentase karakter Floral (0-100)');
            $table->unsignedTinyInteger('woody')->default(0)->comment('Persentase karakter Woody (0-100)');
            $table->unsignedTinyInteger('warm')->default(0)->comment('Persentase karakter Warm (0-100)');
            $table->unsignedTinyInteger('sweet')->default(0)->comment('Persentase karakter Sweet (0-100)');
            $table->unsignedTinyInteger('bright')->default(0)->comment('Persentase karakter Bright (0-100)');
            $table->unsignedTinyInteger('fresh')->default(0)->comment('Persentase karakter Fresh (0-100)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_characteristics');
    }
};
