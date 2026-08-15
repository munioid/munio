<?php

namespace App\Models\Store;

use App\Enums\StoreOrderStatusEnum;
use App\Models\User;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreOrderStatusHistory extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'store_order_status_histories';

    /**
     * Immutable audit row — created_at only, no updated_at (OD-Order-2).
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'status_from',
        'status_to',
        'changed_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_from' => StoreOrderStatusEnum::class,
            'status_to' => StoreOrderStatusEnum::class,
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(StoreOrder::class, 'order_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
