<?php

namespace App\Models\Blog;

use App\Traits\Multitenantable;
use App\Traits\Searchable;
use Database\Factories\Blog\TagFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(TagFactory::class)]
class Tag extends Model
{
    use HasFactory, HasUuids, Multitenantable, Searchable;

    protected $table = 'blog_tags';

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
    ];

    /**
     * The attrubutes that are searchable.
     */
    protected array $searchable = [
        'name',
    ];
}
