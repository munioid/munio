<?php

namespace App\Models\Blog;

use App\Models\File;
use App\Traits\Multitenantable;
use App\Traits\Searchable;
use Database\Factories\Blog\PostFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[UseFactory(PostFactory::class)]
class Post extends Model
{
    use Multitenantable, HasUuids, HasFactory, Searchable;

    protected $table = 'blog_posts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'organization_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'source',
        'is_published',
        'published_by',
        'published_at'
    ];

    /**
     * The attrubutes that are searchable.
     */
    protected array $searchable = [
        'title',
        'category.name',
        'tags.name'
    ];

    /**
     * Relationships
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, table: 'blog_tags_posts_pivot');
    }

    public function covers(): MorphMany
    {
        return $this->morphMany(File::class, 'attachment')
            ->where('field', 'covers');
    }
}
