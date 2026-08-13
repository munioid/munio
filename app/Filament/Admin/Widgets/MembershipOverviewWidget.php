<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\MemberStatusEnum;
use App\Models\Membership\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MembershipOverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Membership';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $memberTotal = Member::query()
            ->where('status', MemberStatusEnum::ACTIVE->value)
            ->count();

        return [
            Stat::make('Total Member', $memberTotal)
                ->color('success'),
        ];
    }
}
