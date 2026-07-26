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

<div>
    <div class="min-h-screen bg-gray-50 pb-28">

        {{-- Event --}}
        <div class="bg-white p-5 shadow-sm">

            <div class="flex gap-4">

                <img
                    src="{{ $event->cover?->getPath() ?? 'https://picsum.photos/200/200' }}"
                    class="h-24 w-24 rounded-xl object-cover">

                <div class="flex-1">

                    <h1 class="text-xl font-bold">
                        {{ $event->title }}
                    </h1>

                    <div class="mt-3 space-y-2 text-sm text-gray-500">

                        <div class="flex items-center gap-2">
                            <x-heroicon-o-calendar-days class="h-4 w-4" />
                            {{ $event->event_date }}
                        </div>

                        @if($event->location)
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-map-pin class="h-4 w-4" />
                            {{ $event->location }}
                        </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- Reservation --}}
        <div class="mt-5 space-y-5 px-5">

            <div class="rounded-2xl bg-white p-5">

                <h2 class="text-lg font-semibold">
                    Data Peserta
                </h2>

                <div class="mt-5 space-y-4">

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            wire:model.live.debounce.300ms="name"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Email
                        </label>

                        <input
                            type="email"
                            wire:model.live.debounce.300ms="email"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none">
                    </div>

                    <!-- <div>
                        <label class="mb-2 block text-sm font-medium">
                            Nomor WhatsApp
                        </label>

                        <input
                            type="tel"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none">
                    </div> -->

                </div>

            </div>

            {{-- Ticket --}}
            <div class="rounded-2xl bg-white p-5">

                <h2 class="text-lg font-semibold">
                    Tiket
                </h2>

                <div class="mt-4 flex items-center justify-between">

                    <div>
                        <div class="font-medium">
                            Jumlah Tiket
                        </div>

                        <div class="text-sm text-gray-500">
                            Maksimal 10 tiket
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            wire:click="decrementQuantity"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 transition hover:bg-gray-100 disabled:opacity-40"
                            @disabled($quantity <=1)>
                            <x-heroicon-o-minus class="h-4 w-4" />
                        </button>

                        <span class="w-8 text-center text-lg font-semibold">
                            {{ $quantity }}
                        </span>

                        <button
                            wire:click="incrementQuantity"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 transition hover:bg-gray-100 disabled:opacity-40"
                            @disabled($quantity>= 10)>
                            <x-heroicon-o-plus class="h-4 w-4" />
                        </button>

                    </div>

                </div>

            </div>

            {{-- Summary --}}
            <div class="rounded-2xl bg-white p-5">

                <h2 class="text-lg font-semibold">
                    Ringkasan
                </h2>

                <div class="mt-4 space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span>Harga Tiket</span>
                        <span>
                            {{ $event->price ? 'Rp '.number_format($price,0,',','.') : 'GRATIS' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Jumlah</span>
                        <span>{{ $quantity }}</span>
                    </div>

                    <div class="flex justify-between border-t pt-3 text-base font-bold">
                        <span>Total</span>

                        <span class="text-primary">
                            {{ $event->price ? 'Rp '.number_format($this->total_price,0,',','.') : 'GRATIS' }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Bottom Action --}}
    <div class="fixed inset-x-0 bottom-20">
        <div class="mx-auto max-w-md px-4">
            <button
                wire:click="register"
                wire:loading.attr="disabled"
                class="w-full rounded-2xl bg-primary py-4 text-lg font-semibold text-white shadow-xl">

                <span wire:loading.remove wire:target="register">
                    Daftar
                </span>

                <span wire:loading wire:target="register">
                    Memproses...
                </span>

            </button>
        </div>
    </div>
</div>