<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('13th Month Pay Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                
                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-indigo-50 dark:bg-slate-800/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-indigo-900 dark:text-white">Year-End Bonus Calculation</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Based on Total Basic Salary Earned in {{ $currentYear }} / 12</p>
                    </div>
                    <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-700 px-3 py-1 rounded-lg border border-indigo-100 dark:border-slate-600">
                        Year: {{ $currentYear }}
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-700">Employee</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-right">Total Basic Earned (YTD)</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-right">13th Month Pay</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Status</th>
                                <th class="px-6 py-4 border-b dark:border-slate-700 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach($data as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xs mr-3">
                                            {{ substr($row['employee']->user->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $row['employee']->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-gray-500 dark:text-gray-400">
                                    ₱{{ number_format($row['total_basic'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400 text-lg">
                                    ₱{{ number_format($row['thirteenth_pay'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($row['is_paid'])
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Generated</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400">Not Yet</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$row['is_paid'])
                                        <form action="{{ route('payroll.13th.generate', $row['employee']->id) }}" method="POST" onsubmit="return confirm('Generate 13th Month Pay for {{ $row['employee']->user->name }}?');">
                                            @csrf
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-xs shadow-md transition transform hover:scale-105">
                                                Generate
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="bg-gray-200 dark:bg-slate-700 text-gray-400 cursor-not-allowed px-4 py-2 rounded-lg font-bold text-xs">
                                            Done
                                        </button>
                                    @endif
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