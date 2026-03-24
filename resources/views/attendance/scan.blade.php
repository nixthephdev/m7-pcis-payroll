<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Kiosk - M7 PCIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* Laser Scan Animation */
        .scan-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: #06b6d4; /* Cyan */
            box-shadow: 0 0 15px #06b6d4;
            top: 0;
            left: 0;
            animation: scan 2s ease-in-out infinite;
            opacity: 0.8;
        }
        @keyframes scan {
            0% { top: 10%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 90%; opacity: 0; }
        }

        /* Pulse Effect for Icon */
        .scanner-icon {
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0% { filter: drop-shadow(0 0 5px rgba(6, 182, 212, 0.5)); transform: scale(1); }
            50% { filter: drop-shadow(0 0 20px rgba(6, 182, 212, 0.8)); transform: scale(1.05); }
            100% { filter: drop-shadow(0 0 5px rgba(6, 182, 212, 0.5)); transform: scale(1); }
        }

        /* Clock-In overlay: deep green */
        .overlay-clockin {
            background: linear-gradient(135deg, #052e16 0%, #064e3b 50%, #022c22 100%) !important;
        }
        /* Clock-Out overlay: deep orange/amber */
        .overlay-clockout {
            background: linear-gradient(135deg, #1c0a00 0%, #431407 50%, #27100a 100%) !important;
        }
        /* Error overlay */
        .overlay-error {
            background: linear-gradient(135deg, #1f0a0a 0%, #3b0e0e 50%, #1a0808 100%) !important;
        }

        /* Bounce-in animation for the icon */
        @keyframes bounceIn {
            0%   { transform: scale(0.3); opacity: 0; }
            50%  { transform: scale(1.15); opacity: 1; }
            70%  { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        .bounce-in { animation: bounceIn 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both; }

        /* Slide-up animation for text */
        @keyframes slideUp {
            0%   { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .slide-up { animation: slideUp 0.4s ease 0.2s both; }
        .slide-up-delay { animation: slideUp 0.4s ease 0.35s both; }

        /* Ripple background pulse */
        @keyframes ripplePulse {
            0%   { box-shadow: 0 0 0 0 rgba(255,255,255,0.15); }
            70%  { box-shadow: 0 0 0 40px rgba(255,255,255,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
        }
        .ripple-icon { animation: ripplePulse 1.2s ease-out 0.3s infinite; }

        /* Badge pill */
        .badge-clockin  { background: rgba(52,211,153,0.15); color: #6ee7b7; border: 1px solid rgba(52,211,153,0.4); }
        .badge-clockout { background: rgba(251,146,60,0.15);  color: #fdba74; border: 1px solid rgba(251,146,60,0.4); }
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
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest">Scanner Ready</p>
                </div>
            </div>

            <!-- Scanner Visual Box (No Webcam, just Visuals) -->
            <div class="relative mx-auto w-full aspect-square max-w-[280px] bg-black/40 rounded-2xl overflow-hidden shadow-inner border border-white/10 flex items-center justify-center group">
                
                <!-- Central Icon -->
                <div class="scanner-icon text-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>

                <!-- Viewfinder Corners -->
                <div class="absolute inset-0 pointer-events-none flex flex-col justify-between p-6 opacity-50">
                    <div class="flex justify-between">
                        <div class="w-8 h-8 border-t-4 border-l-4 border-cyan-400 rounded-tl-lg"></div>
                        <div class="w-8 h-8 border-t-4 border-r-4 border-cyan-400 rounded-tr-lg"></div>
                    </div>
                    <div class="flex justify-between">
                        <div class="w-8 h-8 border-b-4 border-l-4 border-cyan-400 rounded-bl-lg"></div>
                        <div class="w-8 h-8 border-b-4 border-r-4 border-cyan-400 rounded-br-lg"></div>
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

            <!-- INPUT FIELD (Auto-Focused) -->
            <div class="mt-6">
                <div class="relative flex items-center max-w-[320px] mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <!-- Added autofocus and autocomplete off -->
                    <input type="text" id="manual_id" placeholder="Scan QR or Enter ID..." autocomplete="off" autofocus
                           class="w-full pl-10 pr-20 py-3 rounded-xl border border-white/10 bg-white/10 text-white text-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition shadow-sm placeholder-gray-400 backdrop-blur-md">
                    <button onclick="processScan(document.getElementById('manual_id').value)" 
                            class="absolute right-1 top-1 bottom-1 bg-indigo-600 hover:bg-indigo-500 text-white px-4 rounded-lg text-xs font-bold transition shadow-lg shadow-indigo-500/30">
                        ENTER
                    </button>
                </div>
                <p class="text-center text-[10px] text-gray-500 mt-2">Ready for Scanner Input</p>
            </div>

            <!-- Status Overlay -->
            <div id="result" class="absolute inset-0 backdrop-blur-xl z-50 hidden flex-col items-center justify-center text-center p-8 transition-all duration-500" style="background: #0f172a;">
                <!-- Top accent bar -->
                <div id="accent-bar" class="absolute top-0 left-0 w-full h-1.5"></div>
                <!-- Badge pill -->
                <div id="badge-pill" class="mb-4 px-5 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest slide-up hidden"></div>
                <div id="icon-box" class="mb-5"></div>
                <h3 class="text-5xl font-black uppercase tracking-widest text-white slide-up" id="status-title">Processing</h3>
                <p class="text-xl font-semibold mt-3 slide-up-delay" id="message" style="color:#94a3b8;">Please wait...</p>
                <!-- Time stamp -->
                <p id="stamp-time" class="mt-4 text-sm font-mono slide-up-delay hidden" style="color:#475569;"></p>
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

    <!-- LOGIC -->
    <script>
        // 1. CLEAN CONSOLE & ADD SIGNATURE
        window.onload = function() {
            console.clear();
            console.log(
                "%c SYSTEM ARCHITECTURE & DEVELOPMENT \n%c by Nikko Calumpiano \n\n%c M7 PCIS Attendance System v2.0 \n%c © 2026 All Rights Reserved. ",
                "color: #818cf8; font-size: 20px; font-weight: bold; background: #1e1b4b; padding: 10px; border-radius: 5px; border: 1px solid #4f46e5;",
                "color: #c7d2fe; font-size: 14px; font-weight: bold; margin-top: 5px;",
                "color: #94a3b8; font-size: 12px; margin-top: 10px;",
                "color: #64748b; font-size: 10px;"
            );
            
            // Auto Focus
            const inputField = document.getElementById('manual_id');
            if(inputField) inputField.focus();
        };

        // 2. Clock Logic
        function updateTime() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('date').innerText = now.toLocaleDateString([], { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateTime, 1000);
        updateTime();

        // 3. Hardware Scanner Listener
        const inputField = document.getElementById('manual_id');
        document.addEventListener('click', function() { inputField.focus(); });

        inputField.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                let id = inputField.value;
                if(id.trim() !== "") {
                    processScan(id);
                    inputField.value = ""; // Clear input immediately
                }
            }
        });

        // 4. Sound Effects
        function playSound(type) {
            if (type === 'clock_in') {
                // Bright ascending success chime (file)
                new Audio('{{ asset("sounds/success.mp3") }}').play();
            } else if (type === 'clock_out') {
                // Synthesized warm descending farewell chime
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const notes = [523.25, 392.00, 329.63]; // C5 → G4 → E4 descending
                notes.forEach((freq, i) => {
                    const osc  = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.18);
                    gain.gain.setValueAtTime(0.45, ctx.currentTime + i * 0.18);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.18 + 0.35);
                    osc.start(ctx.currentTime + i * 0.18);
                    osc.stop(ctx.currentTime + i * 0.18 + 0.36);
                });
            } else {
                // Error buzz (file)
                new Audio('{{ asset("sounds/error.mp3") }}').play();
            }
        }

        // 5. Process Attendance
        function processScan(employeeId) {
            const resultDiv  = document.getElementById('result');
            const iconBox    = document.getElementById('icon-box');
            const title      = document.getElementById('status-title');
            const msg        = document.getElementById('message');
            const badge      = document.getElementById('badge-pill');
            const accentBar  = document.getElementById('accent-bar');
            const stampTime  = document.getElementById('stamp-time');

            // --- Show Loading Overlay ---
            resultDiv.style.background = '#0f172a';
            accentBar.style.background = 'linear-gradient(to right, transparent, #6366f1, transparent)';
            badge.classList.add('hidden');
            stampTime.classList.add('hidden');

            resultDiv.classList.remove('hidden');
            resultDiv.classList.add('flex');

            iconBox.innerHTML = `<svg class="animate-spin h-20 w-20 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            title.className  = "text-4xl font-black uppercase tracking-widest text-indigo-400";
            title.innerText  = "VERIFYING...";
            msg.style.color  = '#94a3b8';
            msg.innerText    = "Please wait a moment.";

            // --- Send Request ---
            fetch("{{ route('attendance.scan') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ employee_id: employeeId })
            })
            .then(response => response.json())
            .then(data => {

                const now = new Date();
                const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });

                playSound(data.status === 'success' ? data.type : 'error');

                if (data.status === 'success' && data.type === 'clock_in') {
                    // ===== CLOCK IN — Vibrant Green =====
                    resultDiv.style.background = 'linear-gradient(135deg, #052e16 0%, #064e3b 50%, #022c22 100%)';
                    accentBar.style.background = 'linear-gradient(to right, transparent, #34d399, transparent)';

                    badge.className = 'mb-4 px-5 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest badge-clockin slide-up';
                    badge.innerHTML = '&#x2191; CLOCKING IN';
                    badge.classList.remove('hidden');

                    iconBox.innerHTML = `
                        <div class="bounce-in ripple-icon rounded-full p-7 border-2 border-emerald-400/60"
                             style="background:rgba(52,211,153,0.12); box-shadow:0 0 50px rgba(52,211,153,0.35);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>`;

                    title.className = "text-5xl font-black uppercase tracking-widest slide-up";
                    title.style.color = '#34d399';
                    title.innerText = 'CLOCKED IN';

                    msg.style.color = '#6ee7b7';
                    msg.className = 'text-2xl font-bold mt-3 slide-up-delay';

                    stampTime.innerHTML = `<span style="color:#34d399;">&#x25CF;</span> Time In: ${timeStr}`;
                    stampTime.classList.remove('hidden');

                } else if (data.status === 'success' && data.type === 'clock_out') {
                    // ===== CLOCK OUT — Warm Amber/Orange =====
                    resultDiv.style.background = 'linear-gradient(135deg, #1c0a00 0%, #431407 50%, #27100a 100%)';
                    accentBar.style.background = 'linear-gradient(to right, transparent, #fb923c, transparent)';

                    badge.className = 'mb-4 px-5 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest badge-clockout slide-up';
                    badge.innerHTML = '&#x2193; CLOCKING OUT';
                    badge.classList.remove('hidden');

                    iconBox.innerHTML = `
                        <div class="bounce-in ripple-icon rounded-full p-7 border-2 border-orange-400/60"
                             style="background:rgba(251,146,60,0.12); box-shadow:0 0 50px rgba(251,146,60,0.35);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>`;

                    title.className = "text-5xl font-black uppercase tracking-widest slide-up";
                    title.style.color = '#fb923c';
                    title.innerText = 'CLOCKED OUT';

                    msg.style.color = '#fdba74';
                    msg.className = 'text-2xl font-bold mt-3 slide-up-delay';

                    stampTime.innerHTML = `<span style="color:#fb923c;">&#x25CF;</span> Time Out: ${timeStr}`;
                    stampTime.classList.remove('hidden');

                } else {
                    // ===== ERROR — Red =====
                    resultDiv.style.background = 'linear-gradient(135deg, #1f0a0a 0%, #3b0e0e 50%, #1a0808 100%)';
                    accentBar.style.background = 'linear-gradient(to right, transparent, #f87171, transparent)';

                    badge.classList.add('hidden');

                    iconBox.innerHTML = `
                        <div class="bounce-in rounded-full p-7 border-2 border-rose-500/50"
                             style="background:rgba(251,113,133,0.12); box-shadow:0 0 40px rgba(251,113,133,0.3);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>`;

                    title.className = "text-5xl font-black uppercase tracking-widest slide-up";
                    title.style.color = '#f87171';
                    title.innerText = 'DENIED';

                    msg.style.color = '#fca5a5';
                    msg.className = 'text-xl font-semibold mt-3 slide-up-delay';
                    stampTime.classList.add('hidden');
                }

                msg.innerText = data.message;

                // Reset after 4 seconds
                setTimeout(() => {
                    resultDiv.classList.add('hidden');
                    resultDiv.classList.remove('flex');
                    inputField.focus();
                }, 4000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Connection Error. Please try again.");
                resultDiv.classList.add('hidden');
                resultDiv.classList.remove('flex');
                inputField.focus();
            });
        }
    </script>

</body>
</html>