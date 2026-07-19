<?php

namespace App\Models\Membership;

use App\Models\User;
use App\Observers\Membership\MemberObserver;
use App\Services\Membership\MemberNumberService;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(MemberObserver::class)]
class Member extends Model
{
    use Multitenantable, HasUuids;

    protected $table = 'membership_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'package_id',
        'number',
        'name',
        'email',
        'status',
        'status_updated_at',
        'user_id'
    ];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function (self $model) {
            if ($model->isDirty('status')) {
                $model->status_updated_at = now();
            }
        });
    }
    /**
     * Relationships
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, table: 'membership_members_attributes_pivot')
            ->withPivot('value');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    ### Functions ###
    public function generateNumber()
    {
        $data = $this->attributes()
            ->get()
            ->mapWithKeys(fn($attribute) => [
                $attribute->fieldname => $attribute->pivot->value,
            ])
            ->toArray();
        $this->number = MemberNumberService::generateNumber($this->package, $data);
        $this->saveQuietly();
    }
}
