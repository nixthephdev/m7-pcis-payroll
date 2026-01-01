<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Student Management') }}
            </h2>
            <a href="{{ route('students.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Enroll Student
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-slate-700/50 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Student</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Grade & Section</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Guardian</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($students as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition group">
                                
                                <!-- Student Name & Edit Icon -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <!-- Avatar -->
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-sm mr-3 flex-shrink-0">
                                            {{ substr($student->user->name, 0, 1) }}
                                        </div>
                                        
                                        <!-- Name & ID -->
                                        <div>
                                            <div class="font-bold text-gray-800 dark:text-gray-200">{{ $student->user->name }}</div>
                                            <div class="text-xs text-gray-400 font-mono">{{ $student->student_id }}</div>
                                        </div>

                                        <!-- Edit Pencil (ALWAYS VISIBLE) -->
                                        <a href="{{ route('students.edit', $student->id) }}" class="ml-3 text-gray-300 hover:text-amber-500 dark:text-slate-600 dark:hover:text-amber-400 transition p-1" title="Edit Student">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded text-xs font-bold border border-blue-100 dark:border-blue-800">
                                        {{ $student->grade_level }} - {{ $student->section }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-bold text-gray-700 dark:text-gray-300">{{ $student->guardian_name }}</div>
                                    <div class="text-gray-400">{{ $student->guardian_contact }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('students.idcard', $student->id) }}" target="_blank" class="p-2 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition inline-block" title="View ID">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```