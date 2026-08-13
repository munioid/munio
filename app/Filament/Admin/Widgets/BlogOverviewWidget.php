<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Blog\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogOverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Blog';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $months = collect(range(0, 11))->mapWithKeys(function ($i) {
            return [now()->subMonths(11 - $i)->format('Y-m') => 0];
        });

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
            Stat::make('Total Post', $postTotal)
                ->chart($postCharts)
                ->color('success'),
        ];
    }
}
