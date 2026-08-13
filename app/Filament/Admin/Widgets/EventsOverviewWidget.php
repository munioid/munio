<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event\Event;
use Filament\Schemas\Components\Component;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class EventsOverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Events';

    protected static ?int $sort = 10;

    public function getSectionContentComponent(): Component
    {
        return parent::getSectionContentComponent()
            ->heading(new HtmlString('<span class="text-xl">'.e($this->getHeading()).'</span>'));
    }

    protected function getStats(): array
    {
        $today = today();

        $total = Event::query()->count();

        $upcoming = Event::query()
            ->whereDate('start_at', '>', $today)
            ->count();

        $ongoing = Event::query()
            ->whereDate('start_at', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereDate('end_at', '>=', $today)
                    ->orWhere(function ($query) use ($today) {
                        $query->whereNull('end_at')->whereDate('start_at', $today);
                    });
            })
            ->count();

        $completed = Event::query()
            ->where(function ($query) use ($today) {
                $query->whereDate('end_at', '<', $today)
                    ->orWhere(function ($query) use ($today) {
                        $query->whereNull('end_at')->whereDate('start_at', '<', $today);
                    });
            })
            ->count();

        return [
            Stat::make('Total Events', $total)
                ->color('primary'),
            Stat::make('Upcoming Events', $upcoming)
                ->color('info'),
            Stat::make('Ongoing Events', $ongoing)
                ->color('warning'),
            Stat::make('Completed Events', $completed)
                ->color('success'),
        ];
    }
}
