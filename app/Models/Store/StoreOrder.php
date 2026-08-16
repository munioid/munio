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
use Illuminate\Support\Facades\Auth;
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
        'name',
        'email',
        'phone',
        'order_number',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'notes',
    ];

    /**
     * Explicit "changed by" override for the next status-history row this
     * request writes. Set by changeStatus() so callers (e.g. the seeder) can
     * attribute a transition to a specific user without an authenticated web
     * session. Falls back to the current Filament/web user when left unset.
     */
    protected ?string $pendingStatusChangeActorId = null;

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
            $order->recordStatusHistory(null, $order->status);
        });

        // OD-Order-3: admin can freely change `status` (no state machine), but
        // every change — whether via changeStatus(), the Filament edit form,
        // or a plain ->update(['status' => ...]) — must be logged automatically.
        static::updated(function (self $order) {
            $changes = $order->getChanges();

            if (! array_key_exists('status', $changes)) {
                return;
            }

            $order->recordStatusHistory($order->getOriginal('status'), $order->status);
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
     * Transition the order to a new status. The immutable status history
     * trail (OD-Order-2) is written automatically by the `updated` hook in
     * boot() — this only needs to attribute the change to $changedBy before
     * saving, for callers (e.g. the seeder) without an authenticated web user.
     */
    public function changeStatus(StoreOrderStatusEnum $status, ?User $changedBy = null): StoreOrderStatusHistory
    {
        $this->pendingStatusChangeActorId = $changedBy?->id;

        $this->update(['status' => $status]);

        return $this->statusHistories()->latest('created_at')->latest('id')->firstOrFail();
    }

    /**
     * Write a single row to the status history audit trail, attributed to the
     * explicit actor set via changeStatus() or, failing that, the current
     * authenticated (Filament) user.
     */
    protected function recordStatusHistory(StoreOrderStatusEnum|string|null $from, StoreOrderStatusEnum|string|null $to): StoreOrderStatusHistory
    {
        $history = $this->statusHistories()->create([
            'status_from' => $from,
            'status_to' => $to,
            'changed_by' => $this->pendingStatusChangeActorId ?? Auth::id(),
        ]);

        $this->pendingStatusChangeActorId = null;

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
