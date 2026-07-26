<div class="overflow-hidden rounded-2xl bg-white shadow-sm">
    <img
        src="{{ $event->cover?->getPath() ?? 'https://picsum.photos/800/400?1' }}"
        class="aspect-[16/9] w-full object-cover">
    <div class="p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold leading-tight line-clamp-2">
                    {{ $event->title }}
                </h2>
                <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                    <x-heroicon-o-calendar-days class="h-4 w-4" />
                    <span>{{ $event->event_date }}</span>
                </div>
                <div class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                    <x-heroicon-o-tag class="h-4 w-4" />
                    <span>{{ $event->category?->name }}</span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs uppercase text-gray-400">
                    Harga
                </div>
                <div class="mt-1 font-bold text-primary">
                    @if($event->price)
                    Rp {{ number_format($event->price, 0, ',', '.') }}
                    @else
                    GRATIS
                    @endif
                </div>
            </div>
        </div>
        <p class="mt-4 line-clamp-2 text-sm text-gray-500">
            {{ $event->excerpt }}
        </p>
        <div class="mt-5 flex gap-3">
            <a href="{{ url('/events/' . $event->slug) }}"
                class="flex-1 rounded-xl border border-primary text-primary py-3 font-medium hover:bg-primary hover:text-white transition text-center">
                Detail
            </a>
            @if($event->register_url)
            <a href="{{ $event->register_url }}"
                class="flex-1 rounded-xl bg-primary py-3 font-medium text-white py-3 font-medium hover:bg-primary hover:text-white transition text-center">
                Daftar
            </a>
            @endif
        </div>
    </div>
</div>