<div class="sticky top-0 z-10 border-b bg-white px-5 py-4">

    <div class="mt-4">
        <input
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Cari acara..."
            class="w-full rounded-xl border border-gray-300 bg-white py-2 pl-4 pr-2 text-sm focus:border-primary focus:outline-none">
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
</div>