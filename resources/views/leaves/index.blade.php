<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Leave Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('message') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- LEFT COLUMN: FILE LEAVE FORM -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold text-indigo-900 mb-4">File a Request</h3>
                            
                            <form action="{{ route('leave.store') }}" method="POST">
                                @csrf
                                
                                <!-- Leave Type -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Leave Type</label>
                                    <select name="leave_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Vacation Leave">Vacation Leave</option>
                                        <option value="Emergency Leave">Emergency Leave</option>
                                    </select>
                                </div>

                                <!-- Dates -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" name="start_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" name="end_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Reason -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Reason</label>
                                    <textarea name="reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                                    Submit Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: HISTORY TABLE -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-700 mb-4">My Leave History</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                            <th class="py-3 px-6">Type</th>
                                            <th class="py-3 px-6">Dates</th>
                                            <th class="py-3 px-6">Reason</th>
                                            <th class="py-3 px-6 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm font-light">
                                        @foreach($leaves as $leave)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="py-3 px-6 font-bold">{{ $leave->leave_type }}</td>
                                            <td class="py-3 px-6">
                                                {{ $leave->start_date }} <span class="text-gray-400">to</span> {{ $leave->end_date }}
                                            </td>
                                            <td class="py-3 px-6">{{ $leave->reason }}</td>
                                            <td class="py-3 px-6 text-center">
                                                @if($leave->status == 'Pending')
                                                    <span class="bg-yellow-200 text-yellow-700 py-1 px-3 rounded-full text-xs">Pending</span>
                                                @elseif($leave->status == 'Approved')
                                                    <span class="bg-green-200 text-green-700 py-1 px-3 rounded-full text-xs">Approved</span>
                                                @else
                                                    <span class="bg-red-200 text-red-700 py-1 px-3 rounded-full text-xs">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($leaves->isEmpty())
                                    <p class="text-center text-gray-400 mt-4">No leave requests found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>