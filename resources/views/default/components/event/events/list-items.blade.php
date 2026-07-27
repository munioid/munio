<div class="space-y-5 p-5">
    @foreach($events as $event)
    <x-event.events.list-card :theme=$theme :event=$event />
    @endforeach
</div>
@if ($events->hasMorePages())
<x-partial.load-more />
@endif