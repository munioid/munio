<x-filament::page style="margin-bottom: 500px">
    <form wire:submit="save">
        {{ $this->content }}

        <div style="padding-top: 1.5rem;">
            @foreach ($this->getCachedFormActions() as $action)
            {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament::page>
<script>
    document.addEventListener('livewire:init', function() {
        Livewire.on('organizationUpdated', function(param) {
            window.location.href = `/admin/${param.code}/settings/organization`;
        });
    });
</script>