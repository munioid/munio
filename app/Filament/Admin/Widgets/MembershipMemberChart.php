<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\MemberStatusEnum;
use App\Models\Membership\Member;
use Filament\Widgets\ChartWidget;

class MembershipMemberChart extends ChartWidget
{
    protected ?string $heading = 'Members';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months = collect(range(0, 11))->mapWithKeys(function ($i) {
            return [$i => now()->subMonths(11 - $i)->format('Y-m')];
        });

        $activeCharts = Member::query()
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(status_updated_at, "%Y-%m") as month')
            ->where('status', MemberStatusEnum::ACTIVE->value)
            ->where('status_updated_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month');

        $data = $months->map(function ($month) use ($activeCharts) {
            return $activeCharts[$month] ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'New Active Member',
                    'data' => $data,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
