<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID - {{ $student->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; -webkit-print-color-adjust: exact; background-color: #f3f4f6; }
        
        /* Standardized Card Size */
        .id-card { width: 320px; height: 480px; background: white; border-radius: 20px; overflow: hidden; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; }
        
        /* Front Design */
        .bg-pattern { background: linear-gradient(135deg, #0d9488 0%, #2dd4bf 100%); clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); height: 180px; width: 100%; position: absolute; top: 0; left: 0; }
        
        /* Back Design */
        .bg-pattern-back { background: linear-gradient(135deg, #0d9488 0%, #2dd4bf 100%); height: 100%; width: 100%; position: absolute; top: 0; left: 0; opacity: 0.05; }
        
        .print-btn { position: fixed; bottom: 30px; right: 30px; z-index: 50; }
        
        @media print { 
            .print-btn { display: none; } 
            body { background: white; }
            .container-wrapper { gap: 20px; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-10">

    <div class="container-wrapper flex flex-wrap justify-center gap-10">

        <!-- ================= FRONT OF CARD ================= -->
        <div class="id-card flex flex-col">
            <div class="bg-pattern absolute z-0"></div>
            
            <div class="relative z-10 flex flex-col items-center pt-8 h-full w-full">
                <!-- School Logo/Name -->
                <div class="text-white text-center mb-4">
                    <div class="font-extrabold text-xl tracking-wider opacity-90">M7 PCIS</div>
                    <div class="text-xs font-medium opacity-75 uppercase tracking-widest">Student Identity</div>
                </div>

                <!-- Profile Image / Initials -->
                <div class="w-28 h-28 rounded-full border-4 border-white shadow-lg bg-white flex items-center justify-center overflow-hidden mb-3">
                    @if($student->user && $student->user->avatar)
                        <img src="{{ asset('storage/' . $student->user->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-teal-50 text-teal-600 flex items-center justify-center text-4xl font-bold">
                            {{ substr($student->full_name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Student Details -->
                <div class="text-center px-6 w-full flex-grow">
                    <h1 class="text-lg font-bold text-gray-800 leading-tight mb-1">{{ $student->full_name }}</h1>
                    <p class="text-sm text-teal-600 font-bold mb-4">{{ $student->student_id }}</p>

                    <div class="border-t border-gray-100 py-4 w-full">
                        <div class="grid grid-cols-2 gap-4 text-left text-xs">
                            <div>
                                <span class="block text-gray-400 uppercase text-[10px] font-bold">Grade Level</span>
                                <span class="font-bold text-gray-700">{{ $student->grade_level }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-400 uppercase text-[10px] font-bold">Section</span>
                                <span class="font-bold text-gray-700">{{ $student->section }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-gray-400 uppercase text-[10px] font-bold">Emergency Contact</span>
                                <span class="font-bold text-gray-700 block truncate">{{ $student->guardian_name }}</span>
                                <span class="text-gray-600">{{ $student->guardian_contact }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Bar -->
                <div class="mt-auto w-full bg-gray-50 py-3 border-t border-gray-100 flex justify-center items-center gap-2">
                    <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Active Student</span>
                </div>
            </div>
        </div>

        <!-- ================= BACK OF CARD ================= -->
        <div class="id-card flex flex-col items-center justify-center text-center relative">
            <!-- Subtle Background Pattern -->
            <div class="bg-pattern-back"></div>

            <div class="relative z-10 p-6 w-full h-full flex flex-col items-center justify-center">
                
                <div class="mb-6">
                    <h2 class="text-2xl font-extrabold text-teal-700 tracking-wider">M7 PCIS</h2>
                    <p class="text-xs text-gray-500 uppercase tracking-widest">Official Student ID</p>
                </div>

                <!-- Large QR Code -->
                <div class="bg-white p-3 rounded-xl shadow-md border border-gray-100 mb-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $student->student_id }}" 
                         alt="QR Code" 
                         class="w-40 h-40">
                </div>

                <div class="space-y-1">
                    <p class="text-sm font-bold text-gray-800">{{ $student->student_id }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wide">Scan at Kiosk for Attendance</p>
                </div>

                <div class="mt-8 text-[10px] text-gray-500 leading-relaxed px-4">
                    <p>This card is non-transferable.</p>
                    <p>If found, please return to the M7 PCIS Administration Office.</p>
                </div>

            </div>
        </div>

    </div>

    <!-- Print Button -->
    <button onclick="window.print()" class="print-btn bg-gray-800 text-white p-4 rounded-full shadow-xl hover:bg-gray-700 transition transform hover:scale-105 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        <span class="font-bold text-sm pr-2">Print ID</span>
    </button>

</body>
</html>