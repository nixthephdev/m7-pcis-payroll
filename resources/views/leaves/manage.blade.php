<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('message'))
                <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200 text-green-600 text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <header class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900">Pending Approvals</h2>
                        <p class="mt-1 text-sm text-gray-600">Review and manage employee leave applications.</p>
                    </header>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                                <tr>
                                    <th class="px-6 py-3">Employee</th>
                                    <th class="px-6 py-3">Leave Details</th>
                                    <th class="px-6 py-3">Duration</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                    <th class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($leaves as $leave)
                                <tr class="hover:bg-gray-50 transition">
                                    
                                    <!-- Employee Info -->
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ optional($leave->employee->user)->name ?? 'Unknown' }}
                                        <div class="text-xs text-gray-500 font-normal mt-1">
                                            {{ $leave->employee->position ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <!-- Reason -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-1">
                                            {{ $leave->leave_type }}
                                        </span>
                                        <div class="text-gray-600 italic">"{{ $leave->reason }}"</div>
                                    </td>

                                    <!-- Dates -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} 
                                        - 
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Days
                                        </div>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-6 py-4 text-center">
                                        @if($leave->status == 'Pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($leave->status == 'Approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="px-6 py-4 text-center">
                                        @if($leave->status == 'Pending')
                                            <div class="flex justify-center gap-2">
                                                <!-- Approve -->
                                                <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-medium hover:underline">
                                                        Approve
                                                    </button>
                                                </form>
                                                
                                                <span class="text-gray-300">|</span>

                                                <!-- Reject -->
                                                <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium hover:underline">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Locked</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Empty State -->
                        @if($leaves->isEmpty())
                            <div class="text-center py-10">
                                <p class="text-gray-500">No leave requests found.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>