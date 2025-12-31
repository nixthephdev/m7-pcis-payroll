<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Leave Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-bold">{{ session('message') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT: File New Request -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        File a Request
                    </h3>
                    
                    <form action="{{ route('leave.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                            <select name="leave_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Vacation Leave">Vacation Leave</option>
                                <option value="Emergency Leave">Emergency Leave</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <textarea name="reason" rows="3" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Brief explanation..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition transform hover:scale-[1.02]">
                            Submit Request
                        </button>
                    </form>
                </div>

                <!-- RIGHT: History Table -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Request History</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-white text-xs uppercase text-gray-400 font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 border-b">Type</th>
                                    <th class="px-6 py-4 border-b">Dates</th>
                                    <th class="px-6 py-4 border-b">Reason</th>
                                    <th class="px-6 py-4 border-b text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($leaves as $leave)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-indigo-900">{{ $leave->leave_type }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span>
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Days</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 italic text-gray-500">"{{ Str::limit($leave->reason, 30) }}"</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($leave->status == 'Pending')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                                        @elseif($leave->status == 'Approved')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Approved</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($leaves->isEmpty())
                            <div class="text-center py-12 text-gray-400">No leave requests found.</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>