<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Leave Approvals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="font-bold">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">All Leave Requests</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-5 py-4 border-b dark:border-slate-700">Employee</th>
                                <th class="px-5 py-4 border-b dark:border-slate-700">Leave Details</th>
                                <th class="px-5 py-4 border-b dark:border-slate-700">Period / Duration</th>
                                <th class="px-5 py-4 border-b dark:border-slate-700 text-center">Head Status</th>
                                <th class="px-5 py-4 border-b dark:border-slate-700 text-center">HR Action / Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($leaves as $leave)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">

                                {{-- Employee --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ optional($leave->employee->user)->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $leave->employee->position ?? 'N/A' }}</p>
                                </td>

                                {{-- Leave Type + Reason --}}
                                <td class="px-5 py-4">
                                    @php
                                        $typeColors = [
                                            'Vacation Leave'    => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                            'Sick Leave'        => 'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
                                            'Birthday Leave'    => 'bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
                                            'Solo Parent Leave' => 'bg-teal-50 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300',
                                            'Incentive Hours'   => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                            'Maternity Leave'   => 'bg-pink-50 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300',
                                            'Paternity Leave'   => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-300',
                                            'Bereavement Leave' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            'Official Business' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
                                            'Emergency Leave'   => 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
                                        ];
                                        $color = $typeColors[$leave->leave_type] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-bold {{ $color }} mb-1">{{ $leave->leave_type }}</span>
                                    <p class="italic text-gray-500 dark:text-gray-400 text-xs">"{{ Str::limit($leave->reason, 45) }}"</p>
                                </td>

                                {{-- Period / Duration --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($leave->leave_type === 'Incentive Hours')
                                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ \Carbon\Carbon::parse($leave->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($leave->end_time)->format('h:i A') }}
                                        </p>
                                        <p class="text-xs font-bold text-amber-600 dark:text-amber-400">{{ $leave->total_hours }} hrs</p>
                                    @else
                                        <p class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} day(s)
                                        </p>
                                    @endif
                                </td>

                                {{-- Head/Coordinator Status --}}
                                <td class="px-5 py-4 text-center">
                                    @if(!$leave->employee->supervisor_id)
                                        <span class="text-xs text-gray-400 italic">Direct to HR</span>
                                    @elseif($leave->supervisor_status === 'Approved')
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Endorsed</span>
                                    @elseif($leave->supervisor_status === 'Rejected')
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300">Rejected</span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300">Pending Review</span>
                                    @endif
                                </td>

                                {{-- HR Action / Final Status --}}
                                <td class="px-5 py-4 text-center">
                                    @if($leave->status === 'Pending')
                                        @if($leave->employee->supervisor_id && $leave->supervisor_status === 'Pending')
                                            <div class="flex flex-col items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Awaiting Head</span>
                                            </div>
                                        @else
                                            {{-- HR Approve/Reject with Paid toggle --}}
                                            <div class="flex flex-col items-center gap-2" x-data="{ paid: true }">
                                                <label class="flex items-center gap-1.5 cursor-pointer text-xs text-gray-600 dark:text-gray-400">
                                                    <input type="checkbox" x-model="paid" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                                    <span>Paid Leave</span>
                                                </label>
                                                <div class="flex gap-2">
                                                    <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Approved">
                                                        <input type="hidden" name="is_paid" x-bind:value="paid ? '1' : ''">
                                                        <button class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-800 transition" title="Approve">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Rejected">
                                                        <button class="p-2 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-200 dark:hover:bg-rose-800 transition" title="Reject">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="flex flex-col items-center gap-1">
                                            @if($leave->status === 'Approved')
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Approved</span>
                                                @if($leave->is_paid)
                                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">Paid</span>
                                                @else
                                                    <span class="text-[10px] font-bold text-gray-400">Unpaid</span>
                                                @endif
                                            @else
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300">Rejected</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-12 text-gray-400 dark:text-gray-500">No leave requests found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
