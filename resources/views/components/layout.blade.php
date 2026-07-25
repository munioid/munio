<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'My App' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{ $slot }}

</body>
</html>