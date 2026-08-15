<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_product_tags', function (Blueprint $table) {
            $table->foreignUuid('product_id')
                ->constrained('store_products')
                ->cascadeOnDelete();
            $table->foreignUuid('tag_id')
                ->constrained('store_tags')
                ->cascadeOnDelete();

            // Composite primary key, no timestamps: the pair itself is the row.
            $table->primary(['product_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_product_tags');
    }
};
