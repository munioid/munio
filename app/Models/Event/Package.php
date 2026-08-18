<?php

namespace App\Models\Event;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'organization_id',
    'event_id',
    'name',
    'code',
    'price',
    'stocks',
    'booked',
    'event_slug'
)]
class Package extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'event_packages';

    protected function casts(): array
    {
        return [
            // event_slug is a pseudo-column used only during import, not persisted
        ];
    }

    public function save(array $options = []): bool
    {
        // Remove the event_slug pseudo-column before saving
        $this->offsetUnset('event_slug');
        return parent::save($options);
    }

    // ## Relationships ###
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
