<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Employee Masterlist') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="bg-white text-indigo-900 px-4 py-2 rounded-md font-bold text-sm hover:bg-gray-100 transition">
                + Add New Employee
            </a>
        </div>
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
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-indigo-50 text-indigo-900 uppercase text-xs font-bold">
                                <th class="py-3 px-4">Name</th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4">Position</th>
                                <th class="py-3 px-4 text-right">Basic Salary</th>
                                <th class="py-3 px-4 text-center">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach($employees as $emp)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-bold">{{ $emp->user->name }}</td>
                                <td class="py-3 px-4 text-gray-500">{{ $emp->user->email }}</td>
                                <td class="py-3 px-4">
                                    <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs">{{ $emp->position }}</span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono">${{ number_format($emp->basic_salary, 2) }}</td>
                                <td class="py-3 px-4 text-center text-gray-400 text-xs">
                                    {{ $emp->created_at->format('M d, Y') }}
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