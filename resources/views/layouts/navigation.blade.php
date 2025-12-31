<nav x-data="{ open: false }" class="bg-indigo-900 border-b border-indigo-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    <!-- 1. DASHBOARD (For Everyone) -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-200">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- 2. LEAVES (For Everyone) -->
                    <x-nav-link :href="route('leaves.index')" :active="request()->routeIs('leaves.index')" class="text-white hover:text-gray-200">
                        {{ __('Leaves') }}
                    </x-nav-link>

                    <!-- 3. ADMIN ONLY LINKS -->
                    @if(Auth::user()->role === 'admin')
                        
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" class="text-white hover:text-gray-200">
                            {{ __('Employees') }}
                        </x-nav-link>

                        <x-nav-link :href="route('leaves.manage')" :active="request()->routeIs('leaves.manage')" class="text-white hover:text-gray-200">
                            {{ __('HR Approval') }}
                        </x-nav-link>

                    @endif

                </div>
            </div>

            <!-- Settings Dropdown (Top Right) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-900 hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                            
                            <!-- AVATAR DISPLAY LOGIC -->
                            <div class="me-2">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-8 w-8 rounded-full object-cover border-2 border-indigo-400">
                                @else
                                    <!-- Default Initials if no image -->
                                    <div class="h-8 w-8 rounded-full bg-indigo-700 flex items-center justify-center text-xs font-bold text-white border-2 border-indigo-500">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile Menu Button) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:text-white hover:bg-indigo-800 focus:outline-none focus:bg-indigo-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-indigo-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('leaves.index')" :active="request()->routeIs('leaves.index')" class="text-white">
                {{ __('Leaves') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" class="text-white">
                    {{ __('Employees') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('leaves.manage')" :active="request()->routeIs('leaves.manage')" class="text-white">
                    {{ __('HR Approval') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-indigo-700">
            <div class="px-4 flex items-center">
                <!-- Mobile Avatar -->
                <div class="me-3">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-10 w-10 rounded-full object-cover border-2 border-indigo-400">
                    @else
                        <div class="h-10 w-10 rounded-full bg-indigo-700 flex items-center justify-center text-sm font-bold text-white border-2 border-indigo-500">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-200 hover:text-white">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="text-gray-200 hover:text-white"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>