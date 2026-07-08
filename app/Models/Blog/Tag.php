<?php

namespace App\Models\Blog;

use App\Traits\Multitenantable;
use Database\Factories\Blog\TagFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(TagFactory::class)]
class Tag extends Model
{
    use Multitenantable, HasUuids, HasFactory;

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
        'description'
    ];
}
