<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                My Profile
            </h2>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-slate-600 rounded-lg text-sm font-bold hover:bg-gray-50 dark:hover:bg-slate-600 transition shadow-sm">
                &larr; Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        activeTab: '{{ session('active_tab') ?? (
            $errors->has('school_name') ? 'education' :
            ($errors->has('relation') ? 'family' :
            ($errors->has('title') ? 'training' :
            ($errors->has('condition') ? 'health' : 'personal')))
        ) }}'
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-lg relative shadow-sm flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong class="font-bold">Saved!</strong>
                        <span class="block sm:inline text-sm">{{ session('message') }}</span>
                    </div>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="show = false">
                        <svg class="fill-current h-5 w-5 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </span>
                </div>
            @endif

            {{-- Profile Header Card --}}
            <div class="mb-6 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl shadow-lg p-6 flex items-center gap-6 text-white">
                <div class="flex-shrink-0">
                    @if($employee->photo_path)
                        <img src="{{ asset('storage/'.$employee->photo_path) }}" alt="Photo" class="w-20 h-20 rounded-full object-cover object-top ring-2 ring-white/40 shadow-lg" style="image-rendering:auto;">
                    @else
                        <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30">
                            <svg class="w-10 h-10 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-extrabold leading-tight">{{ $employee->user->name }}</h3>
                    <p class="text-indigo-200 text-sm mt-0.5">{{ $employee->position }}</p>
                    <p class="text-indigo-300 text-xs mt-1">
                        ID: <span class="font-bold text-white">{{ $employee->employee_code }}</span>
                        @if($employee->schedule)
                            &nbsp;·&nbsp; Schedule: <span class="font-bold text-white">{{ $employee->schedule->name }}</span>
                            ({{ \Carbon\Carbon::parse($employee->schedule->time_in)->format('h:i A') }} – {{ \Carbon\Carbon::parse($employee->schedule->time_out)->format('h:i A') }})
                        @endif
                    </p>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-xs text-indigo-300 uppercase font-bold tracking-wide">Basic Salary</p>
                    <p class="text-3xl font-extrabold text-white">₱{{ number_format($employee->basic_salary, 2) }}</p>
                    <p class="text-xs text-indigo-300 mt-0.5">per month</p>
                </div>
            </div>

            <!-- TABS NAVIGATION -->
            <div class="flex space-x-1 bg-white dark:bg-slate-800 p-1.5 rounded-xl shadow-sm mb-8 overflow-x-auto border border-gray-200 dark:border-slate-700">
                <button @click="activeTab = 'personal'"  :class="activeTab === 'personal'  ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Personal (201)</button>
                <button @click="activeTab = 'education'" :class="activeTab === 'education' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Education & Work History</button>
                <button @click="activeTab = 'training'"  :class="activeTab === 'training'  ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Trainings & Licenses</button>
                <button @click="activeTab = 'family'"    :class="activeTab === 'family'    ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Family</button>
                <button @click="activeTab = 'health'"    :class="activeTab === 'health'    ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Health</button>
                <button @click="activeTab = 'salary'"    :class="activeTab === 'salary'    ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap">Salary History</button>
            </div>

            {{-- ======================================================= --}}
            {{-- TAB 1: PERSONAL (201) --}}
            {{-- ======================================================= --}}
            <div x-show="activeTab === 'personal'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-8">

                    {{-- Profile Photo (display only — HR/Admin manages uploads) --}}
                    <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                        <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Profile Photo</h4>
                        <div class="flex items-center gap-6">
                            <div class="flex-shrink-0">
                                @if($employee->photo_path)
                                    <img src="{{ asset('storage/'.$employee->photo_path) }}" alt="Photo" class="w-20 h-20 rounded-full object-cover object-top ring-2 ring-indigo-300 dark:ring-indigo-600 shadow" style="image-rendering:auto;">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center border-4 border-dashed border-slate-300 dark:border-slate-600">
                                        <svg class="w-9 h-9 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <p class="text-sm text-gray-400 dark:text-gray-500 italic">Photo is managed by HR / Admin.</p>
                        </div>
                    </div>

                    {{-- Personal Details Form --}}
                    <form action="{{ route('employee.updateMyPersonal') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <!-- Full Name -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Full Legal Name</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">First Name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Last Name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                        </div>

                        <!-- Birth & Demographics -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Birth & Demographics</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Birthdate</label>
                                    <input type="date" name="birthdate" value="{{ old('birthdate', $employee->birthdate) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Birth Place</label>
                                    <input type="text" name="birthplace" value="{{ old('birthplace', $employee->birthplace) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Marital Status</label>
                                    <select name="marital_status" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                        <option value="">-- Select --</option>
                                        <option value="Single"    {{ old('marital_status', $employee->marital_status) == 'Single'    ? 'selected' : '' }}>Single</option>
                                        <option value="Married"   {{ old('marital_status', $employee->marital_status) == 'Married'   ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed"   {{ old('marital_status', $employee->marital_status) == 'Widowed'   ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ old('marital_status', $employee->marital_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">
                                        Birth Certificate
                                        @if($employee->birth_certificate_path)
                                            — <a href="{{ asset('storage/'.$employee->birth_certificate_path) }}" target="_blank" class="text-indigo-500 hover:underline normal-case font-normal">View Current</a>
                                        @endif
                                    </label>
                                    <input type="file" name="birth_certificate" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Personal Contact Number</label>
                                    <input type="text" name="contact_number" value="{{ old('contact_number', $employee->contact_number) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Personal Email Address</label>
                                    <input type="email" name="personal_email" value="{{ old('personal_email', $employee->personal_email) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Complete Home Address</label>
                                    <input type="text" name="address" value="{{ old('address', $employee->address) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>
                        </div>

                        <!-- Interests -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Personal Interests</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Special Interests</label>
                                    <textarea name="special_interests" rows="3" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('special_interests', $employee->special_interests) }}</textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Hobbies</label>
                                    <textarea name="hobbies" rows="3" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('hobbies', $employee->hobbies) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Government IDs -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Government Identification & 201 Documents</h4>
                            <div class="rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden divide-y divide-gray-100 dark:divide-slate-700">

                                {{-- NBI --}}
                                <div class="flex items-center gap-4 px-5 py-4 bg-white dark:bg-slate-800 hover:bg-gray-50/60 dark:hover:bg-slate-700/40 transition">
                                    <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">NBI Clearance</p>
                                        <input type="file" name="nbi_clearance" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/50 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 cursor-pointer">
                                    </div>
                                    @if($employee->nbi_clearance_path)
                                    <a href="{{ asset('storage/'.$employee->nbi_clearance_path) }}" target="_blank" class="flex-shrink-0 inline-flex items-center gap-1 text-xs font-bold text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View
                                    </a>
                                    @endif
                                </div>

                                {{-- TIN --}}
                                <div class="px-5 py-4 bg-white dark:bg-slate-800 hover:bg-gray-50/60 dark:hover:bg-slate-700/40 transition">
                                    <div class="flex items-start gap-4">
                                        <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                                        </div>
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">TIN Number</label>
                                                <input type="text" name="tin_no" value="{{ old('tin_no', $employee->tin_no) }}" class="block w-full rounded-lg border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">
                                                    Attach TIN Proof
                                                    @if($employee->tin_proof_path) &nbsp;<a href="{{ asset('storage/'.$employee->tin_proof_path) }}" target="_blank" class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 normal-case font-normal">— View</a> @endif
                                                </label>
                                                <input type="file" name="tin_proof" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-900/50 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 cursor-pointer">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SSS --}}
                                <div class="px-5 py-4 bg-white dark:bg-slate-800 hover:bg-gray-50/60 dark:hover:bg-slate-700/40 transition">
                                    <div class="flex items-start gap-4">
                                        <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                        </div>
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">SSS Number</label>
                                                <input type="text" name="sss_no" value="{{ old('sss_no', $employee->sss_no) }}" class="block w-full rounded-lg border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">
                                                    Attach SSS Proof
                                                    @if($employee->sss_proof_path) &nbsp;<a href="{{ asset('storage/'.$employee->sss_proof_path) }}" target="_blank" class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 normal-case font-normal">— View</a> @endif
                                                </label>
                                                <input type="file" name="sss_proof" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/50 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 cursor-pointer">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PhilHealth --}}
                                <div class="px-5 py-4 bg-white dark:bg-slate-800 hover:bg-gray-50/60 dark:hover:bg-slate-700/40 transition">
                                    <div class="flex items-start gap-4">
                                        <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                        </div>
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">PhilHealth No.</label>
                                                <input type="text" name="philhealth_no" value="{{ old('philhealth_no', $employee->philhealth_no) }}" class="block w-full rounded-lg border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">
                                                    Attach PhilHealth Proof
                                                    @if($employee->philhealth_proof_path) &nbsp;<a href="{{ asset('storage/'.$employee->philhealth_proof_path) }}" target="_blank" class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 normal-case font-normal">— View</a> @endif
                                                </label>
                                                <input type="file" name="philhealth_proof" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-50 dark:file:bg-rose-900/50 file:text-rose-700 dark:file:text-rose-300 hover:file:bg-rose-100 cursor-pointer">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pag-Ibig --}}
                                <div class="px-5 py-4 bg-white dark:bg-slate-800 hover:bg-gray-50/60 dark:hover:bg-slate-700/40 transition">
                                    <div class="flex items-start gap-4">
                                        <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                        </div>
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">Pag-Ibig No.</label>
                                                <input type="text" name="pagibig_no" value="{{ old('pagibig_no', $employee->pagibig_no) }}" class="block w-full rounded-lg border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 block">
                                                    Attach Pag-Ibig Proof
                                                    @if($employee->pagibig_proof_path) &nbsp;<a href="{{ asset('storage/'.$employee->pagibig_proof_path) }}" target="_blank" class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 normal-case font-normal">— View</a> @endif
                                                </label>
                                                <input type="file" name="pagibig_proof" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 dark:file:bg-amber-900/50 file:text-amber-700 dark:file:text-amber-300 hover:file:bg-amber-100 cursor-pointer">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Bank Account -->
                        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-4">Bank Account Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Bank Name</label>
                                    <input type="text" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}" placeholder="e.g. BDO, BPI, UnionBank" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Account Name</label>
                                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $employee->bank_account_name) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">Account Number</label>
                                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}" class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 block">
                                        Attach Proof of Bank Account
                                        @if($employee->bank_proof_path) — <a href="{{ asset('storage/'.$employee->bank_proof_path) }}" target="_blank" class="text-indigo-500 hover:underline normal-case font-normal">View Current</a> @endif
                                    </label>
                                    <input type="file" name="bank_proof" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                </div>
                            </div>
                        </div>

                        <div class="text-right border-t border-gray-100 dark:border-slate-700 pt-8 mt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold text-sm shadow-md transition-all hover:-translate-y-0.5">Save Personal Info</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- TAB 2: EDUCATION & EMPLOYMENT HISTORY --}}
            {{-- ======================================================= --}}
            <div x-show="activeTab === 'education'" class="space-y-6">

                <div x-data="{ showEduModal: false, edu: {} }" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Educational Background</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-md">Level</th>
                                        <th class="px-4 py-3">School Name</th>
                                        <th class="px-4 py-3">Date Graduated</th>
                                        <th class="px-4 py-3">Diploma</th>
                                        <th class="px-4 py-3">Transcript (TOR)</th>
                                        <th class="px-4 py-3 rounded-r-md"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @forelse($employee->education as $edu)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $edu->level }}</td>
                                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $edu->school_name }}</td>
                                        <td class="px-4 py-3">{{ $edu->date_graduated ? \Carbon\Carbon::parse($edu->date_graduated)->format('m/d/Y') : '-' }}</td>
                                        <td class="px-4 py-3">
                                            @if($edu->diploma_path) <a href="{{ asset('storage/'.$edu->diploma_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold">View</a> @else <span class="text-gray-400">—</span> @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($edu->tor_path) <a href="{{ asset('storage/'.$edu->tor_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold">View</a> @else <span class="text-gray-400">—</span> @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="edu = {{ json_encode(['id' => $edu->id, 'level' => $edu->level, 'school_name' => $edu->school_name, 'date_graduated' => $edu->date_graduated ?? '', 'has_diploma' => (bool)$edu->diploma_path, 'has_tor' => (bool)$edu->tor_path]) }}; showEduModal = true" title="Edit" class="text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-400 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <form method="POST" action="{{ route('employee.destroyMyEducation', $edu->id) }}" onsubmit="return confirm('Delete this education record?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete" class="text-red-500 hover:text-red-700 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">No education records yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Edit Education Modal --}}
                        <div x-show="showEduModal" style="display:none" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 p-4" @keydown.escape.window="showEduModal = false">
                            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg">
                                <div class="flex items-center justify-between p-5 border-b dark:border-slate-700">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit Education Record</h3>
                                    <button @click="showEduModal = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl leading-none">&times;</button>
                                </div>
                                <form :action="'/my-profile/education/' + edu.id" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Level</label>
                                            <select name="level" x-model="edu.level" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option>Primary</option><option>Secondary</option><option>Tertiary</option><option>Post Degree</option><option>Ph Degree</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Date Graduated</label>
                                            <input type="date" name="date_graduated" x-model="edu.date_graduated" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">School Name</label>
                                            <input type="text" name="school_name" x-model="edu.school_name" required class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Replace Diploma <span class="font-normal text-gray-400">(optional)</span></label>
                                            <input type="file" name="diploma" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                            <p x-show="edu.has_diploma" class="text-[10px] text-gray-400 mt-1">Current file kept if none uploaded.</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Replace TOR <span class="font-normal text-gray-400">(optional)</span></label>
                                            <input type="file" name="tor" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                            <p x-show="edu.has_tor" class="text-[10px] text-gray-400 mt-1">Current file kept if none uploaded.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 pt-2">
                                        <button type="button" @click="showEduModal = false" class="px-4 py-2 rounded-md border border-gray-300 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                                        <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-sm transition-all">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Education Record</h4>
                            <form action="{{ route('employee.storeMyEducation') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Level</label>
                                        <select name="level" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option>Primary</option><option>Secondary</option><option>Tertiary</option><option>Post Degree</option><option>Ph Degree</option>
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
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Attach Diploma</label>
                                        <input type="file" name="diploma" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Attach Transcript (TOR)</label>
                                        <input type="file" name="tor" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Add Education</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-data="{ showJobModal: false, job: {} }" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Employment History</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-md">From</th>
                                        <th class="px-4 py-3">To</th>
                                        <th class="px-4 py-3">Company</th>
                                        <th class="px-4 py-3">Designation</th>
                                        <th class="px-4 py-3">COE</th>
                                        <th class="px-4 py-3 rounded-r-md"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @forelse($employee->employmentHistory as $job)
                                    <tr>
                                        <td class="px-4 py-3">{{ $job->from_date }}</td>
                                        <td class="px-4 py-3">{{ $job->to_date ?? 'Present' }}</td>
                                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $job->company_name }}</td>
                                        <td class="px-4 py-3">{{ $job->designation }}</td>
                                        <td class="px-4 py-3">
                                            @if($job->coe_path) <a href="{{ asset('storage/'.$job->coe_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold">View COE</a> @else <span class="text-gray-400">—</span> @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="job = {{ json_encode(['id' => $job->id, 'from_date' => $job->from_date, 'to_date' => $job->to_date ?? '', 'company_name' => $job->company_name, 'designation' => $job->designation, 'has_coe' => (bool)$job->coe_path]) }}; showJobModal = true" title="Edit" class="text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-400 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <form method="POST" action="{{ route('employee.destroyMyEmploymentHistory', $job->id) }}" onsubmit="return confirm('Delete this employment record?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete" class="text-red-500 hover:text-red-700 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">No employment history added yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Edit Employment Modal --}}
                        <div x-show="showJobModal" style="display:none" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 p-4" @keydown.escape.window="showJobModal = false">
                            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg">
                                <div class="flex items-center justify-between p-5 border-b dark:border-slate-700">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit Employment Record</h3>
                                    <button @click="showJobModal = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl leading-none">&times;</button>
                                </div>
                                <form :action="'/my-profile/employment-history/' + job.id" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">From (Month & Year)</label>
                                            <input type="text" name="from_date" x-model="job.from_date" placeholder="e.g. June 2018" required class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">To (leave blank if current)</label>
                                            <input type="text" name="to_date" x-model="job.to_date" placeholder="e.g. March 2023" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Company Name</label>
                                            <input type="text" name="company_name" x-model="job.company_name" required class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Designation / Position</label>
                                            <input type="text" name="designation" x-model="job.designation" required class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Replace COE <span class="font-normal text-gray-400">(optional)</span></label>
                                            <input type="file" name="coe" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                            <p x-show="job.has_coe" class="text-[10px] text-gray-400 mt-1">Current file kept if none uploaded.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 pt-2">
                                        <button type="button" @click="showJobModal = false" class="px-4 py-2 rounded-md border border-gray-300 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                                        <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-sm transition-all">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Previous Employment</h4>
                            <form action="{{ route('employee.storeMyEmploymentHistory') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">From (Month & Year)</label>
                                        <input type="text" name="from_date" placeholder="e.g. June 2018" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">To (blank if current)</label>
                                        <input type="text" name="to_date" placeholder="e.g. March 2023" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Company Name</label>
                                        <input type="text" name="company_name" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Designation / Position</label>
                                        <input type="text" name="designation" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    <div class="md:col-span-3">
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Attach Certificate of Employment (COE)</label>
                                        <input type="file" name="coe" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Add Record</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- TAB 3: TRAININGS & LICENSES --}}
            {{-- ======================================================= --}}
            <div x-show="activeTab === 'training'" class="space-y-6">

                <div x-data="{ showLicModal: false, lic: {} }" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 text-xs font-bold rounded-full uppercase">Licenses</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Professional Licenses</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-md">License Name</th>
                                        <th class="px-4 py-3">License No.</th>
                                        <th class="px-4 py-3">Date Issued</th>
                                        <th class="px-4 py-3">Expiry Date</th>
                                        <th class="px-4 py-3">Copy</th>
                                        <th class="px-4 py-3 rounded-r-md"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @forelse($employee->trainings->where('type', 'License') as $lic)
                                    <tr>
                                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $lic->title }}</td>
                                        <td class="px-4 py-3">{{ $lic->license_no ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $lic->start_date ? \Carbon\Carbon::parse($lic->start_date)->format('m/d/Y') : '—' }}</td>
                                        <td class="px-4 py-3">
                                            @if($lic->expiry_date)
                                                @php $exp = \Carbon\Carbon::parse($lic->expiry_date); @endphp
                                                <span class="{{ $exp->isPast() ? 'text-red-600 font-bold' : ($exp->diffInDays() < 90 ? 'text-amber-600 font-bold' : '') }}">
                                                    {{ $exp->format('m/d/Y') }}
                                                    @if($exp->isPast()) <span class="text-xs">(Expired)</span> @endif
                                                </span>
                                            @else —
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($lic->certificate_path) <a href="{{ asset('storage/'.$lic->certificate_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold">View</a> @else <span class="text-gray-400">—</span> @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="lic = {{ json_encode(['id' => $lic->id, 'title' => $lic->title, 'license_no' => $lic->license_no ?? '', 'start_date' => $lic->start_date ?? '', 'expiry_date' => $lic->expiry_date ?? '', 'has_cert' => (bool)$lic->certificate_path]) }}; showLicModal = true" title="Edit" class="text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-400 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <form method="POST" action="{{ route('employee.destroyMyTraining', $lic->id) }}" onsubmit="return confirm('Delete this license?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete" class="text-red-500 hover:text-red-700 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">No licenses added yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Edit License Modal --}}
                        <div x-show="showLicModal" style="display:none" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 p-4" @keydown.escape.window="showLicModal = false">
                            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg">
                                <div class="flex items-center justify-between p-5 border-b dark:border-slate-700">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit License</h3>
                                    <button @click="showLicModal = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl leading-none">&times;</button>
                                </div>
                                <form :action="'/my-profile/training/' + lic.id" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">License Name</label>
                                            <input type="text" name="title" x-model="lic.title" required class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">License No.</label>
                                            <input type="text" name="license_no" x-model="lic.license_no" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Date Issued</label>
                                            <input type="date" name="start_date" x-model="lic.start_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Expiry Date</label>
                                            <input type="date" name="expiry_date" x-model="lic.expiry_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Replace Copy <span class="font-normal text-gray-400">(optional)</span></label>
                                            <input type="file" name="certificate" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                            <p x-show="lic.has_cert" class="text-[10px] text-gray-400 mt-1">Current file kept if none uploaded.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 pt-2">
                                        <button type="button" @click="showLicModal = false" class="px-4 py-2 rounded-md border border-gray-300 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                                        <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-sm transition-all">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add License</h4>
                            <form action="{{ route('employee.storeMyTraining') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="License">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">License Name</label>
                                        <input type="text" name="title" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">License No.</label>
                                        <input type="text" name="license_no" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Date Issued</label>
                                        <input type="date" name="start_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Expiry Date</label>
                                        <input type="date" name="expiry_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Attach Copy of License</label>
                                        <input type="file" name="certificate" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Add License</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-data="{ showTrModal: false, tr: {} }" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300 text-xs font-bold rounded-full uppercase">Trainings</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Trainings Attended</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-md">Training</th>
                                        <th class="px-4 py-3">Inclusive Dates</th>
                                        <th class="px-4 py-3">Certificate</th>
                                        <th class="px-4 py-3 rounded-r-md"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @forelse($employee->trainings->where('type', 'Training') as $tr)
                                    <tr>
                                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $tr->title }}</td>
                                        <td class="px-4 py-3">
                                            {{ $tr->start_date ? \Carbon\Carbon::parse($tr->start_date)->format('M d, Y') : '' }}
                                            {{ $tr->end_date ? ' — ' . \Carbon\Carbon::parse($tr->end_date)->format('M d, Y') : '' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($tr->certificate_path) <a href="{{ asset('storage/'.$tr->certificate_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold">View</a> @else <span class="text-gray-400">—</span> @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="tr = {{ json_encode(['id' => $tr->id, 'title' => $tr->title, 'start_date' => $tr->start_date ?? '', 'end_date' => $tr->end_date ?? '', 'has_cert' => (bool)$tr->certificate_path]) }}; showTrModal = true" title="Edit" class="text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-400 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <form method="POST" action="{{ route('employee.destroyMyTraining', $tr->id) }}" onsubmit="return confirm('Delete this training?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete" class="text-red-500 hover:text-red-700 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 italic">No trainings added yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Edit Training Modal --}}
                        <div x-show="showTrModal" style="display:none" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 p-4" @keydown.escape.window="showTrModal = false">
                            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg">
                                <div class="flex items-center justify-between p-5 border-b dark:border-slate-700">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit Training</h3>
                                    <button @click="showTrModal = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl leading-none">&times;</button>
                                </div>
                                <form :action="'/my-profile/training/' + tr.id" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Training Title</label>
                                            <input type="text" name="title" x-model="tr.title" required class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Start Date</label>
                                            <input type="date" name="start_date" x-model="tr.start_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">End Date</label>
                                            <input type="date" name="end_date" x-model="tr.end_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Replace Certificate <span class="font-normal text-gray-400">(optional)</span></label>
                                            <input type="file" name="certificate" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                            <p x-show="tr.has_cert" class="text-[10px] text-gray-400 mt-1">Current file kept if none uploaded.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 pt-2">
                                        <button type="button" @click="showTrModal = false" class="px-4 py-2 rounded-md border border-gray-300 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                                        <button type="submit" class="px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold shadow-sm transition-all">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Training</h4>
                            <form action="{{ route('employee.storeMyTraining') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="Training">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Training Title</label>
                                        <input type="text" name="title" placeholder="e.g. IB Developing MYP" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Start Date</label>
                                        <input type="date" name="start_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">End Date</label>
                                        <input type="date" name="end_date" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="md:col-span-3">
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Attach Certificate (if applicable)</label>
                                        <input type="file" name="certificate" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Add Training</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- TAB 4: FAMILY --}}
            {{-- ======================================================= --}}
            <div x-show="activeTab === 'family'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Family Background</h3>
                </div>
                <div class="p-6">

                    {{-- Emergency Contact --}}
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-xl">
                        <h4 class="font-bold text-sm text-red-700 dark:text-red-400 uppercase mb-3">Emergency Contact</h4>
                        <form action="{{ route('employee.updateMyPersonal') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="hidden" name="first_name" value="{{ $employee->first_name }}">
                            <input type="hidden" name="last_name" value="{{ $employee->last_name }}">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-bold text-red-600 dark:text-red-400 uppercase mb-1">Contact Person Name</label>
                                    <input type="text" name="emergency_contact_person" value="{{ old('emergency_contact_person', $employee->emergency_contact_person) }}" class="block w-full rounded-md border-red-200 dark:border-red-800 bg-white dark:bg-slate-700 dark:text-white shadow-sm focus:border-red-400 focus:ring-red-400 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-red-600 dark:text-red-400 uppercase mb-1">Contact Number</label>
                                    <input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number', $employee->emergency_contact_number) }}" class="block w-full rounded-md border-red-200 dark:border-red-800 bg-white dark:bg-slate-700 dark:text-white shadow-sm focus:border-red-400 focus:ring-red-400 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Save Emergency Contact</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                            <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-md">Relation</th>
                                    <th class="px-4 py-3">Full Name</th>
                                    <th class="px-4 py-3">Birthday</th>
                                    <th class="px-4 py-3">Place of Birth</th>
                                    <th class="px-4 py-3 rounded-r-md">Occupation</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                @forelse($employee->family as $fam)
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-xs font-bold
                                            {{ $fam->relation == 'Mother' ? 'bg-pink-100 text-pink-700' :
                                               ($fam->relation == 'Father' ? 'bg-blue-100 text-blue-700' :
                                               ($fam->relation == 'Spouse' ? 'bg-purple-100 text-purple-700' :
                                               ($fam->relation == 'Child'  ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'))) }}">
                                            {{ $fam->relation }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $fam->name }}</td>
                                    <td class="px-4 py-3">{{ $fam->birthdate ? \Carbon\Carbon::parse($fam->birthdate)->format('m/d/Y') : '—' }}</td>
                                    <td class="px-4 py-3">{{ $fam->birthplace ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $fam->occupation ?? '—' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">No family members added yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Family Member</h4>
                        <form action="{{ route('employee.storeMyFamily') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Relation</label>
                                    <select name="relation" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option>Mother</option><option>Father</option><option>Sibling</option><option>Spouse</option><option>Child</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Full Name (Last, First, Middle)</label>
                                    <input type="text" name="name" placeholder="e.g. Dela Cruz, Juan, Santos" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Birthday</label>
                                    <input type="date" name="birthdate" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Place of Birth</label>
                                    <input type="text" name="birthplace" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Occupation</label>
                                    <input type="text" name="occupation" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- TAB 5: HEALTH --}}
            {{-- ======================================================= --}}
            <div x-show="activeTab === 'health'" class="space-y-6">

                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Health & Wellness</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 dark:text-white mb-2">Mental Health Notes</label>
                            <form action="{{ route('employee.updateMyHealthNotes') }}" method="POST">
                                @csrf @method('PUT')
                                <textarea name="mental_health" rows="3" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter any mental health notes or history here...">{{ $employee->mental_health }}</textarea>
                                <div class="text-right mt-3">
                                    <button type="submit" class="text-xs bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 shadow-sm">Save Notes</button>
                                </div>
                            </form>
                        </div>

                        <h4 class="font-bold text-gray-700 dark:text-white mb-3">Pre-existing Conditions</h4>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-md">Condition</th>
                                        <th class="px-4 py-3">Date Diagnosed</th>
                                        <th class="px-4 py-3">Medication</th>
                                        <th class="px-4 py-3 rounded-r-md">Dosage</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @forelse($employee->health as $h)
                                    <tr>
                                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $h->condition }}</td>
                                        <td class="px-4 py-3">{{ $h->date_diagnosed ? \Carbon\Carbon::parse($h->date_diagnosed)->format('m/d/Y') : '—' }}</td>
                                        <td class="px-4 py-3">{{ $h->medication ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $h->dosage ?? '—' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 italic">No conditions recorded.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Pre-existing Condition</h4>
                            <form action="{{ route('employee.storeMyHealth') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Annual Health Exams</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">APE and Drug Test results — updated yearly.</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-md">Type</th>
                                        <th class="px-4 py-3">Year</th>
                                        <th class="px-4 py-3">Notes</th>
                                        <th class="px-4 py-3 rounded-r-md">Result File</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @forelse($employee->healthExams as $exam)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded text-xs font-bold {{ $exam->exam_type == 'APE' ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }}">{{ $exam->exam_type }}</span>
                                        </td>
                                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $exam->exam_year }}</td>
                                        <td class="px-4 py-3">{{ $exam->result_notes ?? '—' }}</td>
                                        <td class="px-4 py-3">
                                            @if($exam->result_path) <a href="{{ asset('storage/'.$exam->result_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold">View Result</a> @else <span class="text-gray-400">—</span> @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 italic">No exam results uploaded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-4 uppercase tracking-wide">Add Exam Result</h4>
                            <form action="{{ route('employee.storeMyHealthExam') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Exam Type</label>
                                    <select name="exam_type" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="APE">Annual Physical Exam (APE)</option>
                                        <option value="DrugTest">Drug Test</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Year</label>
                                    <input type="number" name="exam_year" value="{{ date('Y') }}" min="2000" max="2100" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Notes / Result Summary</label>
                                    <input type="text" name="result_notes" placeholder="e.g. Fit to work" class="block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Attach Result</label>
                                    <input type="file" name="result" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-gray-300">
                                </div>
                                <div class="md:col-span-4 text-right">
                                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-md font-bold text-sm shadow-sm transition-all">Add Exam Result</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- TAB 6: SALARY HISTORY (Read-only) --}}
            {{-- ======================================================= --}}
            <div x-show="activeTab === 'salary'" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="flex justify-between items-center p-8 border-b border-gray-100 dark:border-slate-700">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">My Salary History</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Your compensation progression over time.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wide">Current Basic Salary</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($employee->basic_salary, 2) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">per month</p>
                    </div>
                </div>
                <div class="p-8">
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
                                @forelse($employee->salaryHistory as $history)
                                <tr>
                                    <td class="px-6 py-3">{{ \Carbon\Carbon::parse($history->effective_date)->format('m/d/Y') }}</td>
                                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $history->reason }}</td>
                                    <td class="px-6 py-3 text-right text-gray-400">₱{{ number_format($history->previous_salary, 2) }}</td>
                                    <td class="px-6 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($history->new_salary, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">No salary history recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
