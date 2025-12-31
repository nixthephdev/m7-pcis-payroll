<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Attendance Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daily Records</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Date</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Employee</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Time In</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Time Out</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Status</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($attendances as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($log->employee->user->avatar)
                                            <img src="{{ asset('storage/' . $log->employee->user->avatar) }}" class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200 dark:border-slate-600">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xs mr-3">{{ substr($log->employee->user->name, 0, 1) }}</div>
                                        @endif
                                        <span>{{ optional($log->employee->user)->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-emerald-600 dark:text-emerald-400 font-mono font-bold">
                                    {{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-rose-600 dark:text-rose-400 font-mono font-bold">
                                    @if($log->time_out)
                                        {{ \Carbon\Carbon::parse($log->time_out)->format('h:i A') }}
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600 font-normal">--:--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $log->status == 'Late' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('attendance.edit', $log->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 font-bold text-xs hover:underline">
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