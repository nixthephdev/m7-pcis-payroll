<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payroll Masterlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 border-l-4 border-green-500 text-green-700 font-bold">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-indigo-900 text-white uppercase text-xs font-bold">
                                    <th class="py-3 px-4">Employee</th>
                                    <th class="py-3 px-4">Pay Period</th>
                                    <th class="py-3 px-4 text-right">Gross</th>
                                    <th class="py-3 px-4 text-right">Deductions</th>
                                    <th class="py-3 px-4 text-right">Net Pay</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm">
                                @foreach($payrolls as $payroll)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    
                                    <!-- Employee Name -->
                                    <td class="py-3 px-4 font-bold text-indigo-900">
                                        {{ optional($payroll->employee->user)->name ?? 'Unknown' }}
                                        <div class="text-xs text-gray-400 font-normal">{{ $payroll->employee->position }}</div>
                                    </td>

                                    <!-- Date -->
                                    <td class="py-3 px-4">
                                        {{ \Carbon\Carbon::parse($payroll->pay_date)->format('M d, Y') }}
                                    </td>

                                    <!-- Money Columns -->
                                    <td class="py-3 px-4 text-right">₱{{ number_format($payroll->gross_salary, 2) }}</td>
                                    <td class="py-3 px-4 text-right text-red-500">-₱{{ number_format($payroll->deductions, 2) }}</td>
                                    <td class="py-3 px-4 text-right font-bold text-green-600">₱{{ number_format($payroll->net_salary, 2) }}</td>

                                    <!-- Status -->
                                    <td class="py-3 px-4 text-center">
                                        @if($payroll->status == 'Paid')
                                            <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold">Paid</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 py-1 px-3 rounded-full text-xs font-bold">Pending</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            
                                            <!-- Download PDF -->
                                            <a href="{{ route('payroll.download', $payroll->id) }}" class="text-gray-500 hover:text-indigo-600" title="Download PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                            </a>

                                            <!-- Mark as Paid Button -->
                                            @if($payroll->status == 'Pending')
                                                <form action="{{ route('payroll.paid', $payroll->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-bold shadow transition" title="Mark as Paid">
                                                        Pay
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
                            <div class="text-center py-8 text-gray-400">No payroll records found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>