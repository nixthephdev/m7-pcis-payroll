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
                                <th class="px-6 py-4 border-b dark:border-slate-700">User</th>
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
                                        <!-- Check if attendable exists first -->
                                        @if($log->attendable && $log->attendable->user)
                                            @if($log->attendable->user->avatar)
                                                <img src="{{ asset('storage/' . $log->attendable->user->avatar) }}" class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200 dark:border-slate-600">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xs mr-3">
                                                    {{ substr($log->attendable->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $log->attendable->user->name }}</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                                    @if($log->attendable_type == 'App\Models\Student')
                                                        Student
                                                    @else
                                                        {{ $log->attendable->position ?? 'Employee' }}
                                                    @endif
                                                </p>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Unknown User (Deleted?)</span>
                                        @endif
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
                                    <a href="{{ route('attendance.edit', $log->id) }}" class="inline-block p-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-800 transition" title="Edit Record">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
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