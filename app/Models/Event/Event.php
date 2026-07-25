<?php

namespace App\Models\Event;

use App\Enums\PricingTypeEnum;
use App\Models\File;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable(
    'organization_id',
    'title',
    'slug',
    'content',
    'excerpt',
    'start_at',
    'end_at',
    'published',
    'published_at',
    'pricing_type',
    'price',
    'stocks',
    'external_link'
)]
class Event extends Model
{
    use HasUuids, Multitenantable, HasFactory;

    protected $table = 'event_events';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pricing_type' => PricingTypeEnum::class,
            'start_at' => 'date',
            'end_at' => 'date',
            'published' => 'boolean',
            'published_at' => 'datetime'
        ];
    }

    ### Relationships ###
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cover(): MorphOne
    {
        return $this->morphOne(File::class, 'attachment')
            ->where('field', 'cover');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}
