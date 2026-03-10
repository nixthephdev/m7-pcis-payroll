<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Workspace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <p class="font-bold">Success</p>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            {{-- TODAY'S ATTENDANCE CARD --}}
            @if($employee)
            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Live Clock + Status --}}
                <div class="md:col-span-1 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10 w-28 h-28 -mr-6 -mt-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Today</p>
                    <p class="text-slate-300 text-sm font-medium mb-3" id="live-date"></p>
                    <p class="text-4xl font-bold tabular-nums tracking-tight" id="live-clock">--:--:--</p>
                    <div class="mt-4">
                        @if($todayAttendance)
                            @if($todayAttendance->status === 'Late')
                                <span class="inline-flex items-center gap-1.5 bg-amber-500/20 border border-amber-500/40 text-amber-300 text-xs font-bold px-3 py-1 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span> Late
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-xs font-bold px-3 py-1 rounded-full">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Present
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-slate-600/50 border border-slate-500/40 text-slate-400 text-xs font-bold px-3 py-1 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span> Not Yet Clocked In
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Time In Card --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Time In</p>
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        </div>
                    </div>
                    @if($todayAttendance)
                        <p class="text-3xl font-bold text-gray-800 dark:text-white tabular-nums">{{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i') }}<span class="text-lg text-gray-400 ml-1">{{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('A') }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">Scheduled: {{ $employee->schedule ? \Carbon\Carbon::parse($employee->schedule->time_in)->format('h:i A') : 'N/A' }}</p>
                    @else
                        <p class="text-3xl font-bold text-gray-300 dark:text-slate-600">--:--</p>
                        <p class="text-xs text-gray-400 mt-1">Scheduled: {{ $employee->schedule ? \Carbon\Carbon::parse($employee->schedule->time_in)->format('h:i A') : 'N/A' }}</p>
                    @endif
                </div>

                {{-- Time Out Card --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Time Out</p>
                        <div class="p-2 bg-rose-50 dark:bg-rose-900/30 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                    </div>
                    @if($todayAttendance && $todayAttendance->time_out)
                        <p class="text-3xl font-bold text-gray-800 dark:text-white tabular-nums">{{ \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i') }}<span class="text-lg text-gray-400 ml-1">{{ \Carbon\Carbon::parse($todayAttendance->time_out)->format('A') }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">Scheduled: {{ $employee->schedule ? \Carbon\Carbon::parse($employee->schedule->time_out)->format('h:i A') : 'N/A' }}</p>
                    @else
                        <p class="text-3xl font-bold text-gray-300 dark:text-slate-600">--:--</p>
                        <p class="text-xs text-gray-400 mt-1">Scheduled: {{ $employee->schedule ? \Carbon\Carbon::parse($employee->schedule->time_out)->format('h:i A') : 'N/A' }}</p>
                    @endif
                </div>

            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="space-y-8">

                    @if(Auth::user()->role === 'guard')
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg p-6 text-white border border-slate-700 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2 flex items-center gap-2"><span class="bg-rose-500 h-2 w-2 rounded-full animate-pulse"></span> Security Terminal</h3>
                        <p class="text-slate-400 text-sm mb-6">Launch the QR Scanner for student/employee attendance.</p>
                        <a href="{{ route('attendance.scanPage') }}" target="_blank" class="block w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-center font-bold shadow-md transition transform hover:scale-[1.02]">Launch Scanner</a>
                    </div>
                    @endif


                    @php
                        $employee = Auth::user()->employee;
                        if($employee) {
                            $daysWorked = \App\Models\Attendance::where('attendable_id', $employee->id)
                                            ->where('attendable_type', 'App\Models\Employee')
                                            ->whereMonth('date', \Carbon\Carbon::now()->month)
                                            ->count();
                            $estimatedPay = ($employee->basic_salary / 22) * $daysWorked;
                        } else {
                            $daysWorked = 0;
                            $estimatedPay = 0;
                        }
                    @endphp

                    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
                        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-white opacity-10"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider">Position</p>
                                    <p class="font-bold text-lg">{{ $employee->position ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-between items-end">
                                <div>
                                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider">Est. Gross (Month)</p>
                                    <p class="text-3xl font-bold">₱{{ number_format($estimatedPay, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider">Days</p>
                                    <p class="text-xl font-bold">{{ $daysWorked }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Payroll History</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                                <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4 border-b dark:border-slate-700">Pay Period</th>
                                        <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Gross</th>
                                        <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Deductions</th>
                                        <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Net Pay</th>
                                        <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Status</th>
                                        <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Slip</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                                    @if($employee)
                                        @php $payrolls = \App\Models\Payroll::where('employee_id', $employee->id)->latest()->get(); @endphp
                                        @foreach($payrolls as $payroll)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($payroll->pay_date)->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $payroll->period ?? 'Standard' }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-right">₱{{ number_format($payroll->gross_salary, 2) }}</td>
                                            <td class="px-6 py-4 text-right text-rose-500 dark:text-rose-400">-₱{{ number_format($payroll->deductions, 2) }}</td>
                                            <td class="px-6 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($payroll->net_salary, 2) }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if($payroll->status == 'Paid')
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Paid</span>
                                                @else
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('payroll.download', $payroll->id) }}" class="inline-block p-2 bg-gray-100 dark:bg-slate-700 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 hover:text-indigo-600 dark:hover:text-indigo-300 transition" title="Download PDF">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if(!$employee || (isset($payrolls) && $payrolls->isEmpty()))
                                <div class="text-center py-12 text-gray-400 dark:text-gray-500">No payroll records found.</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- ATTENDANCE HISTORY --}}
            @if($employee && $recentAttendance->isNotEmpty())
            <div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Attendance History</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Last 30 records</p>
                    </div>
                    <div class="flex gap-3 text-xs font-bold">
                        <span class="px-2 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                            Present: {{ $recentAttendance->where('status', 'Present')->count() }}
                        </span>
                        <span class="px-2 py-1 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                            Late: {{ $recentAttendance->where('status', 'Late')->count() }}
                        </span>
                        <span class="px-2 py-1 rounded-full bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300">
                            Absent: {{ $recentAttendance->where('status', 'Absent')->count() }}
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Date</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Day</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Time In</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Time Out</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Hours</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($recentAttendance as $log)
                            @php
                                $hours = '--';
                                if ($log->time_in && $log->time_out) {
                                    $diff = \Carbon\Carbon::parse($log->time_in)->diffInMinutes(\Carbon\Carbon::parse($log->time_out));
                                    $hours = floor($diff / 60) . 'h ' . ($diff % 60) . 'm';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition {{ \Carbon\Carbon::parse($log->date)->isToday() ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
                                <td class="px-6 py-3">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</span>
                                    @if(\Carbon\Carbon::parse($log->date)->isToday())
                                        <span class="ml-2 text-xs bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 px-1.5 py-0.5 rounded font-bold">Today</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($log->date)->format('D') }}</td>
                                <td class="px-6 py-3 font-mono text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}</td>
                                <td class="px-6 py-3 font-mono text-gray-700 dark:text-gray-300">
                                    {{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : '--' }}
                                </td>
                                <td class="px-6 py-3 text-gray-500 dark:text-gray-400 font-mono text-xs">{{ $hours }}</td>
                                <td class="px-6 py-3 text-center">
                                    @if($log->status === 'Present')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">Present</span>
                                    @elseif($log->status === 'Late')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">Late</span>
                                    @elseif($log->status === 'Absent')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300">Absent</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400">{{ $log->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>

<script>
    function updateClock() {
        const now = new Date();
        const timeEl = document.getElementById('live-clock');
        const dateEl = document.getElementById('live-date');
        if (!timeEl) return;

        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');

        timeEl.textContent = `${h}:${m}:${s}`;
        dateEl.textContent = `${days[now.getDay()]}, ${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
</x-app-layout>