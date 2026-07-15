<?php

namespace App\Models\Event;

use App\Models\File;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable(
    'organization_id',
    'title',
    'slug',
    'content',
    'excerpt',
    'start_at',
    'end_at',
    'is_published',
    'published_at'
)]
class Event extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'event_events';

    ### Relationships ###
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function covers(): MorphMany
    {
        return $this->morphMany(File::class, 'attachment')
            ->where('module', 'covers');
    }
}
