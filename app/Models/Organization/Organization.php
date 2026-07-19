<?php

namespace App\Models\Organization;

use App\Models\File;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Storage;

class Organization extends Model implements HasAvatar
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'subdomain',
        'domain'
    ];

    ### Relationships ###
    public function icon(): MorphOne
    {
        return $this->morphOne(File::class, 'attachment')
            ->where('field', 'icon');
    }

    public function favicon(): MorphOne
    {
        return $this->morphOne(File::class, 'attachment')
            ->where('field', 'favicon');
    }

    ### Filament Tenancy ###
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->favicon?->getPath();
    }
}
