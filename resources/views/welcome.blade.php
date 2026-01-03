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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .animated-bg {
                background: linear-gradient(-45deg, #0f172a, #1e1b4b, #312e81, #0f172a);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
    </head>
    <body class="antialiased text-white animated-bg min-h-screen flex flex-col relative overflow-x-hidden">
        
        <!-- Background Elements (Glows) -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Navbar -->
        <nav class="relative z-10 w-full px-6 py-6 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto drop-shadow-lg">
                <!-- UPDATED LOGO COLORS HERE -->
                <span class="font-bold text-lg tracking-wide">
                    <span class="text-[#FF4D4D]">M</span><span class="text-[#2E86DE]">7</span> PCIS
                </span>
            </div>
            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-sm font-semibold transition backdrop-blur-md">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-full text-sm font-bold shadow-lg shadow-indigo-500/30 transition transform hover:scale-105">
                            Sign In
                        </a>
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Main Content -->
        <main class="relative z-10 flex-grow flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center mt-10 mb-20">
            
            <!-- Hero Section -->
            <div class="mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-900/50 border border-indigo-500/30 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    System Operational
                </div>
                
                <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight mb-6 leading-tight">
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-white via-indigo-200 to-indigo-400">
                        Next-Gen Payroll
                    </span>
                    <span class="block text-4xl sm:text-6xl mt-2 text-slate-400">
                        & HR Management
                    </span>
                </h1>
                
                <p class="mt-4 text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    A comprehensive solution for M7 Philippine Cambridge School. 
                    Streamlining attendance, salary computation, and employee management in one secure platform.
                </p>

                <div class="mt-10 flex justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-white text-indigo-900 rounded-full font-bold text-lg shadow-xl hover:bg-gray-100 transition transform hover:-translate-y-1">
                            Access Workspace
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-full font-bold text-lg shadow-xl shadow-indigo-500/20 hover:from-indigo-500 hover:to-blue-500 transition transform hover:-translate-y-1">
                            LOGIN
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full text-left">
                
                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition group">
                    <div class="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center mb-4 text-indigo-400 group-hover:text-indigo-300 group-hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Smart Attendance</h3>
                    <p class="text-sm text-slate-400">Real-time QR Kiosk tracking for employees and students with automated late detection.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition group">
                    <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center mb-4 text-emerald-400 group-hover:text-emerald-300 group-hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Automated Payroll</h3>
                    <p class="text-sm text-slate-400">Dynamic 5th/20th split calculations with custom allowances, deductions, and PDF payslips.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition group">
                    <div class="w-12 h-12 rounded-lg bg-rose-500/20 flex items-center justify-center mb-4 text-rose-400 group-hover:text-rose-300 group-hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Secure Access</h3>
                    <p class="text-sm text-slate-400">Role-based access control (Admin, Guard, Employee) with encrypted data protection.</p>
                </div>

            </div>

        </main>

        <!-- Footer -->
        <footer class="relative z-10 py-6 text-center border-t border-white/10 bg-slate-900/50 backdrop-blur-md">
            <p class="text-slate-400 text-xs">
                <!-- UPDATED FOOTER COLORS HERE -->
                &copy; {{ date('Y') }} <span class="text-[#FF4D4D]">M</span><span class="text-[#2E86DE]">7</span> PCIS. All rights reserved.
            </p>
            <p class="text-slate-500 text-[10px] mt-1">
                System Architecture & Development by <span class="text-indigo-400 font-bold">Nikko Calumpiano</span>
            </p>
        </footer>

    </body>
</html>