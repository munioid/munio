<?php

namespace App\Models\Store;

use App\Enums\StoreProductStockStatusEnum;
use App\Traits\HasAttachments;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{
    use HasAttachments, HasUuids, Multitenantable, SoftDeletes;

    protected $table = 'store_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'stock_status',
        'weight',
        'is_active',
        'sort_order',
    ];

    /**
     * Attachments
     */
    protected static $attachOne = [
        'cover',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stock_status' => StoreProductStockStatusEnum::class,
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'weight' => 'decimal:3',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Relationships
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(StoreCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            StoreTag::class,
            'store_product_tags',
            'product_id',
            'tag_id'
        );
    }

    /**
     * Accessors
     */
    protected function coverUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cover?->getPath(),
        );
    }
}
