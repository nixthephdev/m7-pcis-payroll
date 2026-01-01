<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID - {{ $student->user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; -webkit-print-color-adjust: exact; background-color: #f3f4f6; }
        .id-card { width: 320px; height: 500px; background: white; border-radius: 20px; overflow: hidden; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); border: 1px solid #e5e7eb; }
        /* Teal Gradient for Students to distinguish from Employees */
        .bg-pattern { background: linear-gradient(135deg, #0d9488 0%, #2dd4bf 100%); clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); height: 160px; width: 100%; position: absolute; top: 0; }
        .print-btn { position: fixed; bottom: 20px; right: 20px; }
        @media print { .print-btn { display: none; } body { background: white; } }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="id-card">
        
        <!-- Header Background -->
        <div class="bg-pattern"></div>
        
        <!-- Logo -->
        <div class="absolute top-4 w-full flex justify-center z-10">
            <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto drop-shadow-md">
        </div>

        <!-- Profile Photo -->
        <div class="absolute top-24 w-full flex justify-center z-20">
            <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-200">
                @if($student->user->avatar)
                    <img src="{{ asset('storage/' . $student->user->avatar) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-gray-400 bg-gray-100">
                        {{ substr($student->user->name, 0, 1) }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Student Details -->
        <div class="absolute top-60 w-full text-center px-4">
            <h1 class="text-xl font-extrabold text-gray-900 uppercase leading-tight tracking-tight">
                {{ $student->user->name }}
            </h1>
            <p class="text-sm text-teal-600 font-bold mt-1 uppercase tracking-wider">
                {{ $student->grade_level }} - {{ $student->section }}
            </p>
            
            <div class="mt-3">
                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs rounded-full font-mono border border-gray-200">
                    ID: {{ $student->student_id }}
                </span>
            </div>
        </div>

        <!-- QR Code -->
        <div class="absolute bottom-8 w-full flex flex-col items-center">
            <div class="p-2 bg-white border border-gray-200 rounded-xl shadow-sm">
                <!-- Generates QR containing the Student ID -->
                {{ QrCode::size(100)->generate($student->student_id) }}
            </div>
            <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest font-semibold">Scan for Attendance</p>
        </div>

        <!-- Bottom Strip -->
        <div class="absolute bottom-0 w-full h-2 bg-teal-600"></div>
    </div>

    <!-- Print Button -->
    <button onclick="window.print()" class="print-btn bg-teal-600 hover:bg-teal-700 text-white p-4 rounded-full shadow-xl transition transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
    </button>

</body>
</html>