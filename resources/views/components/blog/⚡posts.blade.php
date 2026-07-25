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
    <x-blog.posts.filter :categories=$categories :selectedCategory=$selectedCategory :tags=$tags :selectedTags=$selectedTags />
    {{-- Posts --}}
    <x-blog.posts.list-items :posts="$this->posts" />
</div>