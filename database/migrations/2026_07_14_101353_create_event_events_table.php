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
        Schema::create('event_events', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class)->nullable();
            $table->string('title');
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('start_at')->nullable();
            $table->string('end_at')->nullable();
            $table->foreignIdFor(Category::class)->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_events');
    }
};
