<div class="min-h-screen bg-gray-50 pb-7">
    {{-- Cover --}}
    <img
        src="{{ $post->cover?->getPath() ?? 'https://picsum.photos/900/500' }}"
        class="aspect-[16/9] w-full object-cover">
    <div class="rounded-t-3xl bg-white -mt-6 relative z-10 px-5 pt-6">
        {{-- Category --}}
        <div class="text-xs font-semibold uppercase text-primary">
            {{ $post->category?->name }}
        </div>
        {{-- Title --}}
        <h1 class="mt-2 text-3xl font-bold leading-tight">
            {{ $post->title }}
        </h1>
        {{-- Meta --}}
        <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
            <span>
                {{ $post->published_at->format('d F Y • H:i') }}
            </span>
            @if($post->author)
            <span>•</span>
            <span>
                {{ $post->author->name }}
            </span>
            @endif
        </div>

        {{-- Tags --}}
        @if($post->tags->count())
        <div class="mt-5 flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs">
                #{{ $tag->name }}
            </span>
            @endforeach
        </div>
        @endif

        {{-- Content --}}
        <article
            class="prose prose-gray mt-8 max-w-none prose-img:rounded-2xl prose-headings:font-bold prose-a:text-primary">
            {!! $post->content !!}
        </article>
    </div>

</div>