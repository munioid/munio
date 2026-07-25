<section class="mt-3 bg-white py-6">
    <div class="flex justify-between items-center px-5 mb-5">

        <h2 class="text-2xl font-semibold">
            Acara Terdekat
        </h2>

        <a href="#" class="text-primary font-medium">
            Selengkapnya →
        </a>

    </div>
    <div class="flex gap-4 overflow-x-auto px-5 pb-2">
        @foreach($events as $event)
        <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">
            <img
                src="{{ $event->cover?->getPath() ?? 'https://picsum.photos/500/300?1' }}"
                class="h-45 w-full object-cover">
            <div class="p-5">
                <h3 class="mt-2 text-2xl font-semibold leading-tight line-clamp-2">
                    {{$event->title}}
                </h3>
                <p class="text-gray-300 mt-4">
                    {{$event->event_date}}
                </p>
                <div class="mt-3">
                    @if($event->price)
                    <span class="text-lg font-bold text-primary">
                        Rp {{ number_format($event->price, 0, ',', '.') }}
                    </span>
                    @else
                    <span class="text-lg font-bold text-primary">
                        FREE
                    </span>
                    @endif
                </div>
                <div class="mt-5 flex gap-3">
                    <a
                        href="{{ url('/events/' . $event->slug) }}"
                        class="flex-1 rounded-xl border border-primary text-primary py-3 font-medium hover:bg-primary transition text-center">
                        Detail
                    </a>
                    <button
                        class="flex-1 rounded-xl bg-primary text-white py-3 font-medium hover:bg-primary transition">
                        Join
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</section>