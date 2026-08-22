<!DOCTYPE html>
<html lang="en" data-theme="{{ $theme ?? 'default' }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title inertia>{{ config('app.name', 'Munio') }}</title>
        <style>
            :root {
                --primary-color: {{ $primaryColor ?? '#1f2937' }};
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
