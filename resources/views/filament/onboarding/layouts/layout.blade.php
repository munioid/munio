<x-filament-panels::layout.base :livewire="$livewire">
    @props([
    'after' => null,
    'heading' => null,
    'subheading' => null,
    ])

    <div class="bg-gray-50 dark:bg-gray-950 flex min-h-screen flex-col items-center">
        @if (($hasTopbar ?? true) && filament()->auth()->check())
        <div class="container">
            @if (filament()->hasDatabaseNotifications())
            @livewire(Filament\Livewire\DatabaseNotifications::class, [
            'lazy' => filament()->hasLazyLoadedDatabaseNotifications()
            ])
            @endif

            <x-filament-panels::user-menu />
        </div>
        @endif

        <div class="container flex w-full flex-grow items-center justify-center">
            <main class="max-w-4xl my-16 w-full bg-white px-6 shadow-xl dark:bg-gray-900 sm:rounded-xl sm:px-12">
                {{ $slot }}
            </main>
        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}
    </div>
</x-filament-panels::layout.base>