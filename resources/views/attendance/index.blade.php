<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Attendance Logs') }}
        </h2>
    </x-slot>

    <!-- Alpine.js Data for Modal -->
    <div class="py-12" x-data="{ 
        showModal: false, 
        editUrl: '', 
        currentTimeIn: '', 
        currentTimeOut: '', 
        currentStatus: '',
        openEdit(id, timeIn, timeOut, status) {
            this.editUrl = '/attendance/' + id;
            this.currentTimeIn = timeIn;
            this.currentTimeOut = timeOut;
            this.currentStatus = status;
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- NOTIFICATION -->
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

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
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Select Employee</label>
                            <select name="employee_id" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" required>
                                <option value="">-- Choose Employee --</option>
                                @foreach(\App\Models\Employee::with('user')->get() as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm" required>
                        </div>
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
                                <th class="px-6 py-4 text-center">Actions</th> <!-- NEW COLUMN -->
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($attendances as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                
                                <!-- NAME -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($type === 'student')
                                            <div class="h-8 w-8 rounded-full bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-300 font-bold text-xs mr-3">
                                                {{ substr($log->attendable->full_name ?? 'S', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-white">{{ $log->attendable->full_name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-400">{{ $log->attendable->student_id ?? 'N/A' }}</p>
                                            </div>
                                        @else
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

                                <!-- ACTIONS (EDIT BUTTON) -->
                                <td class="px-6 py-4 text-center">
                                    <button @click="openEdit(
                                        {{ $log->id }}, 
                                        '{{ \Carbon\Carbon::parse($log->time_in)->format('H:i') }}', 
                                        '{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '' }}', 
                                        '{{ $log->status }}'
                                    )" class="p-2 bg-gray-100 dark:bg-slate-700 rounded-lg text-indigo-600 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition" title="Edit Record">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($attendances->isEmpty())
                        <div class="text-center py-12 text-gray-400 dark:text-gray-500">
                            No attendance records found.
                        </div>
                    @endif
                </div>
            </div>

            <!-- EDIT MODAL (POPUP) -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    
                    <!-- Overlay -->
                    <div x-show="showModal" x-transition.opacity class="fixed inset-0 transition-opacity" @click="showModal = false">
                        <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                    </div>

                    <!-- Modal Panel -->
                    <div x-show="showModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        
                        <form :action="editUrl" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Edit Attendance Log</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Time In -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Time In</label>
                                        <input type="time" name="time_in" x-model="currentTimeIn" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" required>
                                    </div>

                                    <!-- Time Out -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Time Out</label>
                                        <input type="time" name="time_out" x-model="currentTimeOut" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                    </div>

                                    <!-- Status -->
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                        <select name="status" x-model="currentStatus" class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                            <option value="Present">Present</option>
                                            <option value="Late">Late</option>
                                            <option value="Half Day">Half Day</option>
                                            <option value="Absent">Absent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Save Changes
                                </button>
                                <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>