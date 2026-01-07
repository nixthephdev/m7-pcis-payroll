<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Attendance Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- TABS -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="bg-white dark:bg-slate-800 p-1 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 inline-flex">
                    <a href="{{ route('attendance.index', ['type' => 'employee']) }}" 
                       class="px-6 py-2 rounded-md text-sm font-bold transition {{ $type === 'employee' ? 'bg-indigo-600 text-white shadow' : 'text-gray-500 hover:text-indigo-600' }}">
                        Employees
                    </a>
                    <a href="{{ route('attendance.index', ['type' => 'student']) }}" 
                       class="px-6 py-2 rounded-md text-sm font-bold transition {{ $type === 'student' ? 'bg-emerald-600 text-white shadow' : 'text-gray-500 hover:text-emerald-600' }}">
                        Students
                    </a>
                </div>
            </div>

            <!-- GENERATE REPORT PANEL (Only for Employees) -->
            @if($type === 'employee')
                <div class="bg-indigo-50 dark:bg-slate-800 border border-indigo-100 dark:border-slate-700 rounded-xl p-6 mb-8 shadow-sm">
                    <h3 class="font-bold text-indigo-900 dark:text-indigo-300 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Generate Individual Report
                    </h3>
                    
                    <form action="{{ route('attendance.report') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        
                        <!-- Select Employee -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Select Employee</label>
                            <select name="employee_id" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" required>
                                <option value="">-- Choose Employee --</option>
                                @foreach(\App\Models\Employee::with('user')->get() as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" required>
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" required>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-md flex justify-center items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                Download PDF
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- TABLE -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-gray-700 dark:text-white">
                        {{ ucfirst($type) }} Attendance Logs
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold">
                            <tr>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Time In</th>
                                <th class="px-6 py-4">Time Out</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($attendances as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                
                                <!-- NAME COLUMN -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($type === 'student')
                                            <!-- Student Display -->
                                            <div class="h-8 w-8 rounded-full bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-300 font-bold text-xs mr-3">
                                                {{ substr($log->attendable->full_name ?? 'S', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-white">{{ $log->attendable->full_name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-400">{{ $log->attendable->student_id ?? 'N/A' }}</p>
                                            </div>
                                        @else
                                            <!-- Employee Display -->
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xs mr-3">
                                                {{ substr($log->attendable->user->name ?? 'E', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-white">{{ $log->attendable->user->name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-400">{{ $log->attendable->position ?? 'Staff' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                                
                                <td class="px-6 py-4 font-mono text-emerald-600 dark:text-emerald-400 font-bold">
                                    {{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}
                                </td>
                                
                                <td class="px-6 py-4 font-mono text-gray-500 dark:text-gray-400">
                                    @if($log->time_out)
                                        {{ \Carbon\Carbon::parse($log->time_out)->format('h:i A') }}
                                    @else
                                        <span class="text-gray-300 italic">--:--</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $log->status == 'Late' ? 'bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-300' : 'bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-300' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($attendances->isEmpty())
                        <div class="text-center py-12 text-gray-400 dark:text-gray-500">
                            No attendance records found for {{ ucfirst($type) }}s.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>