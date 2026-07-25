<?php

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component
{
    public Collection $categories;
    public Collection $tags;

    public string $search = '';
    public ?string $selectedCategory = null;
    public array $selectedTags = [];

    public int $perPage = 10;

    public function mount(Collection $categories, Collection $tags)
    {
        $this->categories = $categories;
        $this->tags = $tags;
    }

    public function getPostsProperty()
    {
        return Post::query()
            ->published()
            ->when($this->search, fn($query) => $query->search($this->search))
            ->when(
                $this->selectedCategory,
                fn($query) => $query->whereHas('category', function ($categories) {
                    $categories->where('slug', $this->selectedCategory);
                })
            )
            ->when(
                ! empty($this->selectedTags),
                fn($query) => $query->whereHas('tags', function ($tags) {
                    $tags->whereIn('slug', $this->selectedTags);
                })
            )
            ->latest('published_at')
            ->paginate($this->perPage);
    }

    public function selectCategory(?string $category): void
    {
        $this->selectedCategory = $category;
        $this->perPage = 10;
    }

    public function selectTag(string $slug): void
    {
        if (in_array($slug, $this->selectedTags, true)) {
            $this->selectedTags = array_values(
                array_filter(
                    $this->selectedTags,
                    fn($tag) => $tag !== $slug
                )
            );
        } else {
            $this->selectedTags[] = $slug;
        }

        $this->perPage = 10;
    }
    
    public function updatedSearch(): void
    {
        $this->perPage = 10;
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }
};
?>

<div>
    {{-- Filters --}}
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
    {{-- Posts --}}
    <div class="space-y-4 p-5">
        @foreach ($this->posts as $post)
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
        @endforeach
    </div>
    @if ($this->posts->hasMorePages())
    <div class="px-5 pb-6">
        <button
            wire:click="loadMore"
            wire:loading.attr="disabled"
            class="w-full rounded-xl border border-primary py-3 text-primary font-medium hover:bg-primary hover:text-white transition">

            <span wire:loading.remove wire:target="loadMore">
                Muat Lebih Banyak
            </span>

            <span wire:loading wire:target="loadMore">
                Memuat...
            </span>
        </button>
    </div>
    @endif
</div>