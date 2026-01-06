<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Team Requests (Head/Coordinator Approval)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Pending Endorsements</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Review requests from your subordinates before HR approval.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Employee</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Leave Details</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Duration</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($leaves as $leave)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ optional($leave->employee->user)->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $leave->employee->position ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-bold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 mb-1">{{ $leave->leave_type }}</span>
                                    <p class="italic text-gray-500 dark:text-gray-400">"{{ $leave->reason }}"</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Days</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <!-- Approve (Endorse) -->
                                        <form action="{{ route('leaves.supervisor', $leave->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="Approve">
                                            <button class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-800 transition font-bold text-xs flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                Endorse
                                            </button>
                                        </form>

                                        <!-- Reject -->
                                        <form action="{{ route('leaves.supervisor', $leave->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="Reject">
                                            <button class="px-4 py-2 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-200 dark:hover:bg-rose-800 transition font-bold text-xs flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($leaves->isEmpty())
                        <div class="text-center py-12 text-gray-400 dark:text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p>All clear! No pending requests from your team.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>