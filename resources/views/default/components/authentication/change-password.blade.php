<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="relative bg-primary px-6 pb-16 pt-8 text-center">

        <button
            type="button"
            onclick="history.back()"
            class="absolute left-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur">

            <x-heroicon-o-arrow-left class="h-5 w-5" />

        </button>

        <h1 class="text-xl font-semibold text-white">
            Ubah Password
        </h1>

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

    <form wire:submit="save">

        <div class="px-5 pb-8 pt-5">

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                <div class="divide-y divide-gray-100">

                    {{-- Password Lama --}}
                    <div class="p-5">

                        <label class="mb-2 block text-sm font-medium text-gray-500">
                            Password Lama <span class="text-danger-600">*</span>
                        </label>

                        <x-filament::input.wrapper>
                            <x-filament::input
                                wire:model="current_password"
                                type="password"
                                autocomplete="current-password" />
                        </x-filament::input.wrapper>

                        @error('current_password')
                        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Password Baru --}}
                    <div class="p-5">

                        <label class="mb-2 block text-sm font-medium text-gray-500">
                            Password Baru <span class="text-danger-600">*</span>
                        </label>

                        <x-filament::input.wrapper>
                            <x-filament::input
                                wire:model="password"
                                type="password"
                                autocomplete="new-password" />
                        </x-filament::input.wrapper>

                        @error('password')
                        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- Konfirmasi --}}
                    <div class="p-5">

                        <label class="mb-2 block text-sm font-medium text-gray-500">
                            Konfirmasi Password
                        </label>

                        <x-filament::input.wrapper>
                            <x-filament::input
                                wire:model="password_confirmation"
                                type="password"
                                autocomplete="new-password" />
                        </x-filament::input.wrapper>

                    </div>

                </div>

            </div>

            <button
                type="submit"
                class="mt-6 w-full rounded-2xl bg-primary py-4 font-semibold text-white transition hover:opacity-90">

                Simpan

            </button>

        </div>

    </form>

</div>