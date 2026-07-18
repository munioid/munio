<?php

namespace App\Models\Event;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'organization_id',
    'event_id',
    'name',
    'code',
    'price',
    'stocks',
    'booked'
)]
class Package extends Model
{
    use HasUuids, Multitenantable;

    protected $table = 'event_packages';
}
