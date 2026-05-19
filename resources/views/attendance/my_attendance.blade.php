<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            My Attendance
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- MONTH PICKER --}}
            <div class="flex items-center gap-4 mb-8">
                <form method="GET" action="{{ route('attendance.mine') }}" class="flex items-center gap-3">
                    <label class="text-sm font-bold text-gray-600 dark:text-gray-400">Month:</label>
                    <input type="month" name="month" value="{{ $month }}"
                           class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           onchange="this.form.submit()">
                </form>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg">
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Days Present</p>
                    <p class="text-4xl font-bold mt-1">{{ $totalPresent }}</p>
                </div>
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-5 text-white shadow-lg">
                    <p class="text-rose-100 text-xs font-bold uppercase tracking-wider">Total Tardy</p>
                    <p class="text-4xl font-bold mt-1">{{ $totalTardy }}</p>
                    <p class="text-rose-200 text-xs mt-0.5">minutes</p>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 text-white shadow-lg">
                    <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Undertime</p>
                    <p class="text-4xl font-bold mt-1">{{ $totalUndertime }}</p>
                    <p class="text-amber-200 text-xs mt-0.5">minutes</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg">
                    <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Overtime</p>
                    <p class="text-4xl font-bold mt-1">{{ $totalOvertimeMins }}</p>
                    <p class="text-indigo-200 text-xs mt-0.5">minutes</p>
                </div>
            </div>

            {{-- APPROVED LEAVES THIS MONTH --}}
            @if($approvedLeaves->isNotEmpty())
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 mb-6">
                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-2">Approved Leaves This Month</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($approvedLeaves as $lv)
                    <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-full">
                        {{ $lv->leave_type }} —
                        @if($lv->leave_type === 'Incentive Hours')
                            {{ \Carbon\Carbon::parse($lv->start_date)->format('M d') }} ({{ $lv->total_hours }}h)
                        @else
                            {{ \Carbon\Carbon::parse($lv->start_date)->format('M d') }}
                            @if($lv->start_date !== $lv->end_date)
                                – {{ \Carbon\Carbon::parse($lv->end_date)->format('M d') }}
                            @endif
                        @endif
                        @if($lv->is_paid)
                            <span class="opacity-60">(Paid)</span>
                        @else
                            <span class="opacity-60">(Unpaid)</span>
                        @endif
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ATTENDANCE TABLE --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-gray-700 dark:text-white">
                        Daily Logs — {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Day</th>
                                <th class="px-6 py-4">Time In</th>
                                <th class="px-6 py-4">Time Out</th>
                                <th class="px-6 py-4">Duration*</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Tardy</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($logs as $log)
                            @php
                                $in  = \Carbon\Carbon::parse($log->time_in);
                                $out = $log->time_out ? \Carbon\Carbon::parse($log->time_out) : null;
                                // Overnight shift: time_out may wrap to next day
                                if ($out && $out->lt($in)) { $out->addDay(); }
                                $workedMins = $out ? $in->diffInMinutes($out) : 0;
                                $isFlexible = $employee->schedule->is_flexible ?? false;
                                $netMins    = (!$isFlexible && $workedMins > 60) ? $workedMins - 60 : $workedMins;
                                $durLabel   = $out ? floor($netMins/60).'h '.($netMins%60).'m' : '--';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4 font-medium">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($log->date)->format('D') }}</td>
                                <td class="px-6 py-4 font-mono text-emerald-600 dark:text-emerald-400 font-bold">{{ $in->format('h:i A') }}</td>
                                <td class="px-6 py-4 font-mono text-gray-500 dark:text-gray-400">
                                    {{ $out ? $out->format('h:i A') : '<span class="italic text-gray-300">--:--</span>' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ $durLabel }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $sc = ['Present'=>'bg-green-100 text-green-700','Late'=>'bg-rose-100 text-rose-600','Absent'=>'bg-gray-100 text-gray-600','Half Day'=>'bg-amber-100 text-amber-700'];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $sc[$log->status] ?? 'bg-gray-100 text-gray-600' }}">{{ $log->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs">
                                    @if($log->tardy_minutes > 0)
                                        <span class="font-bold text-rose-600">{{ $log->tardy_minutes }}m</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-12 text-gray-400">No records for this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="px-6 py-2 text-xs text-gray-400 dark:text-gray-500 border-t dark:border-slate-700">* Duration excludes 1-hour lunch break (fixed schedules only).</p>
            </div>

        </div>
    </div>
</x-app-layout>
