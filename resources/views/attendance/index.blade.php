<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Daily Records</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-white text-xs uppercase text-gray-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b">Date</th>
                                <th class="px-6 py-4 border-b">Employee</th>
                                <th class="px-6 py-4 border-b">Time In</th>
                                <th class="px-6 py-4 border-b">Time Out</th>
                                <th class="px-6 py-4 border-b">Status</th>
                                <th class="px-6 py-4 border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($attendances as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($log->employee->user->avatar)
                                            <img src="{{ asset('storage/' . $log->employee->user->avatar) }}" class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-3">
                                                {{ substr($log->employee->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span>{{ optional($log->employee->user)->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-emerald-600 font-mono font-bold">
                                    {{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-rose-600 font-mono font-bold">
                                    @if($log->time_out)
                                        {{ \Carbon\Carbon::parse($log->time_out)->format('h:i A') }}
                                    @else
                                        <span class="text-gray-300 font-normal">--:--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $log->status == 'Late' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('attendance.edit', $log->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs hover:underline">
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
</x-app-layout>