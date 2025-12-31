<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- ALERTS -->
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 border-l-4 border-green-500 text-green-700">
                    <p class="font-bold">Success</p>
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <!-- GRID LAYOUT -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <!-- CARD 1: ATTENDANCE ACTIONS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Attendance Control</h3>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Clock In Button -->
                            <form action="{{ route('clock.in') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-32 flex flex-col items-center justify-center bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl shadow-lg transition transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-xl font-bold">Clock In</span>
                                    <span class="text-sm opacity-80">Start your shift</span>
                                </button>
                            </form>

                            <!-- Clock Out Button -->
                            <form action="{{ route('clock.out') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-32 flex flex-col items-center justify-center bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl shadow-lg transition transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    <span class="text-xl font-bold">Clock Out</span>
                                    <span class="text-sm opacity-80">End your shift</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: EMPLOYEE INFO (PHP LOGIC INCLUDED) -->
                @php
                    $employee = Auth::user()->employee;
                    $daysWorked = \App\Models\Attendance::where('employee_id', $employee->id)
                                    ->whereMonth('date', \Carbon\Carbon::now()->month)
                                    ->count();
                    $estimatedPay = ($employee->basic_salary / 22) * $daysWorked;
                @endphp
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">My Stats</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Position</span>
                                <span class="font-semibold">{{ $employee->position }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Basic Salary</span>
                                <span class="font-semibold">${{ number_format($employee->basic_salary, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Days Worked</span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $daysWorked }} Days</span>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <span class="block text-gray-500 text-sm">Est. Gross Pay (This Month)</span>
                                <span class="block text-2xl font-bold text-green-600">${{ number_format($estimatedPay, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAYROLL SECTION -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Payroll History</h3>
                        <form action="{{ route('payroll.generate') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium transition">
                                Generate Payroll (Admin Test)
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <!-- Table Header -->
<thead>
    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
        <th class="py-3 px-6 text-left">Pay Date</th>
        <th class="py-3 px-6 text-right">Gross Salary</th>
        <th class="py-3 px-6 text-right">Deductions</th>
        <th class="py-3 px-6 text-right">Net Salary</th>
        <th class="py-3 px-6 text-center">Status</th>
        <th class="py-3 px-6 text-center">Action</th> <!-- NEW COLUMN HERE -->
    </tr>
</thead>

<!-- Table Body -->
<tbody class="text-gray-600 text-sm font-light">
    @php
        $payrolls = \App\Models\Payroll::where('employee_id', $employee->id)->latest()->get();
    @endphp

    @foreach($payrolls as $payroll)
    <tr class="border-b border-gray-200 hover:bg-gray-50">
        <td class="py-3 px-6 text-left whitespace-nowrap font-medium">
            {{ \Carbon\Carbon::parse($payroll->pay_date)->format('M d, Y') }}
        </td>
        <td class="py-3 px-6 text-right">
            ${{ number_format($payroll->gross_salary, 2) }}
        </td>
        <td class="py-3 px-6 text-right text-red-500">
            -${{ number_format($payroll->deductions, 2) }}
        </td>
        <td class="py-3 px-6 text-right font-bold text-green-600 text-base">
            ${{ number_format($payroll->net_salary, 2) }}
        </td>
        <td class="py-3 px-6 text-center">
            <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Paid</span>
        </td>
        
        <!-- NEW DOWNLOAD BUTTON HERE -->
        <td class="py-3 px-6 text-center">
            <a href="{{ route('payroll.download', $payroll->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold underline">
                Download PDF
            </a>
        </td>
    </tr>
    @endforeach
</tbody>
                        </table>
                        
                        @if($payrolls->isEmpty())
                            <div class="text-center py-8 text-gray-400">
                                No payroll records found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>