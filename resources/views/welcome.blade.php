<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'M7 PCIS') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased">
        
        <!-- Main Container -->
        <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-900 text-white selection:bg-indigo-500 selection:text-white relative">

            <!-- Content Box -->
            <div class="w-full max-w-2xl px-6 lg:px-8 text-center">
                
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="M7 PCIS Logo" class="h-32 w-auto drop-shadow-2xl">
                </div>

                <!-- School Name -->
                <h1 class="text-5xl font-extrabold tracking-tight sm:text-6xl mb-4">
                    M7 PCIS
                </h1>
                
                <!-- System Title -->
                <p class="text-lg sm:text-xl text-indigo-200 mb-12 font-light tracking-wide">
                    Human Resources & Payroll Management System
                </p>

                <!-- Centered Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <!-- If already logged in -->
                            <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-white text-indigo-900 font-bold rounded-full shadow-lg hover:bg-gray-100 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-900">
                                Go to Dashboard
                            </a>
                        @else
                            <!-- Log In Button -->
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-indigo-900 font-bold rounded-full shadow-lg hover:bg-gray-100 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-900">
                                Log in
                            </a>
                        @endauth
                    @endif
                </div>

            </div>

            <!-- Footer (Developer Credit) -->
            <div class="absolute bottom-6 text-center w-full">
                <p class="text-indigo-300 text-sm">
                    &copy; {{ date('Y') }} M7 PCIS. All rights reserved.
                </p>
                <p class="text-indigo-400 text-xs mt-1 opacity-80">
                    System Architecture & Development by <span class="font-bold text-white">Nikko Calumpiano</span>
                </p>
            </div>

        </div>
    </body>
</html>