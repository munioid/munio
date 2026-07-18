<?php

use App\Models\Event\Event;
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
        Schema::create('event_packages', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class);
            $table->foreignIdFor(Event::class);
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->unsignedInteger('stocks')->default(0);
            $table->unsignedInteger('booked')->default(0);
            $table->timestamps();

            $table->unique(['organization_id', 'event_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
