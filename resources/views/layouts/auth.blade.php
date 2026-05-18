<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiPosyandu Jakarta')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #09090b;
            color: #18181b;
            min-height: 100dvh;
            overflow-x: hidden;
        }

        /* Perpetual micro-interactions (MOTION_INTENSITY 6) */
        @keyframes drift {
            0%, 100% { transform: translateY(0) translateX(0); }
            33%       { transform: translateY(-14px) translateX(6px); }
            66%       { transform: translateY(-6px) translateX(-8px); }
        }
        @keyframes drift-b {
            0%, 100% { transform: translateY(0) translateX(0); }
            33%       { transform: translateY(10px) translateX(-6px); }
            66%       { transform: translateY(6px) translateX(10px); }
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.6; transform: scale(0.85); }
        }
        @keyframes shimmer-line {
            0%   { transform: translateX(-100%) skewX(-12deg); }
            100% { transform: translateX(250%) skewX(-12deg); }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .drift-a  { animation: drift   14s ease-in-out infinite; }
        .drift-b  { animation: drift-b 18s ease-in-out infinite 3s; }
        .pulse-dot { animation: pulse-dot 2.4s ease-in-out infinite; }

        /* Staggered load-in cascade */
        .fade-in { opacity: 0; animation: fade-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.12s; }
        .delay-3 { animation-delay: 0.20s; }
        .delay-4 { animation-delay: 0.28s; }
        .delay-5 { animation-delay: 0.36s; }
        .delay-6 { animation-delay: 0.44s; }

        /* Liquid Glass — proper refraction (TASTE_SKILL §4) */
        .liquid-glass {
            background: rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(24px) saturate(1.4);
            -webkit-backdrop-filter: blur(24px) saturate(1.4);
            border: 1px solid rgba(255, 255, 255, 0.75);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.9),
                0 24px 48px -12px rgba(0, 0, 0, 0.08);
        }

        /* Form input — glass surface */
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.6);
            border: 1px solid rgba(209, 213, 219, 0.8);
            border-radius: 0.875rem;
            padding: 0.8125rem 1rem;
            font-size: 0.9375rem;
            font-family: 'Outfit', sans-serif;
            color: #18181b;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
            -webkit-backdrop-filter: blur(8px);
            backdrop-filter: blur(8px);
        }
        .form-input:focus {
            border-color: #10b981;
            background: rgba(255,255,255,0.9);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12),
                        inset 0 1px 0 rgba(255,255,255,0.9);
        }
        .form-input.is-error {
            border-color: #f87171;
            background: rgba(255,245,245,0.8);
        }
        .form-input::placeholder { color: #a1a1aa; }

        /* Shimmer effect on stat card */
        .shimmer::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.15) 50%, transparent 100%);
            transform: translateX(-100%) skewX(-12deg);
            animation: shimmer-line 3.5s ease-in-out infinite 1.5s;
        }

        /* Submit button — tactile feedback */
        .btn-submit {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border-radius: 0.875rem;
            background: #09090b;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9375rem;
            font-weight: 500;
            letter-spacing: -0.01em;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 14px -2px rgba(9,9,11,0.25);
            position: relative;
            overflow: hidden;
        }
        .btn-submit:hover  { background: #27272a; }
        .btn-submit:active { transform: scale(0.98) translateY(1px); box-shadow: 0 2px 6px -1px rgba(9,9,11,0.2); }

        /* Mono numbers */
        .mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="antialiased">

<div class="min-h-[100dvh] grid grid-cols-1 lg:grid-cols-2">

    <!-- ===== LEFT PANEL: Dark Branding ===== -->
    <div class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-zinc-950 px-14 py-12">

        <!-- Ambient blobs — perpetual drift -->
        <div class="drift-a pointer-events-none absolute -top-40 -left-40 w-[500px] h-[500px] rounded-full"
             style="background: radial-gradient(circle, rgba(16,185,129,0.18) 0%, transparent 70%);"></div>
        <div class="drift-b pointer-events-none absolute bottom-0 right-0 w-[400px] h-[400px] rounded-full"
             style="background: radial-gradient(circle, rgba(16,185,129,0.10) 0%, transparent 70%);"></div>

        <!-- Subtle grid texture -->
        <div class="pointer-events-none absolute inset-0"
             style="background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px); background-size: 48px 48px;"></div>

        <!-- Logo -->
        <a href="{{ route('home') }}" class="relative z-10 flex items-center space-x-3 w-fit">
            <div class="w-9 h-9 rounded-full bg-emerald-500 flex items-center justify-center shadow-lg"
                 style="box-shadow: 0 4px 14px -2px rgba(16,185,129,0.4);">
                <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <span class="text-white font-semibold text-lg tracking-tight">SiPosyandu</span>
        </a>

        <!-- Hero copy -->
        <div class="relative z-10 space-y-6 max-w-md">
            <div class="flex items-center space-x-2">
                <span class="pulse-dot inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-emerald-400 text-xs font-medium uppercase tracking-[0.12em]">Sistem aktif · DKI Jakarta</span>
            </div>

            <h1 class="text-4xl xl:text-[2.75rem] font-semibold text-white tracking-tighter leading-[1.08]">
                Satu platform<br/>untuk semua data<br/><span class="text-emerald-400">posyandu Jakarta.</span>
            </h1>

            <p class="text-zinc-400 text-[0.9375rem] leading-relaxed max-w-[38ch]">
                Pencatatan pertumbuhan, imunisasi, vitamin, dan rujukan — terintegrasi real-time dari posyandu ke puskesmas.
            </p>

            <!-- Stat cards — liquid glass on dark -->
            <div class="space-y-3 pt-4">
                <!-- Card 1 -->
                <div class="shimmer relative overflow-hidden flex items-center justify-between rounded-2xl px-5 py-4"
                     style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);">
                    <div>
                        <p class="text-zinc-500 text-[11px] font-medium uppercase tracking-widest mb-1">Balita Terpantau</p>
                        <p class="mono text-white text-2xl font-semibold tracking-tight">120.847</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2);">
                        <svg class="text-emerald-400" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="flex items-center justify-between rounded-2xl px-5 py-4 ml-8"
                     style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);">
                    <div>
                        <p class="text-zinc-500 text-[11px] font-medium uppercase tracking-widest mb-1">Posyandu Aktif</p>
                        <p class="mono text-white text-2xl font-semibold tracking-tight">342</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                        <svg class="text-blue-400" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom footer -->
        <p class="relative z-10 text-zinc-600 text-xs">
            © {{ date('Y') }} Dinas Kesehatan DKI Jakarta
        </p>
    </div>

    <!-- ===== RIGHT PANEL: Form Area ===== -->
    <div class="relative flex flex-col items-center justify-center min-h-[100dvh] px-6 sm:px-10"
         style="background: linear-gradient(135deg, #f0fdf4 0%, #f9fafb 40%, #eff6ff 100%);">

        <!-- Soft radial behind the card -->
        <div class="pointer-events-none absolute inset-0"
             style="background: radial-gradient(ellipse 80% 60% at 50% 40%, rgba(16,185,129,0.07) 0%, transparent 70%);"></div>

        <!-- Mobile logo -->
        <div class="lg:hidden flex items-center space-x-3 absolute top-6 left-6">
            <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center">
                <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <span class="font-semibold text-zinc-900 text-base tracking-tight">SiPosyandu</span>
        </div>

        <!-- Form card — liquid glass -->
        <div class="relative z-10 w-full max-w-[420px]">

            <div class="liquid-glass rounded-[2rem] p-8 sm:p-10">

                <!-- Header slot -->
                <div class="fade-in delay-1 mb-8">
                    @yield('form-header')
                </div>

                <!-- Content slot -->
                <div class="fade-in delay-2">
                    @yield('content')
                </div>

                <!-- Back link -->
                <div class="fade-in delay-6 mt-6 text-center">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center space-x-1.5 text-sm text-zinc-400 hover:text-zinc-600 transition-colors duration-200">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Halaman utama</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
