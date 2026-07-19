<?php

use App\Enums\ReservationStatusEnum;
use App\Models\Event\Event;
use App\Models\Event\Package;
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
        Schema::create('event_reservations', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class);
            $table->foreignIdFor(Event::class);
            $table->foreignIdFor(Package::class)->nullable();
            $table->string('code');
            $table->string('name');
            $table->string('email');
            $table->decimal('price', 15, 2)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total', 15, 2)->nullable();
            $table->string('status')->default(ReservationStatusEnum::PENDING);
            $table->foreignIdFor(User::class)->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'event_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
