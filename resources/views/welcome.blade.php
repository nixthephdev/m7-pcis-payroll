<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>M7 PCIS Payroll System</title>
        
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS (Using the CDN for the landing page to keep it simple) -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased">
        
        <!-- Main Container with Gradient Background -->
        <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-900 text-white selection:bg-indigo-500 selection:text-white">

            <!-- Content Box -->
            <div class="w-full max-w-2xl px-6 lg:px-8 text-center animate-fade-in-up">
                
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
                            <!-- If already logged in, show Dashboard button -->
                            <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-white text-indigo-900 font-bold rounded-full shadow-lg hover:bg-gray-100 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-900">
                                Go to Dashboard
                            </a>
                        @else
                            <!-- Log In Button -->
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-indigo-900 font-bold rounded-full shadow-lg hover:bg-gray-100 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-900">
                                Log in
                            </a>

                            <!-- Register Button -->
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white/10 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-900">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

            </div>

            <!-- Footer -->
            <div class="absolute bottom-6 text-indigo-300 text-sm">
                &copy; {{ date('Y') }} M7 PCIS. All rights reserved.
            </div>

        </div>
    </body>
</html>