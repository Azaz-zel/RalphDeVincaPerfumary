<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfume_occasions', function (Blueprint $table) {
            $table->foreignId('perfume_id')->constrained('perfumes')->cascadeOnDelete();
            $table->foreignId('occasion_id')->constrained('occasions')->cascadeOnDelete();
            $table->primary(['perfume_id', 'occasion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfume_occasions');
    }
};
