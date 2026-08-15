<?php

use App\Models\Organization\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        Schema::create('store_products', function (Blueprint $table) use ($driver) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignUuid('category_id')
                ->nullable()
                ->constrained('store_categories')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2)->unsigned();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('stock_status')->default('in_stock');
            $table->decimal('weight', 10, 3)->unsigned()->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            if ($driver === 'pgsql') {
                DB::statement(
                    'create unique index store_products_organization_id_slug_unique
                     on store_products (organization_id, slug) where deleted_at is null'
                );
            } else {
                $table->unique(['organization_id', 'slug']);
            }

            $table->index(['organization_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
