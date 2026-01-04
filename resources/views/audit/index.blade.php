<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-red-100 dark:bg-red-900/20 rounded-lg border border-red-500/50 animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-red-600 dark:text-red-500 leading-tight font-mono tracking-widest uppercase">
                // RESTRICTED AREA: SYSTEM AUDIT LOGS
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-slate-950 min-h-screen transition-colors duration-300">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl shadow-xl overflow-hidden relative transition-colors duration-300">
                <!-- Decorative Top Line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 via-orange-500 to-red-600"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm font-mono">
                        <thead class="bg-gray-50 dark:bg-slate-950 text-gray-500 dark:text-slate-400 uppercase tracking-wider border-b border-gray-200 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-4 font-bold">Timestamp</th>
                                <th class="px-6 py-4 font-bold">User / Actor</th>
                                <th class="px-6 py-4 font-bold">Action Type</th>
                                <th class="px-6 py-4 font-bold w-1/3">Activity Details</th>
                                <th class="px-6 py-4 font-bold text-right">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-slate-300">
                            @foreach($logs as $log)
                                @php
                                    $badgeClass = 'text-blue-700 bg-blue-50 border-blue-200 dark:text-blue-400 dark:bg-blue-500/10 dark:border-blue-500/30';
                                    
                                    if (Str::contains($log->action, ['Delete', 'Destroy', 'Remove'])) {
                                        $badgeClass = 'text-red-700 bg-red-50 border-red-200 dark:text-red-400 dark:bg-red-500/10 dark:border-red-500/30';
                                    } elseif (Str::contains($log->action, ['Update', 'Edit', 'Change'])) {
                                        $badgeClass = 'text-orange-700 bg-orange-50 border-orange-200 dark:text-orange-400 dark:bg-orange-500/10 dark:border-orange-500/30';
                                    } elseif (Str::contains($log->action, ['Create', 'Add', 'Login'])) {
                                        $badgeClass = 'text-emerald-700 bg-emerald-50 border-emerald-200 dark:text-emerald-400 dark:bg-emerald-500/10 dark:border-emerald-500/30';
                                    }
                                @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition duration-150 group">
                                <!-- Timestamp -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-slate-400 group-hover:text-gray-900 dark:group-hover:text-white">
                                    {{ $log->created_at->format('Y-m-d') }} <span class="text-gray-400 dark:text-slate-500 group-hover:text-gray-600 dark:group-hover:text-slate-300">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>

                                <!-- User -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-700">
                                            {{ substr(optional($log->user)->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-black dark:group-hover:text-white">{{ optional($log->user)->name ?? 'SYSTEM' }}</span>
                                    </div>
                                </td>

                                <!-- Action Badge -->
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded border text-xs font-bold uppercase tracking-wide {{ $badgeClass }}">
                                        {{ $log->action }}
                                    </span>
                                </td>

                                <!-- Details -->
                                <td class="px-6 py-4 text-gray-600 dark:text-slate-400 break-words group-hover:text-gray-900 dark:group-hover:text-gray-200">
                                    {{ $log->description }}
                                </td>

                                <!-- IP -->
                                <td class="px-6 py-4 text-right font-mono text-cyan-600 dark:text-cyan-500 group-hover:text-cyan-700 dark:group-hover:text-cyan-300">
                                    {{ $log->ip_address }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($logs->hasPages())
                <div class="bg-gray-50 dark:bg-slate-950 px-6 py-4 border-t border-gray-200 dark:border-slate-800">
                    {{ $logs->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>