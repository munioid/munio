<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\MemberStatusEnum;
use App\Models\Blog\Post;
use App\Models\Membership\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $months = collect(range(0, 11))->mapWithKeys(function ($i) {
            return [now()->subMonths(11 - $i)->format('Y-m') => 0];
        });

        # Members Stats
        $memberTotal = Member::query()
            ->where('status', MemberStatusEnum::ACTIVE->value)
            ->count();
        $memberCharts = Member::query()
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(status_updated_at, "%Y-%m") as month')
            ->where('status', MemberStatusEnum::ACTIVE->value)
            ->where('status_updated_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count')
            ->toArray();
        $memberCharts = $months->merge($memberCharts)->values()->toArray();

        # Posts Stats
        $postTotal = Post::query()
            ->published()
            ->count();
        $postCharts = Post::query()
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(published_at, "%Y-%m") as month')
            ->published()
            ->where('published_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count')
            ->toArray();
        $postCharts = $months->merge($postCharts)->values()->toArray();

        return [
            Stat::make('Total Member', $memberTotal)
                ->chart($memberCharts)
                ->color('success'),
            Stat::make('Total Post', $postTotal)
                ->chart($postCharts)
                ->color('success'),
        ];
    }
}
