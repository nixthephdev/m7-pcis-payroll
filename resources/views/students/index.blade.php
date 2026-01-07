<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Student Management') }}
            </h2>
            <a href="{{ route('students.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
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

            <!-- STUDENT COUNTER & INFO -->
            <div class="mb-4 flex items-center gap-2 px-2">
                <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                    Total Enrolled Students: <span class="text-gray-900 dark:text-white font-bold text-lg ml-1">{{ count($students) }}</span>
                </p>
            </div>

            <!-- TABLE CONTAINER -->
            <div class="bg-white dark:bg-[#1e293b] rounded-2xl shadow-lg border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        
                        <!-- TABLE HEADER -->
                        <thead class="bg-gray-50 dark:bg-[#0f172a]/50 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider border-b dark:border-slate-700">
                            <tr>
                                <th class="px-6 py-5">Student</th>
                                <th class="px-6 py-5">Grade & Section</th>
                                <th class="px-6 py-5">Guardian</th>
                                <th class="px-6 py-5 text-center">Actions</th>
                            </tr>
                        </thead>

                        <!-- TABLE BODY -->
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            @foreach($students as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition group">
                                
                                <!-- STUDENT NAME COLUMN -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Avatar Circle -->
                                        <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md flex-shrink-0">
                                            {{ substr($student->full_name, 0, 1) }}
                                        </div>
                                        
                                        <!-- Name & ID -->
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-800 dark:text-white text-sm">{{ $student->full_name }}</span>
                                                
                                                <!-- Edit Pencil (Next to Name) -->
                                                <a href="{{ route('students.edit', $student->id) }}" class="text-gray-400 hover:text-indigo-400 transition" title="Edit Student">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $student->student_id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- GRADE & SECTION COLUMN (Blue Pill Style) -->
                                <td class="px-6 py-4">
                                    <span class="inline-block bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 px-3 py-1.5 rounded-md text-xs font-bold uppercase tracking-wide">
                                        {{ $student->grade_level }} - {{ $student->section }}
                                    </span>
                                </td>

                                <!-- GUARDIAN COLUMN -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $student->guardian_name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $student->guardian_contact }}</div>
                                </td>

                                <!-- ACTIONS COLUMN (Purple ID Button) -->
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('students.idcard', $student->id) }}" target="_blank" class="inline-flex items-center justify-center p-2 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 border border-purple-100 dark:border-purple-800 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition shadow-sm" title="View ID Card">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
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