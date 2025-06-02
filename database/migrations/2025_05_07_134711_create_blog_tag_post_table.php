<?php

use App\Models\Blog\Post;
use App\Models\Blog\Tag;
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
        Schema::create('blog_tag_post', function (Blueprint $table) {
            $table->foreignIdFor(Post::class);
            $table->foreignIdFor(Tag::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_tag_post');
    }
};
