<a href="/posts/{{$post->slug}}" class="flex gap-4 rounded-2xl bg-white p-3 shadow-sm">
    <img
        src="{{ $post->cover?->getPath() ?? 'https://picsum.photos/240/240?1' }}"
        class="h-28 w-28 rounded-xl object-cover">
    <div class="flex flex-1 flex-col">
        <div class="text-xs font-semibold uppercase text-primary">
            {{ $post->category?->name }}
        </div>
        <h3 class="mt-1 line-clamp-2 text-lg font-semibold">
            {{ $post->title }}
        </h3>
        <p class="mt-2 line-clamp-2 text-sm text-gray-500">
            {{ $post->excerpt }}
        </p>
        <span class="mt-auto pt-3 text-xs text-gray-400">
            {{$post->published_at->format('d F Y • H:i')}}
        </span>

    </div>
</a>