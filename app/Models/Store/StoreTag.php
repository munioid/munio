<?php

namespace App\Models\Store;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StoreTag extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'store_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    /**
     * Relationships
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            StoreProduct::class,
            'store_product_tags',
            'tag_id',
            'product_id'
        );
    }
}
