<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Approvals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Pending Requests</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-white text-xs uppercase text-gray-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b">Employee</th>
                                <th class="px-6 py-4 border-b">Leave Details</th>
                                <th class="px-6 py-4 border-b">Duration</th>
                                <th class="px-6 py-4 border-b text-center">Status</th>
                                <th class="px-6 py-4 border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($leaves as $leave)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ optional($leave->employee->user)->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400">{{ $leave->employee->position ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700 mb-1">{{ $leave->leave_type }}</span>
                                    <p class="italic text-gray-500">"{{ $leave->reason }}"</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Days</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($leave->status == 'Pending')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                                    @elseif($leave->status == 'Approved')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Approved</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($leave->status == 'Pending')
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Approved">
                                                <button class="p-2 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200 transition" title="Approve">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Rejected">
                                                <button class="p-2 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-200 transition" title="Reject">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-300 font-medium uppercase tracking-wider">Closed</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($leaves->isEmpty())
                        <div class="text-center py-12 text-gray-400">No pending requests.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>