<!-- Page Preloader -->
<div id="page-preloader">
    <div class="preloader-inner">
        <div class="preloader-logo-wrap">
            <img src="{{ asset('images/logo.png') }}" alt="M7 PCIS" class="preloader-logo">
            <div class="shimmer-sweep"></div>
        </div>
        <p class="preloader-title">M7 PCIS</p>
        <p class="preloader-sub">Loading, please wait...</p>
        <div class="preloader-bar-track">
            <div class="preloader-bar"></div>
        </div>
    </div>
</div>

<style>
    #page-preloader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #020617 0%, #1e1b4b 50%, #0f172a 100%);
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    #page-preloader.fade-out {
        opacity: 0;
        visibility: hidden;
    }

    .preloader-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    /* Logo + shimmer container */
    .preloader-logo-wrap {
        position: relative;
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 50%;
    }
    .preloader-logo {
        width: 100px;
        height: 100px;
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 0 12px rgba(99,102,241,0.6));
    }

    /* Shimmer sweep — diagonal light streak */
    .shimmer-sweep {
        position: absolute;
        top: 0;
        left: -100%;
        width: 60%;
        height: 100%;
        background: linear-gradient(
            120deg,
            transparent 0%,
            rgba(255,255,255,0.35) 50%,
            transparent 100%
        );
        animation: shimmer 1.6s ease-in-out infinite;
    }
    @keyframes shimmer {
        0%   { left: -100%; }
        100% { left: 160%; }
    }

    .preloader-title {
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: 0.15em;
        font-family: 'Inter', sans-serif;
        margin: 0;
    }
    .preloader-sub {
        color: #818cf8;
        font-size: 0.72rem;
        font-weight: 500;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-family: 'Inter', sans-serif;
        margin: 0;
    }

    /* Progress bar */
    .preloader-bar-track {
        width: 140px;
        height: 3px;
        background: rgba(255,255,255,0.08);
        border-radius: 99px;
        overflow: hidden;
        margin-top: 8px;
    }
    .preloader-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(to right, #6366f1, #06b6d4);
        border-radius: 99px;
        animation: fillBar 0.9s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes fillBar {
        0%   { width: 0%; }
        100% { width: 100%; }
    }
</style>

<script>
    window.addEventListener('load', function () {
        const preloader = document.getElementById('page-preloader');
        // Wait for bar to finish, then fade out
        setTimeout(function () {
            preloader.classList.add('fade-out');
            setTimeout(function () {
                preloader.style.display = 'none';
            }, 500);
        }, 950);
    });
</script>
