<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Attendance Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-bold mb-4">
                        Edit Log: {{ $attendance->employee->user->name }}
                        <span class="text-sm font-normal text-gray-500 block">
                            Date: {{ \Carbon\Carbon::parse($attendance->date)->format('F d, Y') }}
                        </span>
                    </h3>

                    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Time In -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Time In</label>
                            <input type="time" name="time_in" value="{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Time Out -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Time Out</label>
                            <input type="time" name="time_out" value="{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Present" {{ $attendance->status == 'Present' ? 'selected' : '' }}>Present (On Time)</option>
                                <option value="Late" {{ $attendance->status == 'Late' ? 'selected' : '' }}>Late</option>
                                <option value="Half Day" {{ $attendance->status == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                                <option value="Absent" {{ $attendance->status == 'Absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('attendance.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>