<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Executive Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- 1. VIBRANT STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Staff -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">Total Workforce</p>
                        <p class="text-4xl font-bold mt-2">{{ $totalEmployees }}</p>
                        <p class="text-blue-200 text-xs mt-1">Active Employees</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-2 translate-y-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
                <!-- Present Today -->
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Attendance</p>
                        <div class="flex items-baseline gap-2 mt-2">
                            <p class="text-4xl font-bold">{{ $presentToday }}</p>
                            <span class="text-emerald-200 text-sm">/ {{ $totalEmployees }}</span>
                        </div>
                        <p class="text-emerald-200 text-xs mt-1">Present Today</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-2 translate-y-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <!-- Pending Leaves -->
                <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-orange-100 text-xs font-bold uppercase tracking-wider">Action Items</p>
                        <p class="text-4xl font-bold mt-2">{{ $pendingLeaves }}</p>
                        <p class="text-orange-100 text-xs mt-1">Pending Leave Requests</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-2 translate-y-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <!-- Payroll Cost -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Payroll ({{ date('M') }})</p>
                        <p class="text-3xl font-bold mt-2">₱{{ number_format($monthlyCost, 2) }}</p>
                        <p class="text-indigo-200 text-xs mt-1">Total Net Pay Disbursed</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-2 translate-y-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <!-- 2. MAIN CONTENT GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT: Quick Actions -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 transition-colors duration-300">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Management Tools</h3>
                    
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('employees.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg border border-gray-100 dark:border-slate-600 hover:border-indigo-200 transition group">
                            <div class="bg-indigo-600 text-white p-2 rounded-lg group-hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-700 dark:group-hover:text-indigo-400">Add Employee</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Create new account</p>
                            </div>
                        </a>

                        <a href="{{ route('employees.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg border border-gray-100 dark:border-slate-600 hover:border-emerald-200 transition group">
                            <div class="bg-emerald-600 text-white p-2 rounded-lg group-hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">Manage Salaries</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Allowances & Deductions</p>
                            </div>
                        </a>

                        <a href="{{ route('leaves.manage') }}" class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg border border-gray-100 dark:border-slate-600 hover:border-orange-200 transition group">
                            <div class="bg-orange-500 text-white p-2 rounded-lg group-hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-orange-700 dark:group-hover:text-orange-400">Approve Leaves</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $pendingLeaves }} Pending Requests
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- RIGHT: Live Feed -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 transition-colors duration-300">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Live Attendance Feed</h3>
                        <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">View All &rarr;</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                            <thead class="bg-gray-50 dark:bg-slate-700/50 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-lg">Employee</th>
                                    <th class="px-4 py-3">Time In</th>
                                    <th class="px-4 py-3 rounded-r-lg text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                @foreach($recentAttendance as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            @if($log->employee->user->avatar)
                                                <img src="{{ asset('storage/' . $log->employee->user->avatar) }}" class="h-8 w-8 rounded-full object-cover mr-3">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xs mr-3">
                                                    {{ substr($log->employee->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $log->employee->user->name }}</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $log->employee->position }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-emerald-600 dark:text-emerald-400 font-bold">
                                        {{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $log->status == 'Late' ? 'bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-300' : 'bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-300' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($recentAttendance->isEmpty())
                            <div class="text-center py-10">
                                <p class="text-gray-400 dark:text-gray-500">No activity recorded today.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- 3. ADMIN PERSONAL PAYSLIPS -->
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    My Personal Payslips
                </h3>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
                    @if(isset($adminEmployee) && $myPayrolls->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                                <thead class="bg-indigo-50 dark:bg-slate-700/50 text-xs uppercase text-indigo-800 dark:text-indigo-300 font-bold">
                                    <tr>
                                        <th class="px-6 py-3">Pay Period</th>
                                        <th class="px-6 py-3 text-right">Gross</th>
                                        <th class="px-6 py-3 text-right">Deductions</th>
                                        <th class="px-6 py-3 text-right">Net Pay</th>
                                        <th class="px-6 py-3 text-center">Status</th>
                                        <th class="px-6 py-3 text-center">Slip</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @foreach($myPayrolls as $payroll)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                        <td class="px-6 py-3">
                                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $payroll->period }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ \Carbon\Carbon::parse($payroll->pay_date)->format('M d, Y') }}</span>
                                        </td>
                                        <td class="px-6 py-3 text-right">₱{{ number_format($payroll->gross_salary, 2) }}</td>
                                        <td class="px-6 py-3 text-right text-rose-500 dark:text-rose-400">-₱{{ number_format($payroll->deductions, 2) }}</td>
                                        <td class="px-6 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($payroll->net_salary, 2) }}</td>
                                        <td class="px-6 py-3 text-center">
                                            @if($payroll->status == 'Paid')
                                                <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Paid</span>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <!-- DOWNLOAD ICON BUTTON -->
                                            <a href="{{ route('payroll.download', $payroll->id) }}" class="inline-block p-2 bg-gray-100 dark:bg-slate-700 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 hover:text-indigo-600 dark:hover:text-indigo-300 transition" title="Download PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400 font-medium">No personal payroll records found.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>