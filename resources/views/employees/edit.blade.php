<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="text-indigo-200 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Employee: <span class="text-indigo-200">{{ $employee->user->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                
                <!-- Header Band -->
                <div class="bg-gradient-to-r from-amber-50 to-white px-8 py-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-amber-800">Update Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Modify personal details and employment terms.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT') <!-- Important for Updates -->
                        
                        <!-- Row 1: Identity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ $employee->user->name }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ $employee->user->email }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                        </div>

                        <!-- Row 2: Job Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Job Position</label>
                                <input type="text" name="position" value="{{ $employee->position }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Basic Salary</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" step="0.01" name="salary" value="{{ $employee->basic_salary }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 pl-7 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date Joined</label>
                                <input type="date" name="joined_date" value="{{ $employee->created_at->format('Y-m-d') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">Cancel</a>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-lg font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>