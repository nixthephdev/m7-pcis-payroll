<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Kiosk - M7 PCIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        #reader { width: 100%; border-radius: 12px; overflow: hidden; }
        #reader video { object-fit: cover; border-radius: 12px; }
        #html5-qrcode-button-camera-stop { display: none !important; }
        #html5-qrcode-anchor-scan-type-change { display: none !important; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <!-- Main Card -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden relative">
        
        <!-- Header Band -->
        <div class="bg-indigo-900 p-6 text-center">
            <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto mx-auto mb-3 drop-shadow-md">
            <h1 class="text-xl font-bold text-white tracking-wide">M7 ATTENDANCE</h1>
            <p class="text-indigo-200 text-xs uppercase font-medium tracking-wider mt-1">Student & Employee Kiosk Terminal</p>
        </div>

        <div class="p-8">
            
            <!-- Camera Box -->
            <div class="relative bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 p-1">
                <div id="reader"></div>
                <p class="text-center text-xs text-gray-400 mt-2 pb-2">Position QR Code within frame</p>
            </div>

            <!-- Live Clock -->
            <div class="mt-6 text-center">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Current Time</p>
                <div class="text-3xl font-mono font-bold text-gray-800 mt-1" id="clock">--:--:--</div>
                <div class="text-sm text-gray-500 font-medium" id="date">Loading Date...</div>
            </div>

            <!-- MANUAL TEST INPUT (Wider) -->
            <div class="mt-6 flex gap-2 justify-center">
                <input type="text" id="manual_id" placeholder="Enter ID Number (e.g. PCIS00059)" 
                       class="border border-gray-300 p-2 rounded-lg text-black w-64 text-center focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition shadow-sm">
                
                <button onclick="onScanSuccess(document.getElementById('manual_id').value, null)" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold shadow-md transition transform hover:scale-105">
                    Enter
                </button>
            </div>

            <!-- Status Message (Hidden by default) -->
            <div id="result" class="mt-6 hidden transition-all duration-300">
                <div id="result-box" class="p-8 rounded-2xl border-4 flex flex-col items-center text-center shadow-2xl">
                    <div id="icon-box">
                        <!-- Icon injected via JS -->
                    </div>
                    <div>
                        <h3 class="text-3xl font-black uppercase tracking-widest" id="status-title">Processing</h3>
                        <p class="text-xl font-semibold mt-2" id="message">Please wait...</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <p class="text-[10px] text-gray-400">&copy; {{ date('Y') }} M7 PCIS. Authorized Personnel Only.</p>
        </div>
    </div>

    <!-- Hidden Admin Link -->
    <a href="{{ route('dashboard') }}" class="fixed bottom-4 right-4 text-gray-300 hover:text-gray-500 transition p-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            const resultBox = document.getElementById('result-box');
            const iconBox = document.getElementById('icon-box');
            const title = document.getElementById('status-title');
            const msg = document.getElementById('message');

            // Show Loading State
            resultDiv.classList.remove('hidden');
            
            // Default Blue Loading
            resultBox.className = "p-8 rounded-2xl border-4 flex flex-col items-center text-center shadow-2xl bg-blue-50 border-blue-500 text-blue-900";
            iconBox.innerHTML = `<svg class="animate-spin h-16 w-16 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            title.className = "text-3xl font-black uppercase tracking-widest";
            msg.className = "text-xl font-semibold mt-2";
            
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
                    resultBox.className = "p-8 rounded-2xl border-4 flex flex-col items-center text-center shadow-2xl bg-emerald-100 border-emerald-500 text-emerald-900";
                    iconBox.innerHTML = `<div class="bg-emerald-500 text-white rounded-full p-4 mb-4 shadow-lg"><svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></div>`;
                    title.innerText = "SUCCESS!";
                } else {
                    // ERROR (Red)
                    resultBox.className = "p-8 rounded-2xl border-4 flex flex-col items-center text-center shadow-2xl bg-rose-100 border-rose-500 text-rose-900";
                    iconBox.innerHTML = `<div class="bg-rose-500 text-white rounded-full p-4 mb-4 shadow-lg"><svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></div>`;
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
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>
</html>