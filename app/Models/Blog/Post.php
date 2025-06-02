<?php

namespace App\Models\Blog;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use Multitenantable;

    protected $table = 'blog_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'source',
        'is_published',
        'published_at'
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
        return $this->belongsToMany(Tag::class, table: 'blog_tag_post');
    }
}
