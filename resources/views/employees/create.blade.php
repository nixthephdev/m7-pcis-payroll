<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Card Wrapper (Same style as Profile) -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    
                    <!-- Header Section -->
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Employee Information') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Enter the personal and employment details to create a new account.") }}
                        </p>
                    </header>

                    <!-- Form -->
                    <form method="post" action="{{ route('employees.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" placeholder="e.g. Juan Dela Cruz" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" placeholder="email@school.edu" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Position -->
                        <div>
                            <x-input-label for="position" :value="__('Job Position')" />
                            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" required placeholder="e.g. Teacher" />
                            <x-input-error class="mt-2" :messages="$errors->get('position')" />
                        </div>

                        <!-- Salary -->
                        <div>
                            <x-input-label for="salary" :value="__('Basic Monthly Salary')" />
                            <x-text-input id="salary" name="salary" type="number" step="0.01" class="mt-1 block w-full" required placeholder="0.00" />
                            <x-input-error class="mt-2" :messages="$errors->get('salary')" />
                        </div>

                        <!-- Password (Read Only) -->
                        <div>
                            <x-input-label for="password" :value="__('Temporary Password')" />
                            <x-text-input id="password" name="password" type="text" class="mt-1 block w-full bg-gray-100 text-gray-500" value="password123" readonly />
                            <p class="mt-2 text-sm text-gray-600">
                                {{ __('Default password is set to "password123". The employee can change this later.') }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Employee') }}</x-primary-button>

                            <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>