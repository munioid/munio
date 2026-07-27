<div class="min-h-screen bg-gray-50">

    {{-- Profile Header --}}
    <div class="relative bg-primary px-6 pb-16 pt-8 text-center">

        <button
            type="button"
            onclick="history.back()"
            class="absolute left-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
        </button>

        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2">

            @if($user->avatar)
            <img
                src="{{ $user->avatar->getPath() }}"
                class="h-28 w-28 rounded-full border-4 border-white object-cover shadow-xl">
            @else
            <div class="flex h-28 w-28 items-center justify-center rounded-full border-4 border-white bg-white text-4xl font-bold text-primary shadow-xl">
                {{ $initial }}
            </div>
            @endif

        </div>

    </div>

    {{-- User Info --}}
    <div class="bg-white pt-20 pb-8 text-center shadow-sm">

        <h1 class="text-2xl font-bold">
            {{ $user->name }}
        </h1>

        <p class="mt-2 text-gray-500">
            {{ $user->email }}
        </p>

    </div>

    {{-- Menu --}}
    <div class="mt-5 space-y-3 px-5">

        <a
            href="/profile/edit"
            class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">

            <div class="flex items-center gap-3">
                <x-heroicon-o-user class="h-6 w-6 text-primary" />
                <span>Edit Profil</span>
            </div>

            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
        </a>

        <a
            href="/profile/change-password"
            class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">

            <div class="flex items-center gap-3">
                <x-heroicon-o-lock-closed class="h-6 w-6 text-primary" />
                <span>Ubah Password</span>
            </div>

            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
        </a>

        <a
            href="/my-events"
            class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">

            <div class="flex items-center gap-3">
                <x-heroicon-o-calendar-days class="h-6 w-6 text-primary" />
                <span>Reservasi Acara</span>
            </div>

            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
        </a>

    </div>

    {{-- Logout --}}
    <div class="mt-8 px-5">

        <button
            wire:click="logout"
            class="w-full rounded-2xl border border-red-500 py-4 font-semibold text-red-500">

            Keluar

        </button>

    </div>

</div>