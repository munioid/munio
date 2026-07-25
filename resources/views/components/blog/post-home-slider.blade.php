<section class="mt-3 bg-white py-6">

    <div class="flex justify-between items-center px-5 mb-5">

        <h2 class="text-2xl font-semibold">
            Berita Terkini
        </h2>

        <a href="/posts" class="text-green-500 font-medium">
            Selengkapnya →
        </a>

    </div>

    <div class="flex gap-4 overflow-x-auto px-5 pb-2">

        <!-- CARD -->
        @foreach($posts as $post)
        <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">
            <img
                src="{{ $post->cover?->getPath() ?? 'https://picsum.photos/500/300?1' }}"
                class="h-45 w-full object-cover">

            <div class="p-5">
                <div class="text-green-600 uppercase text-xs font-semibold">
                    {{$post->category?->name}}
                </div>
                <h3 class="mt-2 text-2xl font-semibold leading-tight line-clamp-2">
                    {{ $post->title }}
                </h3>
                <p class="mt-3 text-sm text-gray-600 leading-6 line-clamp-3">
                    {{ $post->excerpt }}
                </p>
                <p class="text-gray-300 mt-4">
                    {{$post->published_at->format('d F Y • H:i')}}
                </p>
            </div>
        </div>
        @endforeach
    </div>

</section>