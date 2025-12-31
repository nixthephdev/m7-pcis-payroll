<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Management') }}
            </h2>
            
            <!-- Add New Employee (Kept in Header as Primary Page Action) -->
            <a href="{{ route('employees.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Add Employee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 border-l-4 border-green-500 text-green-700 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <!-- BULK ACTION BAR -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                
                <!-- Left: Count -->
                <div class="text-sm text-gray-500">
                    <span class="font-bold text-gray-800 text-lg">{{ count($employees) }}</span> Active Employees
                </div>

                <!-- Right: Bulk Generate Buttons -->
                <div class="flex gap-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider self-center mr-2">Generate All:</span>
                    
                    <!-- Bulk 20th Button -->
                    <form action="{{ route('payroll.generateAll') }}" method="POST" onsubmit="return confirm('Generate 20th (Mid-Month) payroll for ALL employees?');">
                        @csrf
                        <input type="hidden" name="period" value="Mid-Month">
                        <button type="submit" class="bg-blue-50 text-blue-600 border border-blue-200 px-4 py-2 rounded-lg font-bold text-sm hover:bg-blue-100 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            20th
                        </button>
                    </form>

                    <!-- Bulk 5th Button -->
                    <form action="{{ route('payroll.generateAll') }}" method="POST" onsubmit="return confirm('Generate 5th (End-Month) payroll for ALL employees?');">
                        @csrf
                        <input type="hidden" name="period" value="End-Month">
                        <button type="submit" class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-4 py-2 rounded-lg font-bold text-sm hover:bg-emerald-100 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            5th
                        </button>
                    </form>
                </div>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b">Employee</th>
                                <th class="px-6 py-4 border-b">Contact</th>
                                <th class="px-6 py-4 border-b">Position</th>
                                <th class="px-6 py-4 border-b text-right">Base Salary</th>
                                <th class="px-6 py-4 border-b text-center">Joined</th>
                                <th class="px-6 py-4 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($employees as $emp)
                            <tr class="hover:bg-gray-50 transition group">
                                
                                <!-- Employee Name with Edit Pencil -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($emp->user->avatar)
                                                <img src="{{ asset('storage/' . $emp->user->avatar) }}" class="h-10 w-10 rounded-full object-cover mr-3 border border-gray-200">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm mr-3">
                                                    {{ substr($emp->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="font-bold text-gray-800">{{ $emp->user->name }}</span>
                                        </div>
                                        
                                        <!-- Edit Icon -->
                                        <a href="{{ route('employees.edit', $emp->id) }}" class="text-gray-300 hover:text-amber-500 transition p-1 ml-2" title="Edit Details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-500">{{ $emp->user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold border border-blue-100">{{ $emp->position }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-medium text-gray-900">₱{{ number_format($emp->basic_salary, 2) }}</td>
                                <td class="px-6 py-4 text-center text-xs text-gray-400">
                                    {{ $emp->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col gap-2 items-center">
                                        <a href="{{ route('salary.edit', $emp->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">Manage Salary</a>
                                        
                                        <div class="flex gap-1">
                                            <!-- Individual 20th -->
                                            <form action="{{ route('payroll.create', $emp->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="period" value="Mid-Month">
                                                <button class="px-3 py-1 bg-blue-50 text-blue-600 rounded text-xs font-bold hover:bg-blue-100 transition border border-blue-200" title="Generate 20th Pay">
                                                    20th
                                                </button>
                                            </form>

                                            <!-- Individual 5th -->
                                            <form action="{{ route('payroll.create', $emp->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="period" value="End-Month">
                                                <button class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded text-xs font-bold hover:bg-emerald-100 transition border border-emerald-200" title="Generate 5th Pay">
                                                    5th
                                                </button>
                                            </form>
                                        </div>
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