<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Attendance Logs') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        showModal: false,
        showPdfModal: false,
        editUrl: '',
        currentTimeIn: '',
        currentTimeOut: '',
        currentStatus: '',
        currentTardy: 0,
        currentOTMins: 0,
        currentOTType: '',
        openEdit(id, timeIn, timeOut, status, tardy, otMins, otType) {
            this.editUrl = '/attendance/' + id;
            this.currentTimeIn  = timeIn;
            this.currentTimeOut = timeOut;
            this.currentStatus  = status;
            this.currentTardy   = tardy;
            this.currentOTMins  = otMins;
            this.currentOTType  = otType;
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <!-- TABS + EXPORT -->
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
                @if($type === 'employee')
                <div class="flex items-center gap-3">
                    <a href="{{ route('attendance.export') }}"
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Export CSV
                    </a>
                    <button @click="showPdfModal = true"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Download PDF
                    </button>
                </div>
                @endif
            </div>

            <!-- SEARCH & DATE FILTER -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 mb-6">
                <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-col md:flex-row gap-3 items-end">
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Search Name / Position</label>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="e.g. Juan Dela Cruz"
                               class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">From</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                               class="rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">To</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                               class="rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm shadow-sm">
                    </div>

                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow transition whitespace-nowrap">
                        Search
                    </button>

                    @if($search || $dateFrom !== \Carbon\Carbon::now()->subDays(29)->format('Y-m-d') || $dateTo !== \Carbon\Carbon::now()->format('Y-m-d'))
                    <a href="{{ route('attendance.index', ['type' => $type]) }}"
                       class="px-5 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-600 dark:text-gray-300 text-sm font-bold rounded-lg transition whitespace-nowrap">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            <!-- TABLE -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-700 dark:text-white">{{ ucfirst($type) }} Attendance Logs</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $attendances->total() }} record(s) &bull;
                        {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold">
                            <tr>
                                <th class="px-5 py-4">Name</th>
                                <th class="px-5 py-4">Date</th>
                                <th class="px-5 py-4">Time In</th>
                                <th class="px-5 py-4">Time Out</th>
                                <th class="px-5 py-4">Duration*</th>
                                <th class="px-5 py-4 text-center">Status</th>
                                <th class="px-5 py-4 text-center">Tardy</th>
                                @if($type !== 'student')<th class="px-5 py-4 text-center">OT</th>@endif
                                <th class="px-5 py-4 text-center">Edit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($attendances as $log)
                            @php
                                $isAbsent   = $log->status === 'Absent';
                                $in         = (!$isAbsent && $log->time_in)  ? \Carbon\Carbon::parse($log->time_in)  : null;
                                $out        = (!$isAbsent && $log->time_out) ? \Carbon\Carbon::parse($log->time_out) : null;
                                if ($in && $out && $out->lt($in)) { $out->addDay(); }
                                $workedMins = ($in && $out) ? $in->diffInMinutes($out) : 0;
                                $netMins    = $workedMins > 60 ? $workedMins - 60 : $workedMins;
                                $durLabel   = ($in && $out) ? floor($netMins/60).'h '.($netMins%60).'m' : '--';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-5 py-4">
                                    @if($type === 'student')
                                        <p class="font-bold text-gray-800 dark:text-white">{{ $log->attendable->full_name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->attendable->student_id ?? '' }}</p>
                                    @else
                                        <p class="font-bold text-gray-800 dark:text-white">{{ $log->attendable->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->attendable->position ?? '' }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                                <td class="px-5 py-4 font-mono text-emerald-600 dark:text-emerald-400 font-bold">
                                    {!! $in ? $in->format('h:i A') : '<span class="text-gray-300 dark:text-gray-600 italic">--:--</span>' !!}
                                </td>
                                <td class="px-5 py-4 font-mono text-gray-500 dark:text-gray-400">
                                    {!! $out ? $out->format('h:i A') : '<span class="text-gray-300 dark:text-gray-600 italic">--:--</span>' !!}
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-500">{{ $durLabel }}</td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'Present'  => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                            'Late'     => 'bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-300',
                                            'Absent'   => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                            'Half Day' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $statusColors[$log->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center text-xs">
                                    @if($log->tardy_minutes > 0)
                                        <span class="font-bold text-rose-600 dark:text-rose-400">{{ $log->tardy_minutes }}m</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                @if($type !== 'student')
                                <td class="px-5 py-4 text-center text-xs">
                                    @if($log->overtime_minutes > 0)
                                        <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $log->overtime_minutes }}m</span>
                                        @if($log->overtime_type)
                                            <span class="block text-[10px] text-gray-400">{{ $log->overtime_type }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                @endif
                                <td class="px-5 py-4 text-center">
                                    <button @click="openEdit(
                                        {{ $log->id }},
                                        '{{ $in ? $in->format('H:i') : '' }}',
                                        '{{ $out ? $out->format('H:i') : '' }}',
                                        '{{ $log->status }}',
                                        {{ $log->tardy_minutes }},
                                        {{ $log->overtime_minutes }},
                                        '{{ $log->overtime_type ?? '' }}'
                                    )" class="p-2 bg-gray-100 dark:bg-slate-700 rounded-lg text-indigo-600 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition">
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
                        <div class="text-center py-12 text-gray-400 dark:text-gray-500">No attendance records found.</div>
                    @endif
                </div>
                <p class="px-6 py-2 text-xs text-gray-400 dark:text-gray-500 border-t dark:border-slate-700">* Duration excludes 1-hour lunch break.</p>

                @if($attendances->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
                    {{ $attendances->links() }}
                </div>
                @endif
            </div>

            <!-- PDF REPORT MODAL -->
            <div x-show="showPdfModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div x-show="showPdfModal" x-transition.opacity class="fixed inset-0 bg-gray-900 opacity-75" @click="showPdfModal = false"></div>
                    <div x-show="showPdfModal"
                         x-transition:enter="ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="relative bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-md z-10">

                        <form action="{{ route('attendance.report') }}" method="POST">
                            @csrf
                            <div class="px-6 pt-6 pb-4">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Download PDF Report</h3>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Generate an individual attendance report</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Employee</label>
                                        <select name="employee_id" required
                                                class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm shadow-sm">
                                            <option value="">-- Select Employee --</option>
                                            @foreach(\App\Models\Employee::with('user')->orderBy('id')->get() as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->user->name ?? 'N/A' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Start Date</label>
                                            <input type="date" name="start_date" required
                                                   value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                                                   class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">End Date</label>
                                            <input type="date" name="end_date" required
                                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                   class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white text-sm shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-slate-700/50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                                <button type="submit"
                                        class="px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 shadow flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    Download PDF
                                </button>
                                <button type="button" @click="showPdfModal = false"
                                        class="px-5 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- EDIT MODAL -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 opacity-75" @click="showModal = false"></div>
                    <div x-show="showModal"
                         x-transition:enter="ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="relative bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg z-10">

                        <form :action="editUrl" method="POST">
                            @csrf @method('PUT')

                            <div class="px-6 pt-6 pb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Edit Attendance Record</h3>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Time In</label>
                                        <input type="time" name="time_in" x-model="currentTimeIn"
                                               class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Time Out</label>
                                        <input type="time" name="time_out" x-model="currentTimeOut"
                                               class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                        <select name="status" x-model="currentStatus"
                                                class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                            <option value="Present">Present</option>
                                            <option value="Late">Late</option>
                                            <option value="Half Day">Half Day</option>
                                            <option value="Absent">Absent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tardy (minutes)</label>
                                        <input type="number" name="tardy_minutes" x-model="currentTardy" min="0"
                                               class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                    </div>
                                </div>

                                @if($type !== 'student')
                                <div class="border-t dark:border-slate-700 pt-4">
                                    <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-3">HR Overtime Entry</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">OT Minutes</label>
                                            <input type="number" name="overtime_minutes" x-model="currentOTMins" min="0"
                                                   class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                                                   placeholder="0">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">OT Type</label>
                                            <select name="overtime_type" x-model="currentOTType"
                                                    class="w-full rounded-lg border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                                <option value="">-- None --</option>
                                                <option value="Regular Day">Regular Day</option>
                                                <option value="Regular Holiday">Regular Holiday</option>
                                                <option value="Rest Day / Special Holiday">Rest Day / Special Holiday</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="bg-gray-50 dark:bg-slate-700/50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                                <button type="submit"
                                        class="px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 shadow">
                                    Save Changes
                                </button>
                                <button type="button" @click="showModal = false"
                                        class="px-5 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
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
