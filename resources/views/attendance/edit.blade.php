<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('attendance.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Edit Attendance Record') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                
                <!-- Header Band -->
                <div class="bg-gradient-to-r from-indigo-50 to-white dark:from-slate-700 dark:to-slate-800 px-8 py-6 border-b border-gray-100 dark:border-slate-600">
                    <div class="flex items-center gap-4">
                        <!-- Avatar Logic -->
                        @if($attendance->attendable && $attendance->attendable->user)
                            @if($attendance->attendable->user->avatar)
                                <img src="{{ asset('storage/' . $attendance->attendable->user->avatar) }}" class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-slate-500 shadow-sm">
                            @else
                                <div class="h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-lg">
                                    {{ substr($attendance->attendable->user->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $attendance->attendable->user->name }}</h3>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('l, F d, Y') }}
                                </p>
                            </div>
                        @else
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">?</div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Unknown User</h3>
                                <p class="text-sm text-red-500">User may have been deleted</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Time In -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Time In</label>
                            <input type="time" name="time_in" value="{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition cursor-pointer">
                        </div>

                        <!-- Time Out -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Time Out</label>
                            <input type="time" name="time_out" value="{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '' }}" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition cursor-pointer">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <div class="relative">
                                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition appearance-none">
                                    <option value="Present" {{ $attendance->status == 'Present' ? 'selected' : '' }}>✅ Present (On Time)</option>
                                    <option value="Late" {{ $attendance->status == 'Late' ? 'selected' : '' }}>⏰ Late</option>
                                    <option value="Half Day" {{ $attendance->status == 'Half Day' ? 'selected' : '' }}>🌓 Half Day</option>
                                    <option value="Absent" {{ $attendance->status == 'Absent' ? 'selected' : '' }}>❌ Absent</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                            <a href="{{ route('attendance.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>