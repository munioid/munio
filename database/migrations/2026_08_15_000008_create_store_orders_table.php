<?php

use App\Models\Organization\Organization;
use App\Models\User;
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
        Schema::create('store_orders', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->restrictOnDelete();
            // Customer snapshot at time of order — editable independently of
            // the linked user record (e.g. shipping contact differs from account).
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('order_number');
            $table->string('status')->default('pending');
            $table->decimal('subtotal', 12, 2)->unsigned();
            $table->decimal('shipping_cost', 12, 2)->unsigned()->default(0);
            $table->decimal('total', 12, 2)->unsigned();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'order_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_orders');
    }
};
