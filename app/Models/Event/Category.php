<?php

namespace App\Models\Event;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'organization_id',
    'name',
    'slug',
    'description',
    'parent_id'
)]
class Category extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'event_categories';

    ### Attributes ###
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
