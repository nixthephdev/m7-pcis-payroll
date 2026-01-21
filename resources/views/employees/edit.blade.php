
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                Edit Employee: <span class="text-indigo-600">{{ $employee->user->name }}</span>
            </h2>
            <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-slate-600 rounded-lg text-sm font-bold hover:bg-gray-50 dark:hover:bg-slate-600 transition shadow-sm">
                &larr; Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
    activeTab: '{{ session('active_tab') ?? (
        $errors->has('school_name') || $errors->has('diploma') ? 'education' : 
        ($errors->has('relation') ? 'family' : 
        ($errors->has('title') || $errors->has('certificate') ? 'training' : 
        ($errors->has('condition') ? 'health' : 'job'))) 
    ) }}' 
}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- SUCCESS NOTIFICATION -->
            @if(session('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     class="mb-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-lg relative shadow-sm flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline text-sm">{{ session('message') }}</span>
                    </div>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="show = false">
                        <svg class="fill-current h-5 w-5 text-emerald-600 dark:text-emerald-400" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </span>
                </div>
            @endif

            <!-- TABS NAVIGATION -->
            <div class="flex space-x-1 bg-white dark:bg-slate-800 p-1.5 rounded-xl shadow-sm mb-8 overflow-x-auto border border-gray-200 dark:border-slate-700">
                <button @click="activeTab = 'job'" :class="activeTab === 'job' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Employment</button>
                <button @click="activeTab = 'personal'" :class="activeTab === 'personal' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Personal (201)</button>
                <button @click="activeTab = 'education'" :class="activeTab === 'education' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Education</button>
                <button @click="activeTab = 'training'" :class="activeTab === 'training' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Trainings</button>
                <button @click="activeTab = 'family'" :class="activeTab === 'family' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Family</button>
                <button @click="activeTab = 'health'" :class="activeTab === 'health' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Health</button>
                <button @click="activeTab = 'salary'" :class="activeTab === 'salary' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Salary</button>
            </div>

            <!-- ========================================== -->
            <!-- TAB 1: EMPLOYMENT DETAILS (PREMIUM LAYOUT) -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'job'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-8">
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                        @csrf @method('PUT')

                        <!-- SECTION 1: IDENTITY & ACCESS -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">System Access & Identity</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Employee ID</label>
                                    <input type="text" name="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $employee->user->email) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">System Role</label>
                                    <select name="role" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                        <option value="employee" {{ $employee->user->role == 'employee' ? 'selected' : '' }}>Regular Employee</option>
                                        <option value="admin" {{ $employee->user->role == 'admin' ? 'selected' : '' }}>HR / Admin</option>
                                        <option value="guard" {{ $employee->user->role == 'guard' ? 'selected' : '' }}>Security Guard</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: JOB DETAILS -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Job Position & Schedule</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Job Position</label>
                                    <input type="text" name="position" value="{{ old('position', $employee->position) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Direct Supervisor</label>
                                    <select name="supervisor_id" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
    <option value="">-- None (Direct to HR) --</option>
    
    {{-- Loop through the FILTERED variable passed from Controller --}}
    @foreach($supervisors as $sup)
        <option value="{{ $sup->id }}" {{ $employee->supervisor_id == $sup->id ? 'selected' : '' }}>
            {{ $sup->user->name }} ({{ $sup->position }})
        </option>
    @endforeach
    
</select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Assigned Schedule</label>
                                    <select name="schedule_id" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                        <option value="">-- Select Schedule --</option>
                                        @foreach(\App\Models\Schedule::all() as $sched)
                                            <option value="{{ $sched->id }}" {{ $employee->schedule_id == $sched->id ? 'selected' : '' }}>{{ $sched->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: COMPENSATION -->
                        <div>
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Compensation & Leaves</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Current Basic Salary</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">₱</span>
                                        </div>
                                        <input type="text" value="{{ number_format($employee->basic_salary, 2) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-gray-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 cursor-not-allowed pl-7 sm:text-sm py-2.5 focus:border-gray-300 focus:ring-0" readonly>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-[10px] text-indigo-500 dark:text-indigo-400 font-medium">*To change salary, use the "Salary" tab.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Vacation Credits</label>
                                    <input type="number" name="vacation_credits" value="{{ old('vacation_credits', $employee->vacation_credits) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Sick Credits</label>
                                    <input type="number" name="sick_credits" value="{{ old('sick_credits', $employee->sick_credits) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-8 mt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold text-sm shadow-md transition-all hover:-translate-y-0.5">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 2: PERSONAL INFO (201) (PREMIUM LAYOUT)-->
            <!-- ========================================== -->
            <div x-show="activeTab === 'personal'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-8">
                    <form action="{{ route('employees.updatePersonal', $employee->id) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <!-- SECTION 1: FULL NAME -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Full Legal Name</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">First Name</label>
                                    <input type="text" name="first_name" value="{{ explode(' ', $employee->user->name)[0] }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ $employee->middle_name }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Last Name</label>
                                    <input type="text" name="last_name" value="{{ explode(' ', $employee->user->name)[1] ?? '' }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DEMOGRAPHICS -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Demographics & Contact</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Birthdate</label>
                                    <input type="date" name="birthdate" value="{{ $employee->birthdate }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Contact No.</label>
                                    <input type="text" name="contact_number" value="{{ $employee->contact_number }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Complete Home Address</label>
                                    <input type="text" name="address" value="{{ $employee->address }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: GOVT IDS -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Government Identification</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">TIN Number</label>
                                    <input type="text" name="tin_no" value="{{ $employee->tin_no }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">SSS Number</label>
                                    <input type="text" name="sss_no" value="{{ $employee->sss_no }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Philhealth No.</label>
                                    <input type="text" name="philhealth_no" value="{{ $employee->philhealth_no }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Pag-Ibig No.</label>
                                    <input type="text" name="pagibig_no" value="{{ $employee->pagibig_no }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: EXTRA -->
                        <div>
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Additional Information</h4>
                            <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Special Interests / Hobbies</label>
                            <textarea name="hobbies" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" rows="3">{{ $employee->hobbies }}</textarea>
                        </div>

                        <div class="text-right border-t border-gray-100 dark:border-slate-700 pt-8 mt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold text-sm shadow-md transition-all hover:-translate-y-0.5">Save Personal Info</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 3: EDUCATION -->
            <div x-show="activeTab === 'education'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-8 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Educational Background</h3>
                </div>
                
                <div class="p-8">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 mb-8">
                        <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                            <tr><th class="px-4 py-3 rounded-l-md">Level</th><th class="px-4 py-3">School Name</th><th class="px-4 py-3">Date Graduated</th><th class="px-4 py-3 rounded-r-md">Diploma</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($employee->education as $edu)
                            <tr>
                                <td class="px-4 py-3">{{ $edu->level }}</td>
                                <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $edu->school_name }}</td>
                                <td class="px-4 py-3">{{ $edu->date_graduated ? \Carbon\Carbon::parse($edu->date_graduated)->format('M d, Y') : '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($edu->diploma_path) <a href="{{ asset('storage/'.$edu->diploma_path) }}" target="_blank" class="text-indigo-600 hover:underline">View</a> @else <span class="text-gray-400">-</span> @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Premium Add Form -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add New Education</h4>
                        <form action="{{ route('employees.storeEducation', $employee->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Level</label>
                                <select name="level" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option>Primary</option><option>Secondary</option><option>Tertiary</option><option>Post Grad</option><option>PhD</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">School Name</label>
                                <input type="text" name="school_name" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Date Graduated</label>
                                <input type="date" name="date_graduated" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-3">
    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Attach Diploma</label>
    <input type="file" name="diploma" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
    <!-- ADD THIS LINE: -->
    <p class="text-[10px] text-gray-400 mt-1">Max size: 10MB. Formats: PDF, JPG, PNG.</p>
</div>
                            <div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-bold text-sm shadow-sm transition-all">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 4: TRAININGS & LICENSES -->
            <div x-show="activeTab === 'training'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-8 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Trainings & Licenses</h3>
                </div>
                <div class="p-8">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 mb-8">
                        <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                            <tr><th class="px-4 py-3 rounded-l-md">Type</th><th class="px-4 py-3">Title / License</th><th class="px-4 py-3">Dates</th><th class="px-4 py-3 rounded-r-md">Certificate</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($employee->trainings as $training)
                            <tr>
                                <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs font-bold {{ $training->type == 'License' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">{{ $training->type }}</span></td>
                                <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $training->title }}</td>
                                <td class="px-4 py-3">
                                    {{ $training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('M d, Y') : '' }}
                                    {{ $training->end_date ? ' - ' . \Carbon\Carbon::parse($training->end_date)->format('M d, Y') : '' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($training->certificate_path) <a href="{{ asset('storage/'.$training->certificate_path) }}" target="_blank" class="text-indigo-600 hover:underline">View</a> @else - @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Premium Add Form -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Training / License</h4>
                        <form action="{{ route('employees.storeTraining', $employee->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Type</label>
                                <select name="type" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option>Training</option><option>License</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Title / License Name</label>
                                <input type="text" name="title" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Start Date</label>
                                <input type="date" name="start_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">End Date (Opt)</label>
                                <input type="date" name="end_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-2">
    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Certificate</label>
    <input type="file" name="certificate" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
    <!-- ADD THIS LINE: -->
    <p class="text-[10px] text-gray-400 mt-1">Max size: 10MB. Formats: PDF, JPG, PNG.</p>
</div>
                            <div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-bold text-sm shadow-sm transition-all">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 5: FAMILY -->
            <div x-show="activeTab === 'family'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-8 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Family Background</h3>
                </div>
                <div class="p-8">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 mb-8">
                        <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                            <tr><th class="px-4 py-3 rounded-l-md">Relation</th><th class="px-4 py-3">Name</th><th class="px-4 py-3">Birthdate</th><th class="px-4 py-3 rounded-r-md">Occupation</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($employee->family as $fam)
                            <tr>
                                <td class="px-4 py-3">{{ $fam->relation }}</td>
                                <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $fam->name }}</td>
                                <td class="px-4 py-3">{{ $fam->birthdate ? \Carbon\Carbon::parse($fam->birthdate)->format('M d, Y') : '-' }}</td>
                                <td class="px-4 py-3">{{ $fam->occupation }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Premium Add Form -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Family Member</h4>
                        <form action="{{ route('employees.storeFamily', $employee->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Relation</label>
                                <select name="relation" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option>Spouse</option><option>Child</option><option>Father</option><option>Mother</option><option>Sibling</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Full Name</label>
                                <input type="text" name="name" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Birthdate</label>
                                <input type="date" name="birthdate" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Occupation</label>
                                <input type="text" name="occupation" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-bold text-sm shadow-sm transition-all">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 6: HEALTH & WELLNESS -->
            <div x-show="activeTab === 'health'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-8 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Health & Wellness</h3>
                </div>
                <div class="p-8">
                    <!-- Mental Health Section -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 dark:text-white mb-2">Mental Health Notes</label>
                        <form action="{{ route('employees.updateHealthNotes', $employee->id) }}" method="POST">
                            @csrf @method('PUT')
                            <textarea name="mental_health" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" rows="3" placeholder="Enter mental health notes or history here...">{{ $employee->mental_health }}</textarea>
                            <div class="text-right mt-3"><button type="submit" class="text-xs bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 shadow-sm">Save Notes</button></div>
                        </form>
                    </div>

                    <h4 class="font-bold text-gray-700 dark:text-white mb-4">Physical Conditions</h4>
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 mb-8">
                        <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                            <tr><th class="px-4 py-3 rounded-l-md">Condition</th><th class="px-4 py-3">Date Diagnosed</th><th class="px-4 py-3">Medication</th><th class="px-4 py-3 rounded-r-md">Dosage</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($employee->health as $h)
                            <tr>
                                <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $h->condition }}</td>
                                <td class="px-4 py-3">{{ $h->date_diagnosed ? \Carbon\Carbon::parse($h->date_diagnosed)->format('M d, Y') : '-' }}</td>
                                <td class="px-4 py-3">{{ $h->medication }}</td>
                                <td class="px-4 py-3">{{ $h->dosage }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Premium Add Form -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Pre-existing Condition</h4>
                        <form action="{{ route('employees.storeHealth', $employee->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Condition</label>
                                <input type="text" name="condition" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Date Diagnosed</label>
                                <input type="date" name="date_diagnosed" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Medication</label>
                                <input type="text" name="medication" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Dosage</label>
                                <input type="text" name="dosage" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-bold text-sm shadow-sm transition-all">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 7: SALARY HISTORY -->
            <div x-show="activeTab === 'salary'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="flex justify-between items-center p-8 border-b border-gray-100 dark:border-slate-700">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Salary Progression</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track increments and promotions.</p>
                    </div>
                    <div class="text-right">
                         <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wide">Current Basic Salary</p>
                         <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($employee->basic_salary, 2) }}</p>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Update Salary Form -->
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-xl p-6 mb-8">
                        <h4 class="font-bold text-sm text-indigo-900 dark:text-indigo-200 mb-4 flex items-center gap-2 uppercase tracking-wide">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            Promote / Update Salary
                        </h4>
                        <form action="{{ route('employees.updateSalary', $employee->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-wide mb-1">New Salary Amount</label>
                                <input type="number" step="0.01" name="new_salary" class="block w-full rounded-md border-indigo-200 dark:border-indigo-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-wide mb-1">Effective Date</label>
                                <input type="date" name="effective_date" class="block w-full rounded-md border-indigo-200 dark:border-indigo-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-wide mb-1">Reason (e.g. Annual Increase)</label>
                                <div class="flex gap-4">
                                    <input type="text" name="reason" class="block w-full rounded-md border-indigo-200 dark:border-indigo-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-bold text-sm shadow-sm transition-all whitespace-nowrap">Update Salary</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- History Table -->
                    <h4 class="font-bold text-gray-700 dark:text-white mb-4">History Log</h4>
                    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-slate-700">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                            <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Effective Date</th>
                                    <th class="px-6 py-3">Reason</th>
                                    <th class="px-6 py-3 text-right">Previous</th>
                                    <th class="px-6 py-3 text-right">New Salary</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @foreach($employee->salaryHistory as $history)
                                <tr>
                                    <td class="px-6 py-3">{{ \Carbon\Carbon::parse($history->effective_date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $history->reason }}</td>
                                    <td class="px-6 py-3 text-right text-gray-400">₱{{ number_format($history->previous_salary, 2) }}</td>
                                    <td class="px-6 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($history->new_salary, 2) }}</td>
                                </tr>
                                @endforeach
                                @if($employee->salaryHistory->isEmpty())
                                    <tr>
                                        <td colspan="4" class="px-6 py-6 text-center text-gray-400 italic">No salary history recorded yet.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>