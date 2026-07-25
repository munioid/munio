<header class="bg-white shadow-sm">
    <div class="flex justify-between items-center px-5 pt-5 pb-5">

        <div class="flex items-center gap-3">
            @if ($organization->icon)
            <img
                src="{{ $organization->icon->getPath() }}"
                alt="{{ $organization->name }}"
                class="h-12 rounded-xl object-cover">
            @else
            <div class="w-12 h-12 rounded-xl bg-green-600 flex items-center justify-center text-white font-bold text-xl">
                M
            </div>

            <div>
                <h1 class="font-bold text-lg">
                    {{$organization->name}}
                </h1>
            </div>
            @endif
        </div>

        <div class="flex gap-3">
            <button class="w-12 h-12 rounded-full border flex items-center justify-center">
                👤
            </button>
        </div>
    </div>
</header>