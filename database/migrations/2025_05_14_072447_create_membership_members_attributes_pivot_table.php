<?php

use App\Models\Membership\Attribute;
use App\Models\Membership\Member;
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
        Schema::create('membership_members_attributes_pivot', function (Blueprint $table) {
            $table->foreignIdFor(Member::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Attribute::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_members_attributes_pivot');
    }
};
