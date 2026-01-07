<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('students.index') }}" class="p-2 rounded-full bg-white dark:bg-slate-800 text-gray-500 hover:text-indigo-600 shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Edit Student') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-slate-700 p-8">
                
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Student ID -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Student ID</label>
                            <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full Name</label>
                            <!-- UPDATED VALUE HERE -->
                            <input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Email Address -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>

                        <!-- Grade Level -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Grade Level</label>
                            <input type="text" name="grade_level" value="{{ old('grade_level', $student->grade_level) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Section -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Section</label>
                            <input type="text" name="section" value="{{ old('section', $student->section) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Guardian Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guardian Name</label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                        <!-- Guardian Contact -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guardian Contact</label>
                            <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-700">
                        <a href="{{ route('students.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-lg font-bold text-sm hover:bg-gray-300 dark:hover:bg-slate-600 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm shadow-md transition transform hover:-translate-y-0.5">
                            Update Student
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>