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
        Schema::create('membership_member_attribute', function (Blueprint $table) {
            $table->foreignIdFor(Member::class);
            $table->foreignIdFor(Attribute::class);
            $table->text('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_member_attribute');
    }
};
