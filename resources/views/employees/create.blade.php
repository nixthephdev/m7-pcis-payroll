<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="text-indigo-200 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Onboard New Employee') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
                
                <div class="bg-gradient-to-r from-indigo-50 to-white dark:from-slate-700 dark:to-slate-800 px-8 py-6 border-b border-gray-100 dark:border-slate-600">
                    <h3 class="text-lg font-bold text-indigo-900 dark:text-white">Employee 201 File & Access</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Please fill in the required information to generate the record.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('employees.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 rounded-lg text-sm">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- SECTION: IDENTITY -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Employee ID Number</label>
                                <input type="text" name="employee_code" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm" placeholder="e.g. PCIS00059">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" name="email" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm" placeholder="email@pcis.edu.ph">
                            </div>
                        </div>

                        <!-- FULL NAME SPLIT -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">First Name</label>
                                <input type="text" name="first_name" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Middle Name</label>
                                <input type="text" name="middle_name" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Last Name</label>
                                <input type="text" name="last_name" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                        </div>

                        <!-- PERSONAL DETAILS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Birthdate</label>
                                <input type="date" name="birthdate" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contact Number</label>
                                <input type="text" name="contact_number" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Complete Address</label>
                                <input type="text" name="address" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-slate-700 my-6">

                        <!-- SECTION: EMPLOYMENT -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Position</label>
                                <input type="text" name="position" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Direct Supervisor</label>
                                <select name="supervisor_id" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
    <option value="">-- None (Direct to HR) --</option>
    
    {{-- Use the filtered variable from the Controller --}}
    @foreach($supervisors as $sup)
        <option value="{{ $sup->id }}">
            {{ $sup->user->name }} ({{ $sup->position }})
        </option>
    @endforeach
    
</select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Basic Salary</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" step="0.01" name="basic_salary" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white pl-7 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm" placeholder="0.00">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assigned Schedule</label>
                                <select name="schedule_id" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                    <option value="" disabled selected>Select Schedule</option>
                                    @foreach(\App\Models\Schedule::all() as $schedule)
                                        <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- System Role & Leaves -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">System Role</label>
                                <select name="role" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                    <option value="employee">Regular Employee</option>
                                    <option value="guard">Security Guard</option>
                                    <option value="admin">HR / Administrator</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Vacation Credits</label>
                                <input type="number" name="vacation_credits" value="15" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sick Credits</label>
                                <input type="number" name="sick_credits" value="15" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                        </div>

                        <!-- GOVT IDS -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2">
                            <div><label class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 block">TIN</label><input type="text" name="tin_no" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-xs text-gray-900 dark:text-white"></div>
                            <div><label class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 block">SSS</label><input type="text" name="sss_no" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-xs text-gray-900 dark:text-white"></div>
                            <div><label class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 block">Philhealth</label><input type="text" name="philhealth_no" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-xs text-gray-900 dark:text-white"></div>
                            <div><label class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 block">Pag-Ibig</label><input type="text" name="pagibig_no" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 text-xs text-gray-900 dark:text-white"></div>
                        </div>

                        <!-- Default Password -->
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-100 dark:border-yellow-800/30 mt-6">
                            <label class="block text-xs font-bold text-yellow-700 dark:text-yellow-500 uppercase tracking-wider mb-2">Default Password</label>
                            <div class="flex items-center gap-3">
                                <input type="text" name="password" value="PCIS@2026" readonly class="w-full rounded-lg border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-400 cursor-not-allowed text-sm">
                                <span class="text-xs text-yellow-600 dark:text-yellow-500 italic">User can change this later.</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                Create & Continue to Profile
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>