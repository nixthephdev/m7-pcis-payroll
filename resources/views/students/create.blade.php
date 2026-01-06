<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Enroll New Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('students.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Student ID -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Student ID</label>
                            <input type="text" name="student_id" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="e.g. PCIS00059">
                        </div>

                        <!-- Name & Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="Student Name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" name="email" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="student@pcis.edu.ph">
                            </div>
                        </div>

                        <!-- Grade & Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Grade Level</label>
                                <input type="text" name="grade_level" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="e.g. MYP 2 (YEAR 7)">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Section</label>
                                <input type="text" name="section" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white" placeholder="e.g. LEXICON LIONS">
                            </div>
                        </div>

                        <!-- Guardian Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Guardian Name</label>
                                <input type="text" name="guardian_name" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Guardian Contact</label>
                                <input type="text" name="guardian_contact" required class="w-full border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white">
                            </div>
                        </div>

                        <!-- Password
                        <div> rounded-lg
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Default Password</label>
                            <input type="text" name="password" value="student123" readonly class="w-full rounded-lg border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-400">
                        </div> -->

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md">Enroll Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>