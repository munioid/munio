<?php

use App\Models\Event\Event;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component
{
    public Collection $events;
    
    public function mount()
    {
        $this->events = Event::query()
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }
};
?>

<div>
    <x-home.event-component :events=$events />
</div>