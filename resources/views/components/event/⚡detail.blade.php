<?php

use App\Models\Event\Event;
use Livewire\Component;

new class extends Component
{
    public string $theme;
    public Event $event;
};
?>

<x-event.events.detail :theme=$theme :event=$event />
