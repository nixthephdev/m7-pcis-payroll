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
            <div class="grid grid-cols-2 @if($employee->is_solo_parent) md:grid-cols-5 @else md:grid-cols-4 @endif gap-4 mb-8">

                {{-- Vacation Leave --}}
                <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 group hover:shadow-md transition-all duration-200">
                    <div class="absolute inset-x-0 top-0 h-[3px] bg-gradient-to-r from-blue-400 to-blue-600 rounded-t-2xl"></div>
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-blue-50 dark:bg-blue-900/20 transition-transform duration-300 group-hover:scale-125"></div>
                    <div class="relative z-10">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Vacation Leave</p>
                        <p class="text-4xl font-extrabold text-gray-800 dark:text-white mt-1 leading-none">{{ $employee->vacation_credits ?? 0 }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">days remaining</p>
                    </div>
                </div>

                {{-- Sick Leave --}}
                <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 group hover:shadow-md transition-all duration-200">
                    <div class="absolute inset-x-0 top-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600 rounded-t-2xl"></div>
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-rose-50 dark:bg-rose-900/20 transition-transform duration-300 group-hover:scale-125"></div>
                    <div class="relative z-10">
                        <div class="w-9 h-9 rounded-xl bg-rose-100 dark:bg-rose-900/50 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Sick Leave</p>
                        <p class="text-4xl font-extrabold text-gray-800 dark:text-white mt-1 leading-none">{{ $employee->sick_credits ?? 0 }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">days remaining</p>
                    </div>
                </div>

                {{-- Birthday Leave --}}
                <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 group hover:shadow-md transition-all duration-200">
                    <div class="absolute inset-x-0 top-0 h-[3px] bg-gradient-to-r from-violet-400 to-violet-600 rounded-t-2xl"></div>
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-violet-50 dark:bg-violet-900/20 transition-transform duration-300 group-hover:scale-125"></div>
                    <div class="relative z-10">
                        <div class="w-9 h-9 rounded-xl bg-violet-100 dark:bg-violet-900/50 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" /></svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Birthday Leave</p>
                        <p class="text-4xl font-extrabold text-gray-800 dark:text-white mt-1 leading-none">{{ $employee->birthday_leave_credits ?? 0 }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">days remaining</p>
                    </div>
                </div>

                {{-- Incentive Hours --}}
                <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 group hover:shadow-md transition-all duration-200">
                    <div class="absolute inset-x-0 top-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-600 rounded-t-2xl"></div>
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-amber-50 dark:bg-amber-900/20 transition-transform duration-300 group-hover:scale-125"></div>
                    <div class="relative z-10">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Incentive Hours</p>
                        <p class="text-4xl font-extrabold text-gray-800 dark:text-white mt-1 leading-none">{{ number_format($employee->incentive_hours_credits ?? 0, 2) }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">hours remaining</p>
                    </div>
                </div>

                {{-- Solo Parent Leave (conditional) --}}
                @if($employee->is_solo_parent)
                <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 group hover:shadow-md transition-all duration-200">
                    <div class="absolute inset-x-0 top-0 h-[3px] bg-gradient-to-r from-teal-400 to-teal-600 rounded-t-2xl"></div>
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-teal-50 dark:bg-teal-900/20 transition-transform duration-300 group-hover:scale-125"></div>
                    <div class="relative z-10">
                        <div class="w-9 h-9 rounded-xl bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Solo Parent</p>
                        <p class="text-4xl font-extrabold text-gray-800 dark:text-white mt-1 leading-none">{{ $employee->solo_parent_leave_credits ?? 0 }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">days remaining</p>
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
