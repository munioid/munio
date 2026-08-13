<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\ReservationStatusEnum;
use App\Models\Event\Reservation;
use Filament\Schemas\Components\Component;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class ReservationsOverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Reservations';

    protected static ?int $sort = 12;

    public function getSectionContentComponent(): Component
    {
        return parent::getSectionContentComponent()
            ->heading(new HtmlString('<span class="text-xl">'.e($this->getHeading()).'</span>'));
    }

    protected function getStats(): array
    {
        $total = Reservation::query()->count();
        $pending = Reservation::query()->where('status', ReservationStatusEnum::PENDING)->count();
        $paid = Reservation::query()->where('status', ReservationStatusEnum::PAID)->count();
        $approved = Reservation::query()->where('status', ReservationStatusEnum::APPROVED)->count();
        $cancelled = Reservation::query()->where('status', ReservationStatusEnum::CANCELED)->count();

        return [
            Stat::make('Total Reservations', $total)
                ->color('primary'),
            Stat::make('Pending', $pending)
                ->color('gray'),
            Stat::make('Paid', $paid)
                ->color('success'),
            Stat::make('Approved', $approved)
                ->color('success'),
            Stat::make('Cancelled', $cancelled)
                ->color('danger'),
        ];
    }
}
