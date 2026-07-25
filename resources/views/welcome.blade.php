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
            md:max-w-[480px]">

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
                        <p class="text-sm text-gray-500">
                            Community Platform
                        </p>
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

        <!-- MENU -->
        <section class="bg-white mt-3 py-6">

            <div class="grid grid-cols-4 gap-4 px-5">

                <div class="text-center">
                    <div class="aspect-square w-full bg-green-50 rounded-3xl flex items-center justify-center">
                        <span class="text-5xl">📰</span>
                    </div>
                    <p class="mt-3 font-medium">
                        Berita
                    </p>
                </div>

                <div class="text-center">
                    <div class="aspect-square w-full bg-green-50 rounded-3xl flex items-center justify-center">
                        <span class="text-5xl">📅</span>
                    </div>
                    <p class="mt-3 font-medium">
                        Acara
                    </p>
                </div>

                <div class="text-center">
                    <div class="aspect-square w-full bg-green-50 rounded-3xl flex items-center justify-center">
                        <span class="text-5xl">🪪</span>
                    </div>
                    <p class="mt-3 font-medium">
                        Anggota
                    </p>
                </div>

                <div class="text-center">
                    <div class="aspect-square w-full bg-green-50 rounded-3xl flex items-center justify-center">
                        <span class="text-5xl">❤️</span>
                    </div>
                    <p class="mt-3 font-medium">
                        Donasi
                    </p>
                </div>

            </div>

        </section>



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

</body>

</html>