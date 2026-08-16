<?php

namespace App\Models\Event;

use App\Enums\ReservationStatusEnum;
use App\Models\User;
use App\Observers\Event\ReservationObserver;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'organization_id',
    'event_id',
    'package_id',
    'code',
    'name',
    'email',
    'price',
    'quantity',
    'total',
    'status',
    'user_id'
)]
#[ObservedBy(ReservationObserver::class)]
class Reservation extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'event_reservations';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ReservationStatusEnum::class,
        ];
    }

    // ## Relationships ###
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
