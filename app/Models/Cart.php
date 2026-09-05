<?php

namespace App\Models;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'session_id',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */

    /**
     * Get the organization that owns this cart.
     * Every cart must belong to an organization.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user that owns this cart.
     * Nullable for guest carts.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Get all items in this cart.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Accessors
     */

    /**
     * Get the total number of items in the cart.
     */
    public function getTotalItemsCountAttribute(): int
    {
        return $this->cartItems()->sum('quantity');
    }

    /**
     * Business Logic Methods
     */

    /**
     * Add or update an item in the cart.
     *
     * @param string $productId The UUID of the product
     * @param int $quantity The quantity to add
     * @return CartItem
     */
    public function addItem(string $productId, int $quantity): CartItem
    {
        // Check if product already exists in cart
        $existingItem = $this->cartItems()
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            // Update quantity if item already exists
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
            ]);

            return $existingItem;
        }

        // Create new cart item
        return $this->cartItems()->create([
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Remove an item from the cart.
     *
     * @param string $cartItemId The UUID of the cart item
     * @return bool
     */
    public function removeItem(string $cartItemId): bool
    {
        return (bool) $this->cartItems()
            ->where('id', $cartItemId)
            ->delete();
    }

    /**
     * Clear all items from the cart.
     *
     * @return bool
     */
    public function clear(): bool
    {
        return (bool) $this->cartItems()->delete();
    }
}
