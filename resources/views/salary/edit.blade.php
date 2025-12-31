<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="text-indigo-200 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Salary: <span class="text-indigo-200">{{ $employee->user->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT: Add Item Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 h-fit">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Add Item</h3>
                            <p class="text-xs text-gray-500">Allowance or Deduction</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('salary.store', $employee->id) }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Item Name</label>
                            <input type="text" name="name" placeholder="e.g. Rice Subsidy" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Amount</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" step="0.01" name="amount" required class="w-full rounded-lg border-gray-300 pl-7 focus:border-indigo-500 focus:ring-indigo-500 transition" placeholder="0.00">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition">
                                <option value="earning">➕ Earning (Adds to Pay)</option>
                                <option value="deduction">➖ Deduction (Subtracts)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition transform hover:scale-[1.02]">
                            Add to Salary
                        </button>
                    </form>
                </div>

                <!-- RIGHT: Configuration List -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Current Configuration</h3>
                        <span class="text-xs font-mono bg-gray-200 text-gray-600 px-2 py-1 rounded">Base: ₱{{ number_format($employee->basic_salary, 2) }}</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white text-xs uppercase text-gray-400 font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 border-b">Item Name</th>
                                    <th class="px-6 py-4 border-b">Type</th>
                                    <th class="px-6 py-4 border-b text-right">Amount</th>
                                    <th class="px-6 py-4 border-b text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($employee->salaryItems as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-800">{{ $item->name }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->type == 'earning')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                                Earning
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                                Deduction
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-bold {{ $item->type == 'earning' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $item->type == 'deduction' ? '-' : '+' }}₱{{ number_format($item->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('salary.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 text-gray-400 hover:text-rose-600 transition rounded-full hover:bg-rose-50" title="Remove Item">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($employee->salaryItems->isEmpty())
                            <div class="text-center py-12">
                                <div class="bg-gray-50 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No custom items configured.</p>
                                <p class="text-xs text-gray-400">Add allowances or deductions on the left.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>