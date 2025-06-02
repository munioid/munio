<?php

namespace App\Models\Membership;

use App\Traits\Multitenantable;
use App\Enums\MemberAttributeTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    use Multitenantable;

    protected $table = 'membership_attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'fieldname',
        'label',
        'type',
        'options',
        'notes',
        'is_private',
        'is_required'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MemberAttributeTypeEnum::class,
            'options' => 'json',
            'is_private' => 'boolean',
            'is_required' => 'boolean',
        ];
    }

    /**
     * Relationships
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, table: 'membership_member_attribute')
            ->withPivot('value');
    }

    /**
     * Attributes
     */
    public function getPivotValueAttribute()
    {
        if ($this->type == MemberAttributeTypeEnum::Dropdown) {
            return collect($this->options)->pluck('value', 'code')->toArray()[$this->value];
        } else {
            return $this->value;
        }
    }
}
