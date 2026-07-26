<div class="space-y-5 p-5">
    @foreach($events as $event)
    <x-event.events.list-card :event=$event />
    @endforeach
</div>
@if ($events->hasMorePages())
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