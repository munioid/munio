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
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Single indexes on foreign keys
            $table->index('organization_id');
            $table->index('user_id');

            // Composite indexes for multi-tenant and guest queries
            $table->index(['organization_id', 'user_id']);
            $table->index(['organization_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
