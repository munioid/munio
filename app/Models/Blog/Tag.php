<?php

namespace App\Models\Blog;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Multitenantable;

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
