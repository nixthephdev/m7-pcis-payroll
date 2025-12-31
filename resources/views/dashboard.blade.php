<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Workspace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- ALERTS -->
            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold">Success</p>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT COLUMN: Attendance & Stats -->
                <div class="space-y-8">
                    
                    <!-- 1. ATTENDANCE CONTROL -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Attendance
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Clock In -->
                            <form action="{{ route('clock.in') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-6 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition transform text-white flex flex-col items-center justify-center group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="font-bold text-lg">Time In</span>
                                </button>
                            </form>

                            <!-- Clock Out -->
                            <form action="{{ route('clock.out') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-6 bg-gradient-to-br from-rose-400 to-rose-600 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition transform text-white flex flex-col items-center justify-center group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="font-bold text-lg">Time Out</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- 2. MY STATS CARD (Credit Card Style) -->
                    @php
                        $employee = Auth::user()->employee;
                        if($employee) {
                            $daysWorked = \App\Models\Attendance::where('employee_id', $employee->id)
                                            ->whereMonth('date', \Carbon\Carbon::now()->month)
                                            ->count();
                            $estimatedPay = ($employee->basic_salary / 22) * $daysWorked;
                        } else {
                            $daysWorked = 0;
                            $estimatedPay = 0;
                        }
                    @endphp

                    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                        <!-- Decorative Circles -->
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
                        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-white opacity-10"></div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider">Position</p>
                                    <p class="font-bold text-lg">{{ $employee->position ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
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

                <!-- RIGHT COLUMN: Payroll History -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Payroll History</h3>
                            
                            <!-- Admin Test Button (Only visible to Admin) -->
                            @if(Auth::user()->role === 'admin')
                            <form action="{{ route('payroll.generate') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1 rounded-full hover:bg-indigo-100 transition">
                                    + Test Gen
                                </button>
                            </form>
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600">
                                <thead class="bg-white text-xs uppercase text-gray-400 font-bold tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4 border-b">Pay Period</th>
                                        <th class="px-6 py-4 border-b text-right">Gross</th>
                                        <th class="px-6 py-4 border-b text-right">Deductions</th>
                                        <th class="px-6 py-4 border-b text-right">Net Pay</th>
                                        <th class="px-6 py-4 border-b text-center">Status</th>
                                        <th class="px-6 py-4 border-b text-center">Slip</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @if($employee)
                                        @php
                                            $payrolls = \App\Models\Payroll::where('employee_id', $employee->id)->latest()->get();
                                        @endphp

                                        @foreach($payrolls as $payroll)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($payroll->pay_date)->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-400">{{ $payroll->period ?? 'Standard' }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-right">₱{{ number_format($payroll->gross_salary, 2) }}</td>
                                            <td class="px-6 py-4 text-right text-rose-500">-₱{{ number_format($payroll->deductions, 2) }}</td>
                                            <td class="px-6 py-4 text-right font-bold text-emerald-600">₱{{ number_format($payroll->net_salary, 2) }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if($payroll->status == 'Paid')
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                                @else
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <a href="{{ route('payroll.download', $payroll->id) }}" class="p-2 bg-gray-100 rounded-lg text-gray-600 hover:bg-indigo-100 hover:text-indigo-600 transition" title="Download PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- Admin Mark Paid (Contextual) -->
                                                    @if(Auth::user()->role === 'admin' && $payroll->status == 'Pending')
                                                        <form action="{{ route('payroll.paid', $payroll->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="p-2 bg-emerald-100 rounded-lg text-emerald-600 hover:bg-emerald-200 transition" title="Mark Paid">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            
                            @if(!$employee || (isset($payrolls) && $payrolls->isEmpty()))
                                <div class="text-center py-12">
                                    <p class="text-gray-400">No payroll records found.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>