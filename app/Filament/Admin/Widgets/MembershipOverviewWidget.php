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
        $counts = Member::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            Stat::make('Total Member', $counts->sum())
                ->color('primary'),
            Stat::make(MemberStatusEnum::ACTIVE->getLabel(), $counts[MemberStatusEnum::ACTIVE->value] ?? 0)
                ->color('success'),
            Stat::make(MemberStatusEnum::PENDING->getLabel(), $counts[MemberStatusEnum::PENDING->value] ?? 0)
                ->color('warning'),
            Stat::make(MemberStatusEnum::INACTIVE->getLabel(), $counts[MemberStatusEnum::INACTIVE->value] ?? 0)
                ->color('gray'),
            Stat::make(MemberStatusEnum::SUSPENDED->getLabel(), $counts[MemberStatusEnum::SUSPENDED->value] ?? 0)
                ->color('danger'),
        ];
    }
}
