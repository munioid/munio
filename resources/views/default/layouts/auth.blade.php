<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --primary-color: {{$organization?->colors['primary'] ?? '#ff5c54'}};
        }

        ::-webkit-scrollbar {
            display: none;
        }

        body {
            background: #f3f4f6;
        }
    </style>
    @vite('resources/css/app.css')
    @filamentStyles

    <title>@yield('title') - {{$organization->name}}</title>
    <link rel="icon" href="{{ $organization->favicon?->getPath() ?? asset('images/favicon.png') }}">
</head>

<body>

    <div class="mx-auto w-full min-h-screen bg-white
            max-w-[390px]
            sm:max-w-[430px]
            md:max-w-[480px]">

        <!-- HEADER -->

        <!-- CONTENT -->
        @yield('content')

        @livewireScripts
        @filamentScripts
        <div>
            @livewire('notifications', [
                'alignment' => 'right',
                'verticalAlignment' => 'start',
            ])
        </div>
    </div>

</body>

</html>