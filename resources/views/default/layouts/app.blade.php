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
        @yield('header')

        <!-- CONTENT -->
        @yield('content')
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