<!DOCTYPE html>
<html lang="en" data-theme="{{ $theme ?? 'default' }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title inertia>{{ config('app.name', 'Munio') }}</title>
        <style>
            :root {
                --primary-color: {{ $primaryColor ?? '#ff5c54' }};
            }

            ::-webkit-scrollbar {
                display: none;
            }

            body {
                background: #f3f4f6;
            }
        </style>
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>
