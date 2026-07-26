<div class="min-h-screen bg-gray-50">
    <img
        src="{{ $organization->login_banner?->getPath() ?? 'https://picsum.photos/240/240?1' }}"
        class="aspect-[16/9] w-full object-cover shadow-lg">

    {{-- Card --}}
    <div class="rounded-t-3xl bg-white -mt-6 relative z-10 px-5 pt-6">

        <div class="text-center">
            <h1 class="text-2xl font-bold">
                Selamat Datang
            </h1>

            <p class="mt-2 text-gray-500">
                Masuk untuk melanjutkan ke akun Anda.
            </p>
        </div>

        <form
            wire:submit="login"
            class="mt-8 space-y-5">

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Email
                </label>

                <input
                    type="email"
                    wire:model.live="email"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Password
                </label>

                <input
                    type="password"
                    wire:model.live="password"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none">
            </div>

            <div class="text-right">
                <a href="/forgot-password" class="text-sm text-primary">
                    Lupa Password?
                </a>
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-primary py-3 font-semibold text-white">
                Masuk
            </button>

        </form>

        <div class="my-8 flex items-center">
            <div class="h-px flex-1 bg-gray-200"></div>

            <span class="px-4 text-sm text-gray-400">
                atau
            </span>

            <div class="h-px flex-1 bg-gray-200"></div>
        </div>

        <a
            href=""
            class="flex items-center justify-center gap-3 rounded-xl border border-gray-300 bg-white py-3 font-medium">

            <img
                src="https://www.svgrepo.com/show/475656/google-color.svg"
                class="h-5 w-5">

            Masuk dengan Google

        </a>

    </div>
</div>