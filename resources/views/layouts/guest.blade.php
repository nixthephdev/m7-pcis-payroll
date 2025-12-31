<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'M7 PCIS') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900">
            
            <!-- Logo Section -->
            <div class="mb-6 flex flex-col items-center">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" class="h-24 w-auto drop-shadow-lg" alt="Logo">
                </a>
                <h1 class="text-white text-2xl font-bold mt-4 tracking-wide">M7 PCIS</h1>
                <p class="text-indigo-200 text-sm">Human Resources & Payroll Management System</p>
            </div>

            <!-- Card Section -->
            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-xl border border-gray-100">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-indigo-300 text-xs">
                &copy; {{ date('Y') }} M7 PCIS. Secure System.
            </div>
        </div>
    </body>
</html>