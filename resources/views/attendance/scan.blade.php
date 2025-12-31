<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Kiosk - M7 PCIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        #reader { width: 100%; height: 100%; object-fit: cover; }
        #reader video { border-radius: 1rem; object-fit: cover; }
        #html5-qrcode-button-camera-stop { display: none !important; }
        #html5-qrcode-anchor-scan-type-change { display: none !important; }
        
        /* Scan Line Animation */
        .scan-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: #4f46e5; /* Indigo-600 */
            box-shadow: 0 0 4px #4f46e5;
            top: 0;
            left: 0;
            animation: scan 3s linear infinite;
            opacity: 0.6;
        }
        @keyframes scan {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-slate-100 to-indigo-100 p-4">

    <!-- Main Card -->
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-white/50 overflow-hidden relative backdrop-blur-sm">
        
        <!-- Decorative Top Bar -->
        <div class="h-2 w-full bg-gradient-to-r from-indigo-600 via-blue-500 to-indigo-600"></div>

        <div class="p-8">
            
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 bg-indigo-50 rounded-full mb-4 shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" class="h-24 w-auto drop-shadow-md">
                </div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">M7 PCIS ATTENDANCE</h1>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <p class="text-indigo-500 text-xs font-bold uppercase tracking-widest">Kiosk Terminal Active</p>
                </div>
            </div>

            <!-- Camera Box (Premium Viewfinder) -->
            <div class="relative mx-auto w-full aspect-square max-w-[320px] bg-slate-900 rounded-2xl overflow-hidden shadow-inner border border-slate-200 group">
                <!-- The Scanner Video -->
                <div id="reader" class="h-full w-full"></div>
                
                <!-- Viewfinder Overlay -->
                <div class="absolute inset-0 pointer-events-none flex flex-col justify-between p-6">
                    <div class="flex justify-between">
                        <div class="w-8 h-8 border-t-4 border-l-4 border-white/80 rounded-tl-lg"></div>
                        <div class="w-8 h-8 border-t-4 border-r-4 border-white/80 rounded-tr-lg"></div>
                    </div>
                    <div class="flex justify-between">
                        <div class="w-8 h-8 border-b-4 border-l-4 border-white/80 rounded-bl-lg"></div>
                        <div class="w-8 h-8 border-b-4 border-r-4 border-white/80 rounded-br-lg"></div>
                    </div>
                </div>

                <!-- Scanning Animation Line -->
                <div class="scan-line"></div>

                <!-- Instruction Text Overlay -->
                <div class="absolute bottom-4 left-0 w-full text-center">
                    <span class="bg-black/50 text-white/90 text-[10px] px-3 py-1 rounded-full backdrop-blur-md border border-white/10">
                        Position QR Code within frame
                    </span>
                </div>
            </div>

            <!-- Live Clock (Digital Station Style) -->
            <div class="mt-8 text-center bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Current Server Time</p>
                <div class="text-4xl font-bold text-slate-800 font-mono tracking-tight" id="clock">--:--:--</div>
                <div class="text-sm text-indigo-500 font-semibold mt-1" id="date">Loading Date...</div>
            </div>

            <!-- MANUAL INPUT (Collapsible/Clean) -->
            <div class="mt-6">
                <div class="relative flex items-center max-w-[320px] mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 19l-1 1-1-1-1 1-1-1-1 1-1-1 5-5m0 0a3 3 0 00-4.681-4.681 1 1 0 00.986 2.986" />
                        </svg>
                    </div>
                    <input type="text" id="manual_id" placeholder="Enter ID (e.g. PCIS00059)" 
                           class="w-full pl-10 pr-20 py-3 rounded-xl border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-gray-400">
                    <button onclick="onScanSuccess(document.getElementById('manual_id').value, null)" 
                            class="absolute right-1 top-1 bottom-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 rounded-lg text-xs font-bold transition">
                        ENTER
                    </button>
                </div>
            </div>

            <!-- Status Message Overlay (Hidden by default) -->
            <div id="result" class="absolute inset-0 bg-white/95 backdrop-blur-md z-50 hidden flex-col items-center justify-center text-center p-8 transition-all duration-300">
                <div id="icon-box" class="mb-4">
                    <!-- Icon injected via JS -->
                </div>
                <h3 class="text-3xl font-black uppercase tracking-widest text-slate-800" id="status-title">Processing</h3>
                <p class="text-lg font-medium text-slate-500 mt-2" id="message">Please wait...</p>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <p class="text-[10px] text-slate-400 font-medium">
                &copy; {{ date('Y') }} M7 PCIS. Authorized Personnel Only.
            </p>
            <p class="text-[9px] text-slate-300 mt-1">
                System Architecture & Development by <span class="text-indigo-300">Nikko Calumpiano</span>
            </p>
        </div>
    </div>

    <!-- Hidden Admin Link -->
    <a href="{{ route('dashboard') }}" class="fixed bottom-6 right-6 text-slate-300 hover:text-indigo-500 transition p-2 bg-white rounded-full shadow-sm hover:shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </a>

    <!-- SCANNER LOGIC -->
    <script>
        // 1. Live Clock & Date
        function updateTime() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('date').innerText = now.toLocaleDateString([], { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateTime, 1000);
        updateTime();

        // 2. Success Callback
        function onScanSuccess(decodedText, decodedResult) {
            if(html5QrcodeScanner) {
                try { html5QrcodeScanner.clear(); } catch(e) {}
            }

            const resultDiv = document.getElementById('result');
            const iconBox = document.getElementById('icon-box');
            const title = document.getElementById('status-title');
            const msg = document.getElementById('message');

            // Show Loading Overlay
            resultDiv.classList.remove('hidden');
            resultDiv.classList.add('flex');
            
            // Loading State
            iconBox.innerHTML = `<svg class="animate-spin h-16 w-16 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            title.className = "text-3xl font-black uppercase tracking-widest text-indigo-900";
            title.innerText = "VERIFYING...";
            msg.innerText = "Please wait a moment.";

            // Send to Backend
            fetch("{{ route('attendance.scan') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ employee_id: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                let audio = new Audio('https://www.soundjay.com/button/beep-07.wav');
                audio.play();

                if(data.status === 'success') {
                    // SUCCESS (Green)
                    iconBox.innerHTML = `<div class="bg-emerald-100 text-emerald-600 rounded-full p-4 shadow-inner"><svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></div>`;
                    title.className = "text-3xl font-black uppercase tracking-widest text-emerald-600";
                    title.innerText = "SUCCESS";
                } else {
                    // ERROR (Red)
                    iconBox.innerHTML = `<div class="bg-rose-100 text-rose-600 rounded-full p-4 shadow-inner"><svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></div>`;
                    title.className = "text-3xl font-black uppercase tracking-widest text-rose-600";
                    title.innerText = "ERROR";
                }
                
                msg.innerText = data.message;

                // Restart Scanner
                setTimeout(() => {
                    location.reload(); 
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Something went wrong! Check Console.");
                location.reload();
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>
</html>