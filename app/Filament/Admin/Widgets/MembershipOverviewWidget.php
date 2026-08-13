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
        $months = collect(range(0, 11))->mapWithKeys(function ($i) {
            return [now()->subMonths(11 - $i)->format('Y-m') => 0];
        });

        $memberTotal = Member::query()
            ->where('status', MemberStatusEnum::ACTIVE->value)
            ->count();
        $memberCharts = Member::query()
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(status_updated_at, "%Y-%m") as month')
            ->where('status', MemberStatusEnum::ACTIVE->value)
            ->where('status_updated_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month')
            ->toArray();
        $memberCharts = $months->merge($memberCharts)->values()->toArray();

        return [
            Stat::make('Total Member', $memberTotal)
                ->chart($memberCharts)
                ->color('success'),
        ];
    }
}
