<?php

namespace App\Models\Event;

use App\Enums\PricingTypeEnum;
use App\Traits\HasAttachments;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    use HasUuids, Multitenantable, HasFactory, HasAttachments;

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

    ### Attachments ###
    protected static $attachOne = [
        'cover'
    ];

    ### Relationships ###
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    ### Scopes ###
    public function scopePublished(Builder $query)
    {
        $query->where('published', true);
    }

    ### Attributes ###
    protected function eventDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->start_at || ! $this->end_at) {
                    return null;
                }

                if ($this->start_at->isSameDay($this->end_at)) {
                    return $this->start_at->format('l, d F Y');
                }

                if ($this->start_at->format('F Y') === $this->end_at->format('F Y')) {
                    return sprintf(
                        '%s–%s %s',
                        $this->start_at->format('d'),
                        $this->end_at->format('d'),
                        $this->start_at->format('F Y'),
                    );
                }

                if ($this->start_at->year === $this->end_at->year) {
                    return sprintf(
                        '%s – %s',
                        $this->start_at->format('d M'),
                        $this->end_at->format('d M Y'),
                    );
                }

                return sprintf(
                    '%s – %s',
                    $this->start_at->format('d M Y'),
                    $this->end_at->format('d M Y'),
                );
            },
        );
    }
}
