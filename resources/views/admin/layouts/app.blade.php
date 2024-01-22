<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/panel.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    @include('admin.layouts.header')
    <main id="content" role="main">
        @include('admin.layouts.navigation')
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 min-h-[27.5rem]">
            {{ $slot }}
        </div>
    </main>
    @include('admin.layouts.footer')
</body>

</html>
