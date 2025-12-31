<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="text-indigo-200 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Onboard New Employee') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                
                <!-- Header Band -->
                <div class="bg-gradient-to-r from-indigo-50 to-white px-8 py-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-indigo-900">Employee Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Please fill in the required information to create a new account.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('employees.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Row 1: Identity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm" placeholder="e.g. Juan Dela Cruz">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm" placeholder="email@pcis.edu.ph">
                            </div>
                        </div>

                        <!-- Row 2: Job Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Job Position</label>
                                <input type="text" name="position" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm" placeholder="e.g Teacher">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Basic Monthly Salary</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" step="0.01" name="salary" required class="w-full rounded-lg border-gray-300 bg-gray-50 pl-7 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Security -->
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                            <label class="block text-xs font-bold text-yellow-700 uppercase tracking-wider mb-2">Default Password</label>
                            <div class="flex items-center gap-3">
                                <input type="text" name="password" value="password123" readonly class="w-full rounded-lg border-gray-200 bg-white text-gray-500 cursor-not-allowed text-sm">
                                <span class="text-xs text-yellow-600 italic">User can change this later.</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                Create Account
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>