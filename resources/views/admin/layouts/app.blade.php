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

    @if (Session::has('success'))
        <div id="dismiss-toast"
            class="hs-removing:translate-x-5 hs-removing:opacity-0 transition duration-300 max-w-xs rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 absolute top-0 end-0 z-10 mt-2 me-2 bg-red-500"
            role="alert">
            <div class="flex p-4">
                <p class="text-sm text-white">
                    {{ Session::get('success') }}
                </p>

                <div class="ms-auto">
                    <button type="button"
                        class="inline-flex flex-shrink-0 justify-center text-white items-center h-5 w-5 rounded-lg text-gray-800 opacity-50 hover:opacity-100 focus:outline-none focus:opacity-100 dark:text-white"
                        data-hs-remove-element="#dismiss-toast">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (Session::has('error'))
    @endif

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
