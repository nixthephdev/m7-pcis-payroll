<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('My Leave Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="font-bold">{{ session('message') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="font-bold">{{ session('error') }}</p>
                </div>
            @endif

            {{-- LEAVE CREDITS CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">Vacation Leave</p>
                        <p class="text-4xl font-bold mt-1">{{ $employee->vacation_credits ?? 0 }}</p>
                        <p class="text-blue-200 text-xs mt-0.5">days remaining</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-rose-100 text-xs font-bold uppercase tracking-wider">Sick Leave</p>
                        <p class="text-4xl font-bold mt-1">{{ $employee->sick_credits ?? 0 }}</p>
                        <p class="text-rose-200 text-xs mt-0.5">days remaining</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-violet-100 text-xs font-bold uppercase tracking-wider">Birthday Leave</p>
                        <p class="text-4xl font-bold mt-1">{{ $employee->birthday_leave_credits ?? 0 }}</p>
                        <p class="text-violet-200 text-xs mt-0.5">days remaining</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Incentive Hours</p>
                        <p class="text-4xl font-bold mt-1">{{ $employee->incentive_hours_credits ?? 0 }}</p>
                        <p class="text-amber-200 text-xs mt-0.5">hours remaining</p>
                    </div>
                </div>
                @if($employee->is_solo_parent)
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-teal-100 text-xs font-bold uppercase tracking-wider">Solo Parent Leave</p>
                        <p class="text-4xl font-bold mt-1">{{ $employee->solo_parent_leave_credits ?? 0 }}</p>
                        <p class="text-teal-200 text-xs mt-0.5">days remaining</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- FILE A REQUEST --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 h-fit"
                     x-data="{ leaveType: 'Sick Leave' }">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        File a Request
                    </h3>

                    <form action="{{ route('leave.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                            <select name="leave_type" x-model="leaveType"
                                    class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <optgroup label="With Credit Deduction">
                                    <option value="Vacation Leave">Vacation Leave</option>
                                    <option value="Sick Leave">Sick Leave</option>
                                    <option value="Birthday Leave">Birthday Leave</option>
                                    @if($employee->is_solo_parent)
                                        <option value="Solo Parent Leave">Solo Parent Leave</option>
                                    @endif
                                    <option value="Incentive Hours">Incentive Hours</option>
                                </optgroup>
                                <optgroup label="Without Credit Deduction">
                                    <option value="Maternity Leave">Maternity Leave</option>
                                    <option value="Paternity Leave">Paternity Leave</option>
                                    <option value="Bereavement Leave">Bereavement Leave</option>
                                    <option value="Official Business">Official Business</option>
                                    <option value="Emergency Leave">Emergency Leave</option>
                                </optgroup>
                            </select>
                        </div>

                        {{-- DATE FIELDS for regular leaves --}}
                        <div x-show="leaveType !== 'Incentive Hours'" class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                                <input type="date" name="start_date"
                                       class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                                <input type="date" name="end_date"
                                       class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        {{-- TIME FIELDS for Incentive Hours --}}
                        <div x-show="leaveType === 'Incentive Hours'" class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                                <input type="date" name="start_date"
                                       class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                                    <input type="time" name="start_time"
                                           class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                                    <input type="time" name="end_time"
                                           class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">Available balance: {{ $employee->incentive_hours_credits ?? 0 }} hrs</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason</label>
                            <textarea name="reason" rows="3" required
                                      class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Brief explanation..."></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition">
                            Submit Request
                        </button>
                    </form>
                </div>

                {{-- HISTORY TABLE --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Request History</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                            <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 border-b dark:border-slate-700">Type</th>
                                    <th class="px-6 py-4 border-b dark:border-slate-700">Period</th>
                                    <th class="px-6 py-4 border-b dark:border-slate-700">Reason</th>
                                    <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                                @forelse($leaves as $leave)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-6 py-4 font-bold text-indigo-900 dark:text-indigo-300 whitespace-nowrap">{{ $leave->leave_type }}</td>
                                    <td class="px-6 py-4">
                                        @if($leave->leave_type === 'Incentive Hours')
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-800 dark:text-gray-200">
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                                </span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ \Carbon\Carbon::parse($leave->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($leave->end_time)->format('h:i A') }}
                                                    ({{ $leave->total_hours }} hrs)
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-800 dark:text-gray-200">
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                </span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} day(s)
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 italic text-gray-500 dark:text-gray-400">"{{ Str::limit($leave->reason, 35) }}"</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($leave->status === 'Pending')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300">Pending</span>
                                        @elseif($leave->status === 'Approved')
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Approved</span>
                                                @if($leave->is_paid)
                                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">Paid</span>
                                                @else
                                                    <span class="text-[10px] font-bold text-gray-400">Unpaid</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-12 text-gray-400 dark:text-gray-500">No leave requests found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
