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

        :root {
            --bg-dark: #09090b;
            --bg-light: #FAFAFA;
            --text-dark: #FAFAFA;
            --text-light: #09090b;
            --emerald-accent: #059669;
            --emerald-accent-hover: #047857;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-light);
            min-height: 100dvh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Ambient subtle grain */
        .noise-bg {
            position: relative;
        }
        .noise-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.04;
            pointer-events: none;
            z-index: 10;
        }

        /* Staggered load-in cascade */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { opacity: 0; animation: fade-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.12s; }
        .delay-3 { animation-delay: 0.18s; }
        .delay-4 { animation-delay: 0.24s; }
        .delay-5 { animation-delay: 0.30s; }
        .delay-6 { animation-delay: 0.36s; }

        /* Form input — Pristine Solid */
        .form-input {
            width: 100%;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            font-family: 'Outfit', sans-serif;
            color: #18181B;
            transition: all 0.2s ease;
            outline: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        .form-input:hover {
            border-color: #D1D5DB;
        }
        .form-input:focus {
            border-color: var(--emerald-accent);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
        .form-input.is-error {
            border-color: #EF4444;
            background: #FEF2F2;
        }
        .form-input::placeholder { color: #A1A1AA; font-weight: 400; }

        /* Submit button — tactile solid */
        .btn-submit {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border-radius: 0.5rem;
            background: #09090B;
            color: #FFFFFF;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9375rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            position: relative;
        }
        .btn-submit:hover  { background: #27272A; }
        .btn-submit:active { transform: scale(0.99); background: #18181B; }

        .btn-submit-emerald {
            background: var(--emerald-accent);
            color: #FFFFFF;
        }
        .btn-submit-emerald:hover { background: var(--emerald-accent-hover); }

        /* Mono numbers */
        .mono { font-family: 'JetBrains Mono', monospace; font-variant-numeric: tabular-nums; }
        
        .tracking-tighter-plus { letter-spacing: -0.04em; }
        
        /* High-end container shadow */
        .pristine-card {
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 1.5rem;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03), 0 2px 8px -2px rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<body class="antialiased">

<div class="min-h-[100dvh] grid grid-cols-1 lg:grid-cols-2">

    <!-- ===== LEFT PANEL: Deep Dark Editorial ===== -->
    <div class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-zinc-950 px-14 py-12 noise-bg">

        <!-- Subtle geometric structural lines (Wireframe aesthetic) -->
        <div class="pointer-events-none absolute inset-0 z-0 opacity-20">
            <div class="absolute left-14 top-0 bottom-0 w-[1px] bg-zinc-800"></div>
            <div class="absolute right-14 top-0 bottom-0 w-[1px] bg-zinc-800"></div>
            <div class="absolute top-24 left-0 right-0 h-[1px] bg-zinc-800"></div>
            <div class="absolute bottom-24 left-0 right-0 h-[1px] bg-zinc-800"></div>
        </div>

        <!-- Spotlight gradient -->
        <div class="pointer-events-none absolute top-0 right-0 w-[600px] h-[600px] rounded-full z-0"
             style="background: radial-gradient(circle at top right, rgba(5,150,105,0.12) 0%, transparent 60%);"></div>

        <!-- Logo -->
        <a href="{{ route('home') }}" class="relative z-20 flex items-center space-x-3 w-fit pt-2">
            <div class="w-8 h-8 rounded bg-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <span class="text-white font-medium text-lg tracking-tight">SiPosyandu</span>
        </a>

        <!-- Copy & Data -->
        <div class="relative z-20 w-full max-w-md">
            <div class="mb-4 inline-flex items-center space-x-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                <span class="text-zinc-400 text-xs font-medium uppercase tracking-[0.1em]">Infrastruktur Data Jakarta</span>
            </div>

            <h1 class="text-5xl font-semibold text-white tracking-tighter-plus leading-[1.1] mb-6" style="text-wrap: balance;">
                Pantau tumbuh kembang dengan <span class="text-zinc-500">presisi.</span>
            </h1>

            <!-- Editorial Quote Line -->
            <div class="border-l border-zinc-800 pl-5 mb-10">
                <p class="text-zinc-400 text-[0.9375rem] leading-relaxed">
                    Sistem informasi manajemen terpadu yang menghubungkan Orangtua, Kader Posyandu, dan Tenaga Kesehatan Puskesmas secara real-time.
                </p>
            </div>

            <!-- Stats (Solid minimal blocks instead of glassy) -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#121212] border border-zinc-900 rounded-xl p-5">
                    <p class="text-zinc-500 text-[10px] font-medium uppercase tracking-widest mb-1.5">Balita Terpantau</p>
                    <p class="mono text-white text-2xl font-semibold tracking-tight">120k<span class="text-emerald-500">+</span></p>
                </div>
                <div class="bg-[#121212] border border-zinc-900 rounded-xl p-5">
                    <p class="text-zinc-500 text-[10px] font-medium uppercase tracking-widest mb-1.5">Akurasi Z-Score</p>
                    <p class="mono text-white text-2xl font-semibold tracking-tight">99.9<span class="text-emerald-500">%</span></p>
                </div>
            </div>
        </div>

        <!-- Bottom footer -->
        <div class="relative z-20 flex items-center justify-between pb-2">
            <p class="text-zinc-500 text-xs">© {{ date('Y') }} Dinas Kesehatan DKI Jakarta</p>
            <p class="text-zinc-600 text-[10px] uppercase tracking-widest mono">SYS.V2.0</p>
        </div>
    </div>

    <!-- ===== RIGHT PANEL: Pristine Light Form Area ===== -->
    <div class="relative flex flex-col items-center justify-center min-h-[100dvh] px-4 sm:px-10 bg-[#FAFAFA]">

        <!-- Mobile Header (Visible only on small screens) -->
        <div class="lg:hidden absolute top-6 left-6 flex items-center space-x-2">
            <div class="w-7 h-7 rounded bg-emerald-600 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <span class="font-medium text-zinc-900 text-sm tracking-tight">SiPosyandu</span>
        </div>

        <!-- The Pristine Form Card -->
        <div class="relative z-10 w-full max-w-[400px]">
            <div class="pristine-card p-8 sm:p-10">
                <!-- Header slot -->
                <div class="fade-in delay-1 mb-8">
                    @yield('form-header')
                </div>

                <!-- Content slot -->
                <div class="fade-in delay-2">
                    @yield('content')
                </div>
            </div>

            <!-- Back link -->
            <div class="fade-in delay-6 mt-8 text-center">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center space-x-2 text-[13px] font-medium text-zinc-400 hover:text-zinc-900 transition-colors duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
        </div>
    </div>

</div>

</body>
</html>
