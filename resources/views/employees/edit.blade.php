<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="text-indigo-200 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                Edit Employee: <span class="text-indigo-600 dark:text-indigo-400">{{ $employee->user->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                
                <div class="bg-gradient-to-r from-amber-50 to-white dark:from-amber-900/20 dark:to-slate-800 px-8 py-6 border-b border-gray-100 dark:border-slate-600">
                    <h3 class="text-lg font-bold text-amber-800 dark:text-amber-500">Update Information</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Modify personal details and employment terms.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- ID NUMBER -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Employee ID Number</label>
                            <input type="text" name="employee_code" value="{{ $employee->employee_code }}" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                        </div>

                        <!-- Row 1: Identity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ $employee->user->name }}" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ $employee->user->email }}" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                        </div>

                        <!-- Row 2: Job Position & Schedule (CONVERTED TO TAILWIND) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Job Position -->
<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Position</label>
    <input 
        type="text" 
        name="position" 
        value="{{ old('position', $employee->position) }}" 
        placeholder="e.g. Software Engineer" 
        required 
        class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm"
    >
</div>

                            <!-- Schedule Dropdown -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assigned Schedule</label>
                                <select name="schedule_id" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                    <option value="" disabled>Select a Schedule</option>
                                    @foreach($schedules as $schedule)
                                        <option value="{{ $schedule->id }}" {{ (isset($employee) && $employee->schedule_id == $schedule->id) ? 'selected' : '' }}>
                                            {{ $schedule->name }} 
                                            @if($schedule->is_flexible)
                                                (Flexible Time)
                                            @else
                                                ({{ \Carbon\Carbon::parse($schedule->time_in)->format('g:i A') }} - 
                                                 {{ \Carbon\Carbon::parse($schedule->time_out)->format('g:i A') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition">Cancel</a>
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