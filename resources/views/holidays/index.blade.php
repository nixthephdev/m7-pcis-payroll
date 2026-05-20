<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Holiday Management') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
         x-data="{
             showAddModal: false,
             showEditModal: false,
             editId: '',
             editName: '',
             editDate: '',
             editType: 'regular',
             editDescription: '',
             editIsRecurring: false,
             openEdit(id, name, date, type, description, isRecurring) {
                 this.editId = id;
                 this.editName = name;
                 this.editDate = date;
                 this.editType = type;
                 this.editDescription = description;
                 this.editIsRecurring = isRecurring;
                 this.showEditModal = true;
             }
         }">

        {{-- Flash Message --}}
        @if(session('message'))
            <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 font-bold shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        @php
            $totalCount     = $holidays->count();
            $regularCount   = $holidays->where('type', 'regular')->count();
            $specialCount   = $holidays->where('type', 'special')->count();
            $recurringCount = $holidays->where('is_recurring', true)->count();
        @endphp

        {{-- Page Header --}}
        <div class="mb-6 bg-gradient-to-r from-indigo-700 to-indigo-500 rounded-2xl px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-lg">
            <div class="flex items-center gap-4">
                <div class="bg-white/10 rounded-xl p-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">Holiday Management</h1>
                    <p class="text-indigo-200 text-sm mt-0.5">Declare public holidays and special non-working days for {{ $year }}.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('holidays.index') }}">
                    <select name="year" onchange="this.form.submit()"
                            class="text-sm border border-white/30 rounded-lg px-3 py-2 bg-white/10 text-white placeholder-white focus:ring-2 focus:ring-white focus:outline-none backdrop-blur-sm">
                        @foreach($years->merge([now()->year])->unique()->sortDesc() as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }} class="text-gray-800 bg-white">{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
                <button @click="showAddModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-700 text-sm font-bold rounded-lg shadow hover:bg-indigo-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Holiday
                </button>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-indigo-100 dark:bg-indigo-900/40">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalCount }}</div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-0.5">Total</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-blue-100 dark:bg-blue-900/40">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $regularCount }}</div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-0.5">Regular</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-amber-100 dark:bg-amber-900/40">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $specialCount }}</div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-0.5">Special</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
                <div class="p-2.5 rounded-lg bg-emerald-100 dark:bg-emerald-900/40">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $recurringCount }}</div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-0.5">Recurring</div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50 dark:bg-slate-800/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <h3 class="font-bold text-gray-700 dark:text-white text-sm">Holiday List</h3>
                </div>
                <span class="text-xs text-gray-400 dark:text-gray-500">
                    {{ $totalCount }} {{ Str::plural('holiday', $totalCount) }} &bull; {{ $year }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead>
                        <tr class="bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-100 dark:border-indigo-900/40 text-xs uppercase tracking-wider text-indigo-600 dark:text-indigo-400 font-bold">
                            <th class="px-5 py-3.5">#</th>
                            <th class="px-5 py-3.5">Holiday Name</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5 text-center">Type</th>
                            <th class="px-5 py-3.5 text-center">Recurring</th>
                            <th class="px-5 py-3.5">Description</th>
                            <th class="px-5 py-3.5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($holidays as $i => $holiday)
                        <tr class="hover:bg-indigo-50/40 dark:hover:bg-indigo-900/10 transition group">
                            <td class="px-5 py-4 text-gray-400 dark:text-gray-500 text-xs font-mono">
                                {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($holiday->type === 'regular')
                                        <div class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></div>
                                    @else
                                        <div class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></div>
                                    @endif
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $holiday->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($holiday->is_recurring)
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $holiday->date->format('F j') }}</span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">every year</span>
                                    </div>
                                @else
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $holiday->date->format('F j, Y') }}</span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $holiday->date->format('l') }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($holiday->type === 'regular')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 ring-1 ring-indigo-200 dark:ring-indigo-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Regular
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 ring-1 ring-amber-200 dark:ring-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Special
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($holiday->is_recurring)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 ring-1 ring-emerald-200 dark:ring-emerald-800">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Annual
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400">
                                        One-time
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400 max-w-xs">
                                <span class="line-clamp-1">{{ $holiday->description ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="openEdit(
                                                '{{ $holiday->id }}',
                                                '{{ addslashes($holiday->name) }}',
                                                '{{ $holiday->date->format('Y-m-d') }}',
                                                '{{ $holiday->type }}',
                                                '{{ addslashes($holiday->description ?? '') }}',
                                                {{ $holiday->is_recurring ? 'true' : 'false' }}
                                            )"
                                            class="p-2 rounded-lg text-indigo-500 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('holidays.destroy', $holiday->id) }}"
                                          onsubmit="return confirm('Remove \'{{ addslashes($holiday->name) }}\' from holidays?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-lg text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition"
                                                title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="w-14 h-14 opacity-30" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm font-semibold">No holidays declared for {{ $year }}</p>
                                    <p class="text-xs">Click <span class="font-bold text-indigo-500">Add Holiday</span> to get started.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($totalCount > 0)
            <div class="px-6 py-3 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/30 flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Annual holidays repeat every year on the same date regardless of the year filter.
            </div>
            @endif
        </div>

        {{-- ADD MODAL --}}
        <div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm" @click="showAddModal = false"></div>

                <div x-show="showAddModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">

                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-5 flex items-center gap-3">
                        <div class="bg-white/15 rounded-lg p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Add Holiday</h3>
                            <p class="text-indigo-200 text-xs">Declare a new public holiday or non-working day.</p>
                        </div>
                        <button type="button" @click="showAddModal = false" class="ml-auto text-white/70 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('holidays.store') }}">
                        @csrf
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Holiday Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required placeholder="e.g. Christmas Day"
                                       class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Date <span class="text-rose-500">*</span></label>
                                    <input type="date" name="date" required
                                           class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Type <span class="text-rose-500">*</span></label>
                                    <select name="type" required
                                            class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition">
                                        <option value="regular">Regular Holiday</option>
                                        <option value="special">Special Non-Working</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Description</label>
                                <textarea name="description" rows="2" placeholder="Optional notes..."
                                          class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition resize-none"></textarea>
                            </div>

                            <label class="flex items-center gap-3 cursor-pointer select-none bg-indigo-50 dark:bg-indigo-900/20 rounded-xl px-4 py-3 border border-indigo-100 dark:border-indigo-900/40">
                                <input type="checkbox" name="is_recurring" value="1"
                                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="text-sm font-semibold text-indigo-700 dark:text-indigo-300">Recurring every year</span>
                                    <p class="text-xs text-indigo-400 dark:text-indigo-500 mt-0.5">This holiday repeats on the same date annually.</p>
                                </div>
                            </label>
                        </div>

                        <div class="bg-gray-50 dark:bg-slate-700/40 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-slate-700">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 shadow transition">
                                Save Holiday
                            </button>
                            <button type="button" @click="showAddModal = false"
                                    class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT MODAL --}}
        <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm" @click="showEditModal = false"></div>

                <div x-show="showEditModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">

                    <div class="bg-gradient-to-r from-amber-500 to-amber-400 px-6 py-5 flex items-center gap-3">
                        <div class="bg-white/15 rounded-lg p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Edit Holiday</h3>
                            <p class="text-amber-100 text-xs">Update the holiday details below.</p>
                        </div>
                        <button type="button" @click="showEditModal = false" class="ml-auto text-white/70 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="`/holidays/${editId}`">
                        @csrf
                        @method('PUT')

                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Holiday Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required x-model="editName"
                                       class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Date <span class="text-rose-500">*</span></label>
                                    <input type="date" name="date" required x-model="editDate"
                                           class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Type <span class="text-rose-500">*</span></label>
                                    <select name="type" required x-model="editType"
                                            class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition">
                                        <option value="regular">Regular Holiday</option>
                                        <option value="special">Special Non-Working</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Description</label>
                                <textarea name="description" rows="2" x-model="editDescription"
                                          class="w-full border border-gray-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition resize-none"></textarea>
                            </div>

                            <label class="flex items-center gap-3 cursor-pointer select-none bg-amber-50 dark:bg-amber-900/20 rounded-xl px-4 py-3 border border-amber-100 dark:border-amber-900/40">
                                <input type="checkbox" name="is_recurring" value="1"
                                       x-model="editIsRecurring"
                                       class="w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <div>
                                    <span class="text-sm font-semibold text-amber-700 dark:text-amber-300">Recurring every year</span>
                                    <p class="text-xs text-amber-400 dark:text-amber-500 mt-0.5">This holiday repeats on the same date annually.</p>
                                </div>
                            </label>
                        </div>

                        <div class="bg-gray-50 dark:bg-slate-700/40 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100 dark:border-slate-700">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-amber-500 text-white text-sm font-bold rounded-xl hover:bg-amber-600 shadow transition">
                                Update Holiday
                            </button>
                            <button type="button" @click="showEditModal = false"
                                    class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
