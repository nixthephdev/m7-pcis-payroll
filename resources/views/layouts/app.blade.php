<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'M7 PCIS') }}</title>
        
        <!-- FAVICON (ADDED) -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" 
          x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
          x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
          :class="{ 'dark': darkMode }">
        
        <div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-slate-800 shadow transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Floating Dark Mode Toggle -->
        <button @click="darkMode = !darkMode" 
                class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white dark:bg-slate-800 shadow-xl border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-white hover:scale-110 transition-transform duration-200 focus:outline-none group">
            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 group-hover:text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
        </button>

        <!-- Developer Signature -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const signature = "U3lzdGVtIERldmVsb3BlZCBieSBOaWtrbyBDYWx1bXBpYW5v";
                const decoded = atob(signature);
                console.log("%c " + decoded + " ", "background: #312e81; color: #ffffff; font-size: 12px; padding: 5px 10px; border-radius: 4px; font-weight: bold;");
            });
        </script>
    </body>
</html>