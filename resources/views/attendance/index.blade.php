<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200 text-green-600 text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Daily Attendance Records</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                                <tr>
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Employee</th>
                                    <th class="px-6 py-3">Time In</th>
                                    <th class="px-6 py-3">Time Out</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($attendances as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ optional($log->employee->user)->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 text-green-600 font-mono">
                                        {{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-red-600 font-mono">
                                        @if($log->time_out)
                                            {{ \Carbon\Carbon::parse($log->time_out)->format('h:i A') }}
                                        @else
                                            <span class="text-gray-400">--:--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                                            {{ $log->status == 'Late' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('attendance.edit', $log->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>