<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Employee Management') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                Add Employee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <!-- BULK ACTION BAR -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 transition-colors">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-bold text-gray-800 dark:text-white text-lg">{{ count($employees) }}</span> Active Employees
                </div>
                <div class="flex gap-3">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider self-center mr-2">Generate All:</span>
                    
                    <form action="{{ route('payroll.generateAll') }}" method="POST" onsubmit="return confirm('Generate 20th (Mid-Month) payroll for ALL employees?');">
                        @csrf
                        <input type="hidden" name="period" value="Mid-Month">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-blue-700 transition shadow-md flex items-center gap-2 transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Bulk 20th
                        </button>
                    </form>

                    <form action="{{ route('payroll.generateAll') }}" method="POST" onsubmit="return confirm('Generate 5th (End-Month) payroll for ALL employees?');">
                        @csrf
                        <input type="hidden" name="period" value="End-Month">
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-emerald-700 transition shadow-md flex items-center gap-2 transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Bulk 5th
                        </button>
                    </form>
                </div>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-slate-700/50 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Employee</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Contact</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Position</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Base Salary</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Joined</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($employees as $emp)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($emp->user->avatar)
                                                <img src="{{ asset('storage/' . $emp->user->avatar) }}" class="h-10 w-10 rounded-full object-cover mr-3 border border-gray-200 dark:border-slate-600">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-sm mr-3">{{ substr($emp->user->name, 0, 1) }}</div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-gray-800 dark:text-gray-200">{{ $emp->user->name }}</div>
                                                <div class="text-xs text-gray-400 font-mono">{{ $emp->employee_code ?? 'No ID' }}</div> <!-- ID Code Here -->
                                            </div>
                                        </div>
                                        <a href="{{ route('employees.edit', $emp->id) }}" class="text-gray-300 dark:text-slate-600 hover:text-amber-500 dark:hover:text-amber-400 transition p-1 ml-2" title="Edit Details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $emp->user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded text-xs font-bold border border-blue-100 dark:border-blue-800">{{ $emp->position }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-medium text-gray-900 dark:text-gray-200">₱{{ number_format($emp->basic_salary, 2) }}</td>
                                <td class="px-6 py-4 text-center text-xs text-gray-400 dark:text-gray-500">{{ $emp->created_at->format('M d, Y') }}</td>
                                
                                <!-- ACTION BUTTONS -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <!-- ID Card Button (Purple) -->
                                        <a href="{{ route('employees.idcard', $emp->id) }}" target="_blank" class="p-2 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition" title="View Digital ID">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                        </a>

                                        <!-- Manage Salary (Wallet) -->
                                        <a href="{{ route('salary.edit', $emp->id) }}" class="p-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-800 transition" title="Manage Salary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </a>

                                        <!-- 20th Button -->
                                        <form action="{{ route('payroll.create', $emp->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="period" value="Mid-Month">
                                            <button class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-bold hover:bg-blue-200 dark:hover:bg-blue-800 transition" title="Generate 20th">
                                                20th
                                            </button>
                                        </form>

                                        <!-- 5th Button -->
                                        <form action="{{ route('payroll.create', $emp->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="period" value="End-Month">
                                            <button class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg text-xs font-bold hover:bg-emerald-200 dark:hover:bg-emerald-800 transition" title="Generate 5th">
                                                5th
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>