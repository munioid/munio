<?php

use App\Models\Event\Event;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component
{
    public string $theme;

    public Collection $categories;
    public int $perPage = 10;

    public string $search = '';
    public ?string $selectedCategory = null;

    public function getEventsProperty()
    {
        return Event::query()
            ->published()
            ->when(
                $this->search,
                fn($query) => $query->search($this->search)
            )
            ->when(
                $this->selectedCategory,
                fn($query) => $query->whereHas('category', function ($categories) {
                    $categories->where('slug', $this->selectedCategory);
                })
            )
            ->paginate($this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->perPage = 10;
    }

    public function selectCategory(?string $category): void
    {
        $this->selectedCategory = $category;
        $this->perPage = 10;
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }
};
?>
<x-event.events.container :theme=$theme>
    {{-- Filter --}}
    <x-slot:filter>
        <x-event.events.filter :theme=$theme :categories=$categories :selectedCategory=$selectedCategory />
    </x-slot:filter>

    {{-- Events --}}
    <x-slot:content>
        <x-event.events.list-items :theme=$theme :events="$this->events" />
    </x-slot:content>
</x-event.events.container>