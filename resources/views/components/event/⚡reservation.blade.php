<?php

use App\Models\Event\Event;
use App\Models\Organization\Organization;
use App\Services\Event\ReservationService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Livewire\Component;

new class extends Component
{
    public Organization $organization;
    public string $theme;
    public Event $event;

    public ?string $name = 'Andy';
    public ?string $email = 'andyeka07@gmail.com';
    public int $quantity = 1;
    public int|float|null $price;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->price = $event->price;
    }

    public function incrementQuantity(): void
    {
        if ($this->quantity < 10) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function getTotalPriceProperty(): float|int
    {
        return $this->price * $this->quantity;
    }

    public function register()
    {
        try {
            Filament::setTenant($this->organization, true);

            ReservationService::createReservation([
                'event_id' => $this->event->id,
                'name' => $this->name,
                'email' => $this->email,
                'quantity' => $this->quantity,
            ]);

            Notification::make()
                ->title('Reservasi berhasil')
                ->body('Reservasi Anda telah berhasil dibuat. Silakan cek email Anda untuk informasi selengkapnya.')
                ->success()
                ->actions([
                    Action::make('close')
                        ->label('Close')
                        ->button()
                        ->color('primary')
                        ->close(),
                ])
                ->send();

            return redirect('/events/' . $this->event->slug);
        } catch (\Throwable $e) {

            Notification::make()
                ->title('Reservasi gagal')
                ->body($e->getMessage())
                ->danger()
                ->actions([
                    Action::make('close')
                        ->label('Close')
                        ->button()
                        ->color('danger')
                        ->close(),
                ])
                ->send();
        }
    }
};
?>

<x-event.events.reservation :theme=$theme :event=$event :quantity=$quantity :price=$price />