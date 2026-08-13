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

        $statusStats = collect(MemberStatusEnum::cases())->map(
            fn (MemberStatusEnum $status) => Stat::make($status->getLabel(), $counts[$status->value] ?? 0)
                ->color($status->getColor())
        );

        return [
            Stat::make('Total Member', $counts->sum())
                ->color('primary'),
            ...$statusStats,
        ];
    }
}
