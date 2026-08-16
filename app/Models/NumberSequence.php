<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'prefix',
    'index'
)]
class NumberSequence extends Model
{
    use HasUuids, Multitenantable;
}
