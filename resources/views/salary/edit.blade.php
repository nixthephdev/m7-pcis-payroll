<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Salary: {{ $employee->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- LEFT: Add New Item Form -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Add Allowance / Deduction</h3>
                    
                    <form action="{{ route('salary.store', $employee->id) }}" method="POST">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Item Name</label>
                            <input type="text" name="name" placeholder="e.g. Rice Subsidy or SSS" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Amount (₱)</label>
                            <input type="number" step="0.01" name="amount" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="earning">Earning (Adds to Salary)</option>
                                <option value="deduction">Deduction (Subtracts from Salary)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Add Item
                        </button>
                    </form>
                </div>

                <!-- RIGHT: List of Items -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Current Configuration</h3>

                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2">Name</th>
                                <th class="pb-2 text-right">Amount</th>
                                <th class="pb-2 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->salaryItems as $item)
                            <tr class="border-b last:border-0">
                                <td class="py-3">
                                    {{ $item->name }}
                                    <span class="block text-xs {{ $item->type == 'earning' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ucfirst($item->type) }}
                                    </span>
                                </td>
                                <td class="py-3 text-right font-mono">
                                    {{ $item->type == 'deduction' ? '-' : '+' }}₱{{ number_format($item->amount, 2) }}
                                </td>
                                <td class="py-3 text-center">
                                    <form action="{{ route('salary.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-xs font-bold">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($employee->salaryItems->isEmpty())
                        <p class="text-gray-400 text-center mt-4">No items added yet.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>