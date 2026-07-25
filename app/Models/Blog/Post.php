<?php

namespace App\Models\Blog;

use App\Traits\HasAttachments;
use App\Traits\Multitenantable;
use App\Traits\Searchable;
use Database\Factories\Blog\PostFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[UseFactory(PostFactory::class)]
class Post extends Model
{
    use Multitenantable, HasUuids, HasFactory, Searchable, HasAttachments;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    ### Attachments ###
    protected static $attachOne = [
        'cover'
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
}
