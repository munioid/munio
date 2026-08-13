<div class="sticky top-0 z-10 bg-white border-b px-5 py-1 pb-4">
    {{-- Search --}}
    <div class="mt-4">
        <input
            id="search"
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Cari berita..."
            class="w-full rounded-xl border border-gray-300 bg-white py-2 pl-4 pr-2 text-sm focus:outline-none focus:border-primary focus:ring-primary">
    </div>

    {{-- Categories --}}
    <div class="mt-4 flex gap-2 overflow-x-auto">
        <button
            wire:click="selectCategory(null)"
            @class([ 'shrink-0 rounded-full px-4 py-2 text-sm' , 'bg-primary text-white'=> $selectedCategory === null,
            'border border-gray-200' => $selectedCategory !== null,
            ])>
            Semua
        </button>

        @foreach ($categories as $category)
        <button
            wire:click="selectCategory('{{ $category->slug }}')"
            @class([ 'shrink-0 rounded-full px-4 py-2 text-sm' , 'bg-primary text-white'=> $selectedCategory === $category->slug,
            'border border-gray-200' => $selectedCategory !== $category->slug,
            ])>
            {{$category->name}}
        </button>
        @endforeach
    </div>

    {{-- Tags --}}
    <div class="mt-3 flex gap-2 overflow-x-auto">
        @foreach ($tags as $tag)
        <button
            wire:click="selectTag('{{ $tag->slug }}')"
            @class([ 'shrink-0 rounded-full px-4 py-2 text-sm transition' , 'bg-primary text-white'=> in_array($tag->slug, $selectedTags, true),
            'border border-gray-200' => ! in_array($tag->slug, $selectedTags, true),
            ])>
            #{{ $tag->name }}
        </button>
        @endforeach
    </div>
</div>