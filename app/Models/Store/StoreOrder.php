<?php

namespace App\Models\Store;

use App\Enums\StoreOrderStatusEnum;
use App\Models\NumberSequence;
use App\Models\User;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class StoreOrder extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'store_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'notes',
    ];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $order) {
            if (! $order->order_number) {
                $order->order_number = static::generateOrderNumber();
            }

            if (! $order->status) {
                $order->status = StoreOrderStatusEnum::PENDING;
            }
        });

        static::created(function (self $order) {
            // Seed the audit trail (OD-Order-2): every order gets an initial
            // history row, status_from left null since there was no prior status.
            $order->statusHistories()->create([
                'status_from' => null,
                'status_to' => $order->status,
            ]);
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StoreOrderStatusEnum::class,
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StoreOrderItem::class, 'order_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StoreOrderStatusHistory::class, 'order_id');
    }

    /**
     * Functions
     */

    /**
     * Transition the order to a new status and record the change in the
     * immutable status history trail (OD-Order-2).
     */
    public function changeStatus(StoreOrderStatusEnum $status, ?User $changedBy = null): StoreOrderStatusHistory
    {
        $history = $this->statusHistories()->create([
            'status_from' => $this->status,
            'status_to' => $status,
            'changed_by' => $changedBy?->id,
        ]);

        $this->update(['status' => $status]);

        return $history;
    }

    /**
     * Generate a unique, per-organization order number (not a uuid).
     */
    public static function generateOrderNumber(): string
    {
        $sequence = DB::transaction(function () {
            $sequence = NumberSequence::lockForUpdate()->firstOrCreate(
                ['prefix' => 'store_order'],
                ['index' => 0]
            );

            $sequence->increment('index');

            return $sequence;
        });

        return 'ORD-'.str_pad((string) $sequence->index, 6, '0', STR_PAD_LEFT);
    }
}
