<?php

use App\Models\Event\Category;
use App\Models\Organization\Organization;
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
        Schema::create('event_categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class)->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->foreignIdFor(Category::class, 'parent_id')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_categories');
    }
};
