<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('students.index') }}" class="p-2 rounded-full bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Enroll New Student') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-rose-50 dark:bg-rose-900/30 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-rose-800 dark:text-rose-200">Please fix the following errors:</h3>
                            <ul class="mt-2 list-disc list-inside text-sm text-rose-700 dark:text-rose-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-slate-700 p-8">
                
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Student ID -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Student ID</label>
                            <input type="text" name="student_id" value="{{ old('student_id') }}" placeholder="e.g. PCIS00059" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Full Name (THE FIX IS HERE) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Student Name" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="student@pcis.edu.ph" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>

                        <!-- Grade Level -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Grade Level</label>
                            <input type="text" name="grade_level" value="{{ old('grade_level') }}" placeholder="e.g. MYP 2 (YEAR 7)" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Section -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Section</label>
                            <input type="text" name="section" value="{{ old('section') }}" placeholder="e.g. LEXICON LIONS" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Guardian Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guardian Name</label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Guardian Contact -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guardian Contact</label>
                            <input type="text" name="guardian_contact" value="{{ old('guardian_contact') }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-700">
                        <a href="{{ route('students.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-lg font-bold text-sm hover:bg-gray-300 dark:hover:bg-slate-600 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm shadow-md transition transform hover:-translate-y-0.5">
                            Enroll Student
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>