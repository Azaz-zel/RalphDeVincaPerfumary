<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The perfume detail page (bottle information panel) reads these
     * columns, but they were never added to the perfumes table.
     */
    public function up(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->string('origin', 150)->nullable()->after('perfumer');
            $table->string('bottle_sizes', 100)->nullable()->after('image');
            $table->enum('status', ['Active', 'Limited Edition', 'Discontinued'])
                ->default('Active')
                ->after('bottle_sizes');
            $table->enum('availability', ['In Stock', 'Out of Stock', 'Pre-Order'])
                ->default('In Stock')
                ->after('status');
            $table->decimal('retail_price', 12, 2)->nullable()->after('availability');
        });
    }

    public function down(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->dropColumn(['origin', 'bottle_sizes', 'status', 'availability', 'retail_price']);
        });
    }
};
