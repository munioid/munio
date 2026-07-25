<nav class="fixed inset-x-0 bottom-0 z-50">
    <div class="mx-auto max-w-md">
        <div class="flex rounded-full bg-white/95 backdrop-blur-lg shadow-2xl border border-gray-100 py-2">

            <a href="/" class="flex-1 flex flex-col items-center {{ request()->is('/') ? 'text-primary' : 'text-gray-400' }}">
                <x-heroicon-o-home class="w-6 h-6" />
                <span class="mt-1 text-[11px] font-medium">Home</span>
            </a>

            <a href="/posts" class="flex-1 flex flex-col items-center {{ request()->is('posts*') ? 'text-primary' : 'text-gray-400' }}">
                <x-heroicon-o-newspaper class="w-6 h-6" />
                <span class="mt-1 text-[11px]">Berita</span>
            </a>

            <a href="/events" class="flex-1 flex flex-col items-center text-gray-400">
                <x-heroicon-o-calendar-days class="w-6 h-6" />
                <span class="mt-1 text-[11px]">Acara</span>
            </a>

            <!-- <a href="#" class="flex-1 flex flex-col items-center text-gray-400">
                <x-heroicon-o-calendar-days class="w-6 h-6" />
                <span class="mt-1 text-[11px]">Member</span>
            </a> -->

        </div>
    </div>
</nav>