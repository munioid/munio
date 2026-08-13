<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="relative bg-primary px-6 pb-8 pt-8 text-center">

        <button
            type="button"
            onclick="history.back()"
            class="absolute left-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur">

            <x-heroicon-o-arrow-left class="h-5 w-5" />

        </button>

        <h1 class="text-xl font-semibold text-white">
            Reservasi Acara
        </h1>
    </div>

    <div class="space-y-4 px-5 pb-8 pt-4">

        @forelse($reservations as $reservation)

        <a
            href="/events/reservations/{{ $reservation->code }}"
            class="block rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md">

            <div class="flex items-start justify-between">

                <div>

                    <h2 class="font-semibold text-gray-900">
                        {{ $reservation->event->title }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $reservation->event->event_date }}
                    </p>

                </div>

                <x-filament::badge
                    :color="$reservation->status?->getColor()"
                    class="shrink-0 rounded-full !px-3 !py-1 text-xs font-semibold">
                    {{ $reservation->status?->getLabel() }}
                </x-filament::badge>

            </div>

            <div class="mt-4 flex items-center justify-between border-t pt-4">

                <div class="flex items-center gap-2 text-sm text-gray-500">

                    <x-heroicon-o-ticket class="h-5 w-5" />

                    {{ $reservation->quantity }}
                    Peserta

                </div>

                <div class="flex items-center gap-1 text-primary">

                    <span class="text-sm font-medium">
                        Detail
                    </span>

                    <x-heroicon-o-chevron-right class="h-4 w-4" />

                </div>

            </div>

        </a>

        @empty

        <div class="rounded-2xl bg-white px-6 py-14 text-center shadow-sm">

            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-primary/10">

                <x-heroicon-o-calendar-days class="h-10 w-10 text-primary" />

            </div>

            <h2 class="mt-6 text-lg font-semibold">
                Belum Ada Reservasi
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Anda belum memiliki reservasi acara.
            </p>

        </div>

        @endforelse

        @if ($reservations->hasMorePages())
        <x-partial.load-more :theme=$theme />
        @endif
    </div>

</div>