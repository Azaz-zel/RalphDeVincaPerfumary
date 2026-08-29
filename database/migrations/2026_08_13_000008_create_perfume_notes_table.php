<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfume_notes', function (Blueprint $table) {
            $table->foreignId('perfume_id')->constrained('perfumes')->cascadeOnDelete();
            $table->foreignId('note_id')->constrained('notes')->cascadeOnDelete();
            $table->enum('note_type', ['top', 'middle', 'base']);
            $table->primary(['perfume_id', 'note_id', 'note_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfume_notes');
    }
};
