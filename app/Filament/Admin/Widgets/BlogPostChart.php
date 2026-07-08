<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Blog\Post;
use Filament\Widgets\ChartWidget;

class BlogPostChart extends ChartWidget
{
    protected ?string $heading = 'Posts';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $months = collect(range(0, 11))->mapWithKeys(function ($i) {
            return [$i => now()->subMonths(11 - $i)->format('Y-m')];
        });

        $postCharts = Post::query()
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(published_at, "%Y-%m") as month')
            ->where('is_published', true)
            ->where('published_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $data = $months->map(function ($month) use ($postCharts) {
            return $postCharts[$month] ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Published',
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
