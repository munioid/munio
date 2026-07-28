<div>
    <div class="min-h-screen bg-gray-50 pb-10">

        {{-- Event Cover --}}
        <div class="relative">
            <img
                src="{{ $event->cover?->getPath() ?? 'https://picsum.photos/1200/600' }}"
                class="aspect-[16/9] w-full object-cover">
        </div>

        <div class="-mt-6 relative rounded-t-3xl bg-white px-5 pt-6">

            {{-- Status --}}
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold uppercase text-primary">
                        Reservasi
                    </div>

                    <h1 class="mt-2 text-3xl font-bold leading-tight">
                        {{ $reservation->event->title }}
                    </h1>
                </div>

                <x-filament::badge
                    :color="$reservation->status?->getColor()"
                    class="shrink-0 rounded-full !px-3 !py-1 text-xs font-semibold">
                    {{ $reservation->status?->getLabel() }}
                </x-filament::badge>
            </div>

            {{-- Reservation Code --}}
            <div class="mt-3 text-sm text-gray-500">
                Kode Reservasi
            </div>

            <div class="font-mono text-lg font-semibold">
                {{ $reservation->code }}
            </div>

            {{-- Information --}}
            <div class="mt-6 divide-y divide-gray-100 rounded-2xl border">

                {{-- Date --}}
                <div class="flex items-start gap-4 p-4">
                    <x-heroicon-o-calendar-days class="mt-0.5 h-5 w-5 text-primary" />

                    <div>
                        <div class="font-medium">
                            Jadwal Event
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $event->event_date }}
                        </div>
                    </div>
                </div>

                {{-- Participant --}}
                <div class="flex items-start gap-4 p-4">
                    <x-heroicon-o-user class="mt-0.5 h-5 w-5 text-primary" />

                    <div>
                        <div class="font-medium">
                            Reservasi Oleh
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $reservation->name }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $reservation->email }}
                        </div>
                    </div>
                </div>

                {{-- Quantity --}}
                <div class="flex items-start gap-4 p-4">
                    <x-heroicon-o-users class="mt-0.5 h-5 w-5 text-primary" />

                    <div>
                        <div class="font-medium">
                            Jumlah Tiket
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $reservation->quantity }} Tiket
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="flex items-start gap-4 p-4">
                    <x-heroicon-o-credit-card class="mt-0.5 h-5 w-5 text-primary" />

                    <div>
                        <div class="font-medium">
                            Total Pembayaran
                        </div>

                        <div class="text-sm font-semibold text-primary">
                            @if($reservation->amount)
                            Rp {{ number_format($reservation->amount,0,',','.') }}
                            @else
                            GRATIS
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Created --}}
                <div class="flex items-start gap-4 p-4">
                    <x-heroicon-o-clock class="mt-0.5 h-5 w-5 text-primary" />

                    <div>
                        <div class="font-medium">
                            Tanggal Reservasi
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $reservation->created_at->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- QR --}}
            <!-- <div class="mt-8">
                <h2 class="text-lg font-semibold">
                    QR Check-in
                </h2>

                <div class="mt-4 flex justify-center rounded-2xl border bg-gray-50 p-6">
                    {!! $reservation->qr_code !!}
                </div>

                <p class="mt-3 text-center text-sm text-gray-500">
                    Tunjukkan QR Code ini saat melakukan check-in.
                </p>
            </div> -->

        </div>

    </div>

    {{-- Bottom CTA --}}
    <!-- <div class="fixed inset-x-0 bottom-20 z-40">
        <div class="mx-auto max-w-md px-4">
            <div class="rounded-2xl border bg-white p-4 shadow-xl">

                <div class="flex gap-3">

                    @if($reservation->ticket_url)
                    <a
                        href="{{ $reservation->ticket_url }}"
                        class="flex-1 rounded-xl bg-primary py-3 text-center font-medium text-white">
                        Lihat Tiket
                    </a>
                    @endif

                    @if($reservation->invoice_url)
                    <a
                        href="{{ $reservation->invoice_url }}"
                        class="rounded-xl border px-5 py-3 font-medium">
                        Invoice
                    </a>
                    @endif

                </div>

            </div>
        </div>
    </div> -->
</div>