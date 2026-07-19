<?php

namespace App\Models\Membership;

use App\Enums\MemberAttributeTypeEnum;
use App\Traits\Multitenantable;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    use Multitenantable, HasUuids;

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
        return $this->belongsToMany(Member::class, table: 'membership_members_attributes_pivot')
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

    ### Scopes ###
    public function scopeNotPrivate(Builder $query)
    {
        $query->where('is_private', false);
    }

    ### Components ###
    public function toFormComponent(): Field
    {
        $field = match ($this->type) {
            MemberAttributeTypeEnum::Dropdown => Select::make("attributes.{$this->fieldname}")
                ->options(collect($this->options)->pluck('value', 'code')->toArray())
                ->native(false),
            default => TextInput::make("attributes.{$this->fieldname}")
        };
        $field->label($this->label)
            ->required($this->is_required)
            ->afterStateHydrated(function ($component, $record) {
                if (! $record) {
                    return;
                }

                $attribute = $record->attributes()
                    ->firstWhere('fieldname', $this->fieldname);

                $component->state($attribute?->pivot?->value);
            });

        return $field;
    }
}
