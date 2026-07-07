<?php

namespace App\Models\Membership;

use App\Models\User;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use Multitenantable;

    protected $table = 'membership_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'number',
        'name',
        'email',
        'phone',
        'address',
        'status',
        'status_updated_at'
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}