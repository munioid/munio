<?php

use App\Enums\Membership\PackageValidityTypeEnum;
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
        Schema::create('membership_packages', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignIdFor(Organization::class);
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('validity_type')->default(PackageValidityTypeEnum::LIFETIME);
            $table->integer('validity_amount')->nullable();
            $table->date('validity_end_at')->nullable();
            $table->boolean('is_active');
            $table->boolean('is_auto_numbering');
            $table->string('format')->nullable();
            $table->timestamps();
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
