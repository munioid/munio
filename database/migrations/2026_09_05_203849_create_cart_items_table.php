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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('cart_id')
                ->constrained('carts')
                ->cascadeOnDelete();
            $table->foreignUuid('product_id')
                ->constrained('store_products')
                ->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->timestamps();

            // Single indexes on foreign keys
            $table->index('cart_id');
            $table->index('product_id');

            // Composite index for queries filtering by cart and sorting by date
            $table->index(['cart_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
