<?php

namespace App\Models\Blog;

use App\Traits\Multitenantable;
use App\Traits\Searchable;
use Database\Factories\Blog\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(CategoryFactory::class)]
class Category extends Model
{
    use HasFactory, HasUuids, Multitenantable, Searchable;

    protected $table = 'blog_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'description',
        'parent_id',
    ];

    /**
     * The attrubutes that are searchable.
     */
    protected array $searchable = [
        'name',
    ];

    /**
     * Relationships
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
