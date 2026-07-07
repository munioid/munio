<x-filament::page>

    <form id="form" wire:submit="save">
        {{ $this->form }}

        <div style="padding-top: 1.5rem;">
            @foreach ($this->getCachedFormActions() as $action)
            {{ $action }}
            @endforeach
        </div>
    </form>

</x-filament::page>