<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Blog\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogOverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Blog';

    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $postTotal = Post::query()
            ->published()
            ->count();

        return [
            Stat::make('Total Post', $postTotal)
                ->color('success'),
        ];
    }
}
