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
        $isMySql = in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);

        Schema::create('store_products', function (Blueprint $table) use ($isMySql) {
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

            // Unique slug per organization, scoped to non-deleted rows only.
            // MySQL has no partial indexes, so the predicate is folded into a
            // stored generated column that is NULL once the row is soft deleted
            // (NULLs are not compared by a unique index). Other drivers get a
            // real partial index below.
            if ($isMySql) {
                $table->string('active_slug')
                    ->nullable()
                    ->storedAs('(case when `deleted_at` is null then `slug` end)');

                $table->unique(['organization_id', 'active_slug']);
            }

            $table->index(['organization_id', 'is_active']);
        });

        if (! $isMySql) {
            DB::statement(
                'create unique index store_products_organization_id_slug_unique
                 on store_products (organization_id, slug) where deleted_at is null'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
