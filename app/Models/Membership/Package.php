<?php

namespace App\Models\Membership;

use App\Enums\Membership\PackageValidityTypeEnum;
use App\Traits\HasAttachments;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'organization_id',
    'name',
    'code',
    'description',
    'information',
    'price',
    'validity_type',
    'validity_amount',
    'validity_end_at',
    'is_active',
    'is_auto_numbering',
    'format'
)]
class Package extends Model
{
    use Multitenantable, HasUuids, HasAttachments;

    protected $table = 'membership_packages';

    protected static $attachOne = [
        'cover',
        'vcard_background'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'validity_type' => PackageValidityTypeEnum::class
        ];
    }
}
