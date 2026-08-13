<header class="bg-white shadow-sm">
    <div class="flex justify-between items-center px-5 pt-5 pb-5">

        <div class="flex items-center gap-3">
            @if ($organization->icon)
            <img
                src="{{ $organization->icon->getPath() }}"
                alt="{{ $organization->name }}"
                class="h-12 rounded-xl object-cover">
            @else
            <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-xl">
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

            @auth('web')
            <a
                href="/profile"
                class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-primary text-lg font-semibold text-white">

                @if(auth('web')->user()->avatar)
                <img
                    src="{{ auth('web')->user()->avatar->getPath() }}"
                    alt="{{ auth('web')->user()->name }}"
                    class="h-full w-full object-cover">
                @else
                {{ strtoupper(mb_substr(auth('web')->user()->name, 0, 1)) }}
                @endif

            </a>
            @else
            <a
                href="/login"
                class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-200 bg-white">
                <x-heroicon-o-user class="h-6 w-6 text-gray-600" />
            </a>
            @endauth

        </div>
    </div>
</header>