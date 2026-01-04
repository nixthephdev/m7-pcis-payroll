<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Kiosk - M7 PCIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Deep Space Animated Background */
        .animated-bg {
            background: linear-gradient(-45deg, #020617, #1e1b4b, #312e81, #0f172a);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        #reader { width: 100%; height: 100%; object-fit: cover; }
        #reader video { border-radius: 1rem; object-fit: cover; }
        #html5-qrcode-button-camera-stop { display: none !important; }
        #html5-qrcode-anchor-scan-type-change { display: none !important; }
        
        /* Laser Scan Animation */
        .scan-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: #06b6d4; /* Cyan */
            box-shadow: 0 0 10px #06b6d4;
            top: 0;
            left: 0;
            animation: scan 2.5s linear infinite;
            opacity: 0.8;
        }
        @keyframes scan {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen animated-bg p-4 text-white">

    <!-- Glassmorphism Card -->
    <div class="w-full max-w-lg bg-white/5 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 overflow-hidden relative">
        
        <!-- Top Glow Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-70"></div>

        <div class="p-8">
            
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 bg-white/10 rounded-full mb-4 shadow-lg border border-white/10 backdrop-blur-md">
                    <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto drop-shadow-md">
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight">
                    <span class="text-red-500">M</span><span class="text-blue-500">7</span> PCIS ATTENDANCE
                </h1>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_#34d399]"></span>
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest">Kiosk Terminal Active</p>
                </div>
            </div>

            <!-- Camera Box -->
            <div class="relative mx-auto w-full aspect-square max-w-[320px] bg-black/50 rounded-2xl overflow-hidden shadow-inner border border-white/10 group">
                <div id="reader" class="h-full w-full"></div>
                
                <!-- Viewfinder Corners -->
                <div class="absolute inset-0 pointer-events-none flex flex-col justify-between p-6 opacity-70">
                    <div class="flex justify-between">
                        <div class="w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-lg"></div>
                        <div class="w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-lg"></div>
                    </div>
                    <div class="flex justify-between">
                        <div class="w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-lg"></div>
                        <div class="w-8 h-8 border-b-4 border-r-4 border-white rounded-br-lg"></div>
                    </div>
                </div>

                <!-- Laser Line -->
                <div class="scan-line"></div>
            </div>

            <!-- Live Clock -->
            <div class="mt-8 text-center bg-white/5 rounded-xl p-4 border border-white/5 backdrop-blur-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Current Server Time</p>
                <div class="text-4xl font-bold font-mono tracking-tight text-white" id="clock">--:--:--</div>
                <div class="text-sm text-indigo-300 font-semibold mt-1" id="date">Loading Date...</div>
            </div>

            <!-- MANUAL INPUT -->
            <div class="mt-6">
                <div class="relative flex items-center max-w-[320px] mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 19l-1 1-1-1-1 1-1-1-1 1-1-1 5-5m0 0a3 3 0 00-4.681-4.681 1 1 0 00.986 2.986" />
                        </svg>
                    </div>
                    <input type="text" id="manual_id" placeholder="Enter ID (e.g. PCIS00059)" 
                           class="w-full pl-10 pr-20 py-3 rounded-xl border border-white/10 bg-white/5 text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder-gray-500 backdrop-blur-md">
                    <button onclick="onScanSuccess(document.getElementById('manual_id').value, null)" 
                            class="absolute right-1 top-1 bottom-1 bg-indigo-600 hover:bg-indigo-500 text-white px-4 rounded-lg text-xs font-bold transition shadow-lg shadow-indigo-500/30">
                        ENTER
                    </button>
                </div>
            </div>

            <!-- Status Overlay -->
            <div id="result" class="absolute inset-0 bg-slate-900/95 backdrop-blur-xl z-50 hidden flex-col items-center justify-center text-center p-8 transition-all duration-300">
                <div id="icon-box" class="mb-6"></div>
                <h3 class="text-3xl font-black uppercase tracking-widest text-white" id="status-title">Processing</h3>
                <p class="text-lg font-medium text-slate-400 mt-2" id="message">Please wait...</p>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-black/20 p-4 text-center border-t border-white/5">
            <p class="text-[10px] text-slate-400 font-medium">
                &copy; {{ date('Y') }} M7 PCIS. Authorized Personnel Only.
            </p>
            <p class="text-[9px] text-slate-500 mt-1">
                System Architecture & Development by <span class="text-indigo-400">Nikko Calumpiano</span>
            </p>
        </div>
    </div>

    <!-- Hidden Admin Link -->
    <a href="{{ route('dashboard') }}" class="fixed bottom-6 right-6 text-white/10 hover:text-white/50 transition p-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </a>

    <!-- SCANNER LOGIC -->
    <script>
        function updateTime() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('date').innerText = now.toLocaleDateString([], { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateTime, 1000);
        updateTime();

        function onScanSuccess(decodedText, decodedResult) {
            if(html5QrcodeScanner) { try { html5QrcodeScanner.clear(); } catch(e) {} }

            const resultDiv = document.getElementById('result');
            const iconBox = document.getElementById('icon-box');
            const title = document.getElementById('status-title');
            const msg = document.getElementById('message');

            resultDiv.classList.remove('hidden');
            resultDiv.classList.add('flex');
            
            iconBox.innerHTML = `<svg class="animate-spin h-20 w-20 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            title.className = "text-4xl font-black uppercase tracking-widest text-indigo-400";
            title.innerText = "VERIFYING...";
            msg.innerText = "Please wait a moment.";

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
                    iconBox.innerHTML = `<div class="bg-emerald-500/20 text-emerald-400 rounded-full p-6 shadow-[0_0_30px_rgba(52,211,153,0.4)] border border-emerald-500/50"><svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></div>`;
                    title.className = "text-4xl font-black uppercase tracking-widest text-emerald-400 drop-shadow-lg";
                    title.innerText = "SUCCESS";
                } else {
                    iconBox.innerHTML = `<div class="bg-rose-500/20 text-rose-400 rounded-full p-6 shadow-[0_0_30px_rgba(251,113,133,0.4)] border border-rose-500/50"><svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></div>`;
                    title.className = "text-4xl font-black uppercase tracking-widest text-rose-400 drop-shadow-lg";
                    title.innerText = "ERROR";
                }
                
                msg.innerText = data.message;

                setTimeout(() => { location.reload(); }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Something went wrong! Check Console.");
                location.reload();
            });
        }

        let config = { fps: 30, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0, experimentalFeatures: { useBarCodeDetectorIfSupported: true } };
        let html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);
        html5QrcodeScanner.render(onScanSuccess, (errorMessage) => {});
    </script>

</body>
</html>