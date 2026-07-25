<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <title>Community App</title>

    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        body {
            background: #f3f4f6;
        }
    </style>
</head>

<body>

    <div class="mx-auto w-full min-h-screen bg-white
            max-w-[390px]
            sm:max-w-[430px]
            md:max-w-[480px] pb-10">

        <!-- HEADER -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center px-5 pt-12 pb-5">

                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-green-600 flex items-center justify-center text-white font-bold text-xl">
                        M
                    </div>

                    <div>
                        <h1 class="font-bold text-lg">
                            Munio
                        </h1>
                    </div>
                </div>

                <div class="flex gap-3">

                    <button class="w-12 h-12 rounded-full border flex items-center justify-center">
                        🔔
                    </button>

                    <button class="w-12 h-12 rounded-full border flex items-center justify-center">
                        👤
                    </button>

                </div>

            </div>
        </header>

        <!-- BERITA -->
        <section class="mt-3 bg-white py-6">

            <div class="flex justify-between items-center px-5 mb-5">

                <h2 class="text-2xl font-semibold">
                    Berita Terkini
                </h2>

                <a href="#" class="text-green-500 font-medium">
                    Selengkapnya →
                </a>

            </div>

            <div class="flex gap-4 overflow-x-auto px-5 pb-2">

                <!-- CARD -->
                <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">

                    <img
                        src="https://picsum.photos/500/300?1"
                        class="h-45 w-full object-cover">

                    <div class="p-5">

                        <div class="text-green-600 uppercase text-xs font-semibold">
                            General • Community
                        </div>

                        <h3 class="mt-2 text-2xl font-semibold leading-tight">
                            Summer Community Gathering 2026
                        </h3>

                        <p class="text-gray-300 mt-4">
                            05 July 2026 • 10:30
                        </p>
                    </div>

                </div>
                <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">

                    <img
                        src="https://picsum.photos/500/300?2"
                        class="h-45 w-full object-cover">

                    <div class="p-5">

                        <div class="text-green-600 uppercase text-xs font-semibold">
                            General • Community
                        </div>

                        <h3 class="mt-2 text-2xl font-semibold leading-tight">
                            Summer Community Gathering 2026
                        </h3>

                        <p class="text-gray-300 mt-4">
                            05 July 2026 • 10:30
                        </p>
                    </div>

                </div>
                <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">

                    <img
                        src="https://picsum.photos/500/300?3"
                        class="h-45 w-full object-cover">

                    <div class="p-5">

                        <div class="text-green-600 uppercase text-xs font-semibold">
                            General • Community
                        </div>

                        <h3 class="mt-2 text-2xl font-semibold leading-tight">
                            Summer Community Gathering 2026
                        </h3>

                        <p class="text-gray-300 mt-4">
                            05 July 2026 • 10:30
                        </p>
                    </div>

                </div>
            </div>

        </section>



        <!-- EVENT -->
        <section class="mt-3 bg-white py-6">

            <div class="flex justify-between items-center px-5 mb-5">

                <h2 class="text-2xl font-semibold">
                    Acara Terdekat
                </h2>

                <a href="#" class="text-green-500 font-medium">
                    Selengkapnya →
                </a>

            </div>

            <div class="flex gap-4 overflow-x-auto px-5 pb-2">

                <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">

                    <img
                        src="https://picsum.photos/500/300?1"
                        class="h-45 w-full object-cover">

                    <div class="p-5">

                        <h3 class="mt-2 text-2xl font-semibold leading-tight">
                            Morning Fun Run
                        </h3>

                        <p class="text-gray-300 mt-4">
                            Sunday, 20 July 2026
                        </p>

                        <div class="mt-5 flex gap-3">
                            <button
                                class="flex-1 rounded-xl border border-green-600 text-green-600 py-3 font-medium hover:bg-green-50 transition">
                                Detail
                            </button>

                            <button
                                class="flex-1 rounded-xl bg-green-600 text-white py-3 font-medium hover:bg-green-700 transition">
                                Join
                            </button>
                        </div>

                    </div>

                </div>
                <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">

                    <img
                        src="https://picsum.photos/500/300?2"
                        class="h-45 w-full object-cover">

                    <div class="p-5">

                        <h3 class="mt-2 text-2xl font-semibold leading-tight">
                            Morning Fun Run
                        </h3>

                        <p class="text-gray-300 mt-4">
                            Sunday, 20 July 2026
                        </p>

                        <div class="mt-5 flex gap-3">
                            <button
                                class="flex-1 rounded-xl border border-green-600 text-green-600 py-3 font-medium hover:bg-green-50 transition">
                                Detail
                            </button>

                            <button
                                class="flex-1 rounded-xl bg-green-600 text-white py-3 font-medium hover:bg-green-700 transition">
                                Join
                            </button>
                        </div>

                    </div>

                </div>
                <div class="min-w-[275px] bg-white border rounded-2xl overflow-hidden">

                    <img
                        src="https://picsum.photos/500/300?3"
                        class="h-45 w-full object-cover">

                    <div class="p-5">

                        <h3 class="mt-2 text-2xl font-semibold leading-tight">
                            Morning Fun Run
                        </h3>

                        <p class="text-gray-300 mt-4">
                            Sunday, 20 July 2026
                        </p>

                        <div class="mt-5 flex gap-3">
                            <button
                                class="flex-1 rounded-xl border border-green-600 text-green-600 py-3 font-medium hover:bg-green-50 transition">
                                Detail
                            </button>

                            <button
                                class="flex-1 rounded-xl bg-green-600 text-white py-3 font-medium hover:bg-green-700 transition">
                                Join
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>
    <nav class="fixed inset-x-0 bottom-0 z-50">
        <div class="mx-auto max-w-md">
            <div
                class="grid grid-cols-4 rounded-full bg-white/95 backdrop-blur-lg shadow-2xl border border-gray-100 py-2">

                <a href="#" class="flex flex-col items-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5" />
                    </svg>
                    <span class="text-[11px] mt-1 font-medium">Home</span>
                </a>

                <a href="#" class="flex flex-col items-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3M4 11h16M5 5h14v16H5z" />
                    </svg>
                    <span class="text-[11px] mt-1">Acara</span>
                </a>

                <a href="#" class="flex flex-col items-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5V4H2v16h5m10 0v-4a3 3 0 00-6 0v4" />
                    </svg>
                    <span class="text-[11px] mt-1">Member</span>
                </a>

                <a href="#" class="flex flex-col items-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21s-8-5.5-8-11a5 5 0 0110-1 5 5 0 0110 1c0 5.5-8 11-8 11z" />
                    </svg>
                    <span class="text-[11px] mt-1">Donasi</span>
                </a>

            </div>
        </div>
    </nav>
</body>

</html>