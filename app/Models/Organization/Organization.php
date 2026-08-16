<?php

namespace App\Models\Organization;

use App\Traits\HasAttachments;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model implements HasAvatar
{
    use HasAttachments, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'subdomain',
        'domain',
        'colors',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'colors' => 'json',
        ];
    }

    // ## Attachments ###
    protected static $attachOne = [
        'icon',
        'favicon',
        'login_banner',
    ];

    // ## Filament Tenancy ###
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->favicon?->getPath();
    }
}
