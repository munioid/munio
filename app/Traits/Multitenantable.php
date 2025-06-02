<?php

namespace App\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Multitenantable
{
    protected static function bootMultitenantable(): void
    {
        static::creating(function ($model) {
            if (Filament::getTenant()) {
                $model->organization()->associate(Filament::getTenant());
            }
        });

        static::addGlobalScope('organization', function (Builder $query) {
            if (Filament::getTenant()) {
                $query->whereBelongsTo(Filament::getTenant());
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
