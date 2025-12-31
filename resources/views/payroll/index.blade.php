<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Payroll Masterlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">All Records</h3>
                    
                    <form action="{{ route('payroll.payAll') }}" method="POST" onsubmit="return confirm('Mark ALL pending records as PAID?');">
                        @csrf
                        <button type="submit" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 font-bold text-sm flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1.5 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Mark All Paid
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Employee</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Pay Period</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Gross</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Deductions</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Net Pay</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Status</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($payrolls as $payroll)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4 font-bold text-indigo-900 dark:text-indigo-300">
                                    {{ optional($payroll->employee->user)->name ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="block font-medium text-gray-800 dark:text-gray-200">{{ $payroll->period }}</span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($payroll->pay_date)->format('M d, Y') }}</span>
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
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('payroll.download', $payroll->id) }}" class="p-2 bg-gray-100 dark:bg-slate-700 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 hover:text-indigo-600 dark:hover:text-indigo-300 transition" title="Download PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        </a>
                                        
                                        @if($payroll->status == 'Pending')
                                            <form action="{{ route('payroll.paid', $payroll->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-800 transition" title="Mark Paid">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to DELETE this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-rose-100 dark:bg-rose-900/30 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-200 dark:hover:bg-rose-800 transition" title="Delete Record">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($payrolls->isEmpty())
                        <div class="text-center py-12 text-gray-400 dark:text-gray-500">No records found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>