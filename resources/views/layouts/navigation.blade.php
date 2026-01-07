<nav x-data="{ open: false }" class="bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 border-b border-indigo-500/30 shadow-lg sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- LOGO & BRANDING -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/logo.png') }}" class="block h-12 w-auto transition transform group-hover:scale-110 drop-shadow-md" alt="M7 Logo">
                        <div class="hidden lg:block">
                            <h1 class="text-white font-bold text-lg leading-tight tracking-wide group-hover:text-indigo-200 transition">M7 PCIS</h1>
                            <p class="text-[10px] text-indigo-300 uppercase font-semibold tracking-wider">HR & Payroll</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-indigo-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out border-none h-auto">{{ __('Dashboard') }}</x-nav-link>
                    <x-nav-link :href="route('leaves.index')" :active="request()->routeIs('leaves.index')" class="text-indigo-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out border-none h-auto">{{ __('Leaves') }}</x-nav-link>
                    
                    <!-- SUPERVISOR LINK (Dynamic with Notification) -->
                    @if(Auth::user()->employee && Auth::user()->employee->subordinates->count() > 0)
                        @php
                            // Count pending requests specifically for this supervisor
                            $teamPendingCount = \App\Models\LeaveRequest::whereIn('employee_id', Auth::user()->employee->subordinates->pluck('id'))
                                ->where('supervisor_status', 'Pending')
                                ->count();
                        @endphp

                        <x-nav-link :href="route('leaves.team')" :active="request()->routeIs('leaves.team')" 
                            class="text-indigo-100 hover:text-white hover:bg-white/10 px-2 py-2 rounded-md text-sm font-medium transition border-none h-auto flex items-center relative">
                            {{ __('Team Requests') }}
                            
                            <!-- NOTIFICATION BADGE -->
                            @if($teamPendingCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 text-[8px] text-white font-bold items-center justify-center">
                                    {{ $teamPendingCount }}
                                  </span>
                                </span>
                            @endif
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        @php 
                            $pendingLeavesCount = \App\Models\LeaveRequest::where('status', 'Pending')->count();
                            // NEW: Count Pending Payrolls
                            $pendingPayrollCount = \App\Models\Payroll::where('status', 'Pending')->count();
                        @endphp

                        <div class="h-6 w-px bg-indigo-700/50 mx-2"></div>
                        
                        <!-- DIRECTORY DROPDOWN (Employees & Students Grouped) -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-indigo-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition focus:outline-none">
                                <span>People</span>
                                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Body -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg py-1 z-50 border border-gray-100 dark:border-slate-700" 
                                 style="display: none;">
                                
                                <a href="{{ route('employees.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                                    Employees
                                </a>
                                <a href="{{ route('students.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                                    Students
                                </a>
                            </div>
                        </div>

                        <x-nav-link :href="route('leaves.manage')" :active="request()->routeIs('leaves.manage')" class="text-indigo-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out border-none h-auto flex items-center relative">
                            {{ __('Approvals') }}
                            @if($pendingLeavesCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span><span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-[10px] text-white font-bold items-center justify-center">{{ $pendingLeavesCount }}</span></span>
                            @endif
                        </x-nav-link>
                        <x-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" class="text-indigo-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out border-none h-auto">{{ __('Attendance') }}</x-nav-link>
                        
                        <!-- PAYROLL (With Notification) -->
                        <x-nav-link :href="route('payroll.history')" :active="request()->routeIs('payroll.history')" class="text-indigo-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out border-none h-auto flex items-center relative">
                            {{ __('Payroll') }}
                            @if($pendingPayrollCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span><span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-[10px] text-white font-bold items-center justify-center">{{ $pendingPayrollCount }}</span></span>
                            @endif
                        </x-nav-link>    
                    @endif

                    <!-- GUARD ONLY: Kiosk Link -->
                    @if(Auth::user()->role === 'guard')
                        <x-nav-link :href="route('attendance.scanPage')" target="_blank" :active="request()->routeIs('attendance.scanPage')" 
                            class="text-indigo-100 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out border-none h-auto flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            {{ __('Launch Kiosk') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center pl-2 pr-4 py-1.5 border border-indigo-500/30 text-sm leading-4 font-medium rounded-full text-indigo-100 bg-indigo-800/50 hover:bg-indigo-700 hover:text-white focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div class="me-3">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-9 w-9 rounded-full object-cover border-2 border-indigo-400">
                                @else
                                    <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold text-white border-2 border-indigo-400">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="flex flex-col items-start text-left">
                                <span class="font-bold leading-none">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] text-indigo-300 font-medium uppercase tracking-wider mt-0.5">{{ Auth::user()->employee->position ?? 'Administrator' }}</span>
                            </div>
                            <div class="ms-3 text-indigo-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 text-xs text-gray-400">Manage Account</div>
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile Settings') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="text-red-600 hover:bg-red-50" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-indigo-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-indigo-100 hover:bg-indigo-800 hover:text-white">{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('leaves.index')" :active="request()->routeIs('leaves.index')" class="text-indigo-100 hover:bg-indigo-800 hover:text-white">{{ __('Leaves') }}</x-responsive-nav-link>
            @if(Auth::user()->role === 'admin')
                @php $pendingLeavesCount = \App\Models\LeaveRequest::where('status', 'Pending')->count(); @endphp
                <div class="border-t border-indigo-800 my-2"></div>
                <div class="px-4 py-2 text-xs font-bold text-indigo-400 uppercase tracking-wider">Admin Tools</div>
                <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" class="text-indigo-100 hover:bg-indigo-800 hover:text-white">{{ __('Employees') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')" class="text-indigo-100 hover:bg-indigo-800 hover:text-white">{{ __('Students') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('leaves.manage')" :active="request()->routeIs('leaves.manage')" class="text-indigo-100 hover:bg-indigo-800 hover:text-white flex justify-between items-center">{{ __('Approvals') }} @if($pendingLeavesCount > 0) <span class="bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingLeavesCount }}</span> @endif</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" class="text-indigo-100 hover:bg-indigo-800 hover:text-white">{{ __('Attendance') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payroll.history')" :active="request()->routeIs('payroll.history')" class="text-indigo-100 hover:bg-indigo-800 hover:text-white">{{ __('Payroll') }}</x-responsive-nav-link>
            @endif
        </div>
        <div class="pt-4 pb-1 border-t border-indigo-800 bg-black/20">
            <div class="px-4 flex items-center">
                <div class="me-3">
                    @if(Auth::user()->avatar) <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-10 w-10 rounded-full object-cover border-2 border-indigo-400"> @else <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-sm font-bold text-white border-2 border-indigo-400">{{ substr(Auth::user()->name, 0, 1) }}</div> @endif
                </div>
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-indigo-300">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-indigo-200 hover:text-white hover:bg-white/5">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}"> @csrf <x-responsive-nav-link :href="route('logout')" class="text-rose-400 hover:text-rose-300 hover:bg-white/5" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link> </form>
            </div>
        </div>
    </div>
</nav>