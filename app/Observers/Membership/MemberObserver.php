<?php

namespace App\Observers\Membership;

use App\Enums\MemberStatusEnum;
use App\Models\Membership\Member;

class MemberObserver
{
    /**
     * Handle the Member "saved" event.
     */
    public function saved(Member $member): void
    {
        if ($member->status == MemberStatusEnum::ACTIVE and !$member->number and $member->attributes()->count()) {
            $member->generateNumber();
        }
    }
}
