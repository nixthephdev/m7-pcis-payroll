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

                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 transition-colors duration-300">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Attendance
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <form action="{{ route('clock.in') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-6 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition transform text-white flex flex-col items-center justify-center group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                    <span class="font-bold text-lg">Time In</span>
                                </button>
                            </form>
                            <form action="{{ route('clock.out') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-6 bg-gradient-to-br from-rose-400 to-rose-600 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition transform text-white flex flex-col items-center justify-center group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    <span class="font-bold text-lg">Time Out</span>
                                </button>
                            </form>
                        </div>
                    </div>

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
        </div>
    </div>
</x-app-layout>