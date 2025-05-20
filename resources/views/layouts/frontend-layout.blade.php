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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')
        <x-flash-messages />

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="">
                        <div class="text-gray-900 dark:text-gray-100">
                            <div class="flex w-full pt-4 gap-6">
                                <div class="w-full md:w-3/4 px-4 bg-white rounded-lg">
                                    {{ $slot }}
                                </div>

                                @include('layouts.sidebar')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @stack('js')
</body>

</html>
