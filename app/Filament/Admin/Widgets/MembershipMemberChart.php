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

        $colors = [
            MemberStatusEnum::ACTIVE->value => '#22c55e',
            MemberStatusEnum::PENDING->value => '#f59e0b',
            MemberStatusEnum::INACTIVE->value => '#6b7280',
            MemberStatusEnum::SUSPENDED->value => '#ef4444',
        ];

        $countsByStatus = Member::query()
            ->selectRaw('status, DATE_FORMAT(status_updated_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('status_updated_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('status', 'month')
            ->get()
            ->groupBy('status');

        $datasets = collect(MemberStatusEnum::cases())->map(function (MemberStatusEnum $status) use ($months, $countsByStatus, $colors) {
            $counts = $countsByStatus->get($status->value, collect())->pluck('count', 'month');

            $data = $months->map(fn ($month) => $counts[$month] ?? 0);

            return [
                'label' => $status->getLabel(),
                'data' => $data,
                'borderColor' => $colors[$status->value],
                'backgroundColor' => $colors[$status->value],
            ];
        })->values();

        return [
            'datasets' => $datasets,
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
