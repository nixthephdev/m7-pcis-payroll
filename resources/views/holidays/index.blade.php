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

        {{-- Header Row --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-white leading-tight">Holiday Management</h1>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Manage public holidays and special non-working days.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                {{-- Year Filter --}}
                <form method="GET" action="{{ route('holidays.index') }}">
                    <div class="flex items-center gap-2 border border-gray-200 dark:border-slate-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <select name="year" onchange="this.form.submit()"
                                class="text-sm bg-transparent text-gray-700 dark:text-gray-200 focus:outline-none cursor-pointer">
                            @foreach($years->merge([now()->year])->unique()->sortDesc() as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <button @click="showAddModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Holiday
                </button>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 flex items-center justify-between">
                <h3 class="font-bold text-gray-700 dark:text-white">Holiday List</h3>
                <span class="text-xs text-gray-400 dark:text-gray-500">
                    {{ $holidays->count() }} holiday(s) &bull; {{ $year }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-white dark:bg-slate-800 text-xs uppercase text-gray-400 dark:text-gray-500 font-bold">
                        <tr>
                            <th class="px-5 py-4">Name</th>
                            <th class="px-5 py-4">Date</th>
                            <th class="px-5 py-4 text-center">Type</th>
                            <th class="px-5 py-4 text-center">Recurring</th>
                            <th class="px-5 py-4">Description</th>
                            <th class="px-5 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($holidays as $holiday)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-5 py-4 font-semibold text-gray-800 dark:text-white">
                                {{ $holiday->name }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($holiday->is_recurring)
                                    <span class="text-gray-500 dark:text-gray-400">
                                        Every {{ $holiday->date->format('F j') }}
                                    </span>
                                @else
                                    {{ $holiday->date->format('F j, Y') }}
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($holiday->type === 'regular')
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                        Regular
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                        Special
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($holiday->is_recurring)
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        Yes
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $holiday->description ?? '—' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit --}}
                                    <button @click="openEdit(
                                                '{{ $holiday->id }}',
                                                '{{ addslashes($holiday->name) }}',
                                                '{{ $holiday->date->format('Y-m-d') }}',
                                                '{{ $holiday->type }}',
                                                '{{ addslashes($holiday->description ?? '') }}',
                                                {{ $holiday->is_recurring ? 'true' : 'false' }}
                                            )"
                                            class="p-1.5 rounded-lg text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('holidays.destroy', $holiday->id) }}"
                                          onsubmit="return confirm('Remove {{ addslashes($holiday->name) }} from holidays?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition"
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
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500">
                                No holidays declared for {{ $year }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="px-6 py-2 text-xs text-gray-400 dark:text-gray-500 border-t dark:border-slate-700">
                * Recurring holidays apply every year on the same date regardless of the selected year.
            </p>
        </div>

        {{-- ADD MODAL --}}
        <div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-gray-900 opacity-75" @click="showAddModal = false"></div>

                <div x-show="showAddModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg z-10">

                    <form method="POST" action="{{ route('holidays.store') }}">
                        @csrf
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Add Holiday</h3>
                                    <p class="text-xs text-gray-400">Declare a new public holiday or non-working day.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Holiday Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" required placeholder="e.g. Christmas Day"
                                           class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Date <span class="text-rose-500">*</span></label>
                                        <input type="date" name="date" required
                                               class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Type <span class="text-rose-500">*</span></label>
                                        <select name="type" required
                                                class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                            <option value="regular">Regular Holiday</option>
                                            <option value="special">Special Non-Working</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Description</label>
                                    <textarea name="description" rows="2" placeholder="Optional notes..."
                                              class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                                </div>

                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="is_recurring" value="1"
                                           class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Recurring every year</span>
                                        <p class="text-xs text-gray-400">This holiday repeats on the same date annually.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-slate-700/50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit"
                                    class="px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 shadow transition">
                                Save Holiday
                            </button>
                            <button type="button" @click="showAddModal = false"
                                    class="px-5 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition">
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
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-900 opacity-75" @click="showEditModal = false"></div>

                <div x-show="showEditModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg z-10">

                    <form method="POST" :action="`/holidays/${editId}`">
                        @csrf
                        @method('PUT')

                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="p-2 bg-amber-100 dark:bg-amber-900/40 rounded-lg">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Edit Holiday</h3>
                                    <p class="text-xs text-gray-400">Update holiday details.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Holiday Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" required x-model="editName"
                                           class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Date <span class="text-rose-500">*</span></label>
                                        <input type="date" name="date" required x-model="editDate"
                                               class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Type <span class="text-rose-500">*</span></label>
                                        <select name="type" required x-model="editType"
                                                class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                            <option value="regular">Regular Holiday</option>
                                            <option value="special">Special Non-Working</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Description</label>
                                    <textarea name="description" rows="2" x-model="editDescription"
                                              class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                                </div>

                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" name="is_recurring" value="1"
                                           x-model="editIsRecurring"
                                           class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Recurring every year</span>
                                        <p class="text-xs text-gray-400">This holiday repeats on the same date annually.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-slate-700/50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit"
                                    class="px-5 py-2 bg-amber-500 text-white text-sm font-bold rounded-lg hover:bg-amber-600 shadow transition">
                                Update Holiday
                            </button>
                            <button type="button" @click="showEditModal = false"
                                    class="px-5 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
