<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiPosyandu Jakarta - Sistem Terintegrasi</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #f9fafb; 
            color: #18181b; 
            overflow-x: hidden; 
        }
        
        /* Perpetual Micro-Interactions */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(-1deg); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0) rotate(2deg); }
            50% { transform: translateY(-8px) rotate(3deg); }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.02); }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .animate-float { animation: float 7s ease-in-out infinite; }
        .animate-float-delayed { animation: float-delayed 8s ease-in-out infinite 1s; }
        .animate-pulse-soft { animation: pulse-soft 4s ease-in-out infinite; }
        
        /* Liquid Glass */
        .liquid-glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 20px 40px -15px rgba(0,0,0,0.05);
        }

        /* Bento Architecture */
        .bento-card {
            background: #ffffff;
            border-radius: 2.5rem;
            border: 1px solid rgba(226, 232, 240, 0.7);
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .bento-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 50px -15px rgba(0,0,0,0.08);
            border-color: rgba(226, 232, 240, 1);
        }
        
        /* Hero Background with radial diffusion */
        .hero-bg {
            background: radial-gradient(circle at 80% -20%, rgba(16, 185, 129, 0.12) 0%, rgba(249, 250, 251, 0) 60%);
        }
        
        /* Hide scrollbar for bento grid container if needed on mobile */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Navigation (Liquid Glass) -->
    <nav class="fixed top-6 left-0 right-0 z-50 px-4">
        <div class="max-w-7xl mx-auto liquid-glass rounded-full px-6 py-3 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="text-zinc-900 font-bold text-xl tracking-tight">SiPosyandu</span>
            </a>

            <!-- Nav Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="#fitur" class="px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/70 rounded-full transition-all duration-200">Fitur</a>
                <a href="#puskesmas" class="px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/70 rounded-full transition-all duration-200">Puskesmas</a>
                <a href="#cara-kerja" class="px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/70 rounded-full transition-all duration-200">Cara Kerja</a>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors hidden sm:block px-3 py-2">Dasbor Saya</a>
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-all active:scale-[0.98] shadow-lg shadow-emerald-600/20">
                        Buka Aplikasi
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors px-3 py-2 hidden sm:block">Masuk</a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full bg-zinc-950 text-white text-sm font-medium hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-lg shadow-zinc-900/20">
                        Akses Portal
                    </a>
                @endauth
            </div>
        </div>
    </nav>


    <!-- Hero Section -->
    <section class="relative min-h-[100dvh] flex items-center hero-bg pt-32 pb-16 overflow-hidden">
        <!-- Minimal Grid Pattern Background -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e5e7eb_1px,transparent_1px),linear-gradient(to_bottom,#e5e7eb_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-30 -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-8 items-center">
                
                <!-- Left: Typography & Copy -->
                <div class="max-w-2xl relative z-20">
                    <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 text-sm font-medium mb-8">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>V 2.0 Infrastruktur Digital Cerdas</span>
                    </div>
                    
                    <h1 class="text-5xl sm:text-6xl md:text-7xl font-semibold text-zinc-950 tracking-tighter leading-[1.05] mb-8">
                        Pantau tumbuh <br class="hidden sm:block"/>
                        kembang dengan <br class="hidden sm:block"/>
                        <span class="text-emerald-600">data presisi.</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-zinc-600 leading-relaxed max-w-[50ch] mb-12">
                        Platform manajemen terpadu untuk Kader Posyandu, Nakes, dan Orangtua. Identifikasi risiko dini dan pantau jadwal imunisasi secara real-time.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-8 py-4 rounded-full bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition-all active:scale-[0.98] active:-translate-y-[1px] shadow-xl shadow-emerald-600/20 flex items-center group w-full sm:w-auto justify-center">
                                Buka Dasbor
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-4 rounded-full bg-zinc-950 text-white font-medium hover:bg-zinc-800 transition-all active:scale-[0.98] active:-translate-y-[1px] shadow-xl shadow-zinc-900/20 flex items-center group w-full sm:w-auto justify-center">
                                Masuk ke Portal
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endauth
                        <a href="#fitur" class="px-8 py-4 rounded-full bg-white text-zinc-900 font-medium hover:bg-zinc-50 border border-slate-200 transition-all active:scale-[0.98] w-full sm:w-auto justify-center text-center">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                <!-- Right: Asymmetric Floating UI Composition -->
                <div class="relative h-[600px] w-full hidden lg:block perspective-1000">
                    
                    <!-- Main Card (Back layer) -->
                    <div class="absolute right-0 top-16 w-[420px] bg-white rounded-[2.5rem] border border-slate-200 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.08)] p-8 animate-float-delayed origin-bottom-right rotate-2">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="font-semibold text-zinc-900 text-lg">Jadwal Imunisasi</h3>
                            <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        <div class="space-y-5 relative">
                            <!-- Shimmer effect overlay for perpetual motion -->
                            <div class="absolute inset-0 -translate-x-[150%] bg-gradient-to-r from-transparent via-white/60 to-transparent animate-[shimmer_2.5s_infinite] pointer-events-none z-10"></div>
                            
                            <div class="flex items-start space-x-4 border-l-[3px] border-emerald-500 pl-4 py-1 relative">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-zinc-900">DPT-HB-Hib 1</p>
                                    <p class="text-xs text-zinc-500 mt-1">Usia 2 Bulan</p>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs rounded-full font-medium">Selesai</span>
                            </div>
                            
                            <div class="flex items-start space-x-4 border-l-[3px] border-slate-200 pl-4 py-1 relative">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-zinc-900">Polio Tetes 2</p>
                                    <p class="text-xs text-zinc-500 mt-1">Usia 3 Bulan</p>
                                </div>
                                <span class="px-2.5 py-1 bg-amber-50 border border-amber-100 text-amber-700 text-xs rounded-full font-medium">Bulan Depan</span>
                            </div>
                            
                            <div class="flex items-start space-x-4 border-l-[3px] border-slate-200 pl-4 py-1 opacity-40 relative">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-zinc-900">Campak Rubella</p>
                                    <p class="text-xs text-zinc-500 mt-1">Usia 9 Bulan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Glass Card (Front layer, overlapping) -->
                    <div class="absolute left-8 bottom-28 w-80 p-6 liquid-glass rounded-[2rem] animate-float -rotate-2 z-20">
                        <div class="flex items-center space-x-4 mb-5">
                            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm bg-zinc-100 flex items-center justify-center">
                                <span class="text-sm font-bold text-zinc-500">AK</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">Aksara K.</p>
                                <div class="flex items-center space-x-1.5 mt-0.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <p class="text-xs text-zinc-600 font-medium">Gizi Normal, BB Naik</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white/70 p-4 rounded-2xl border border-white/80 shadow-sm">
                                <p class="text-[11px] font-medium text-zinc-500 mb-1 uppercase tracking-wider">Status BB/U</p>
                                <p class="text-xl font-bold text-zinc-900 font-mono tracking-tight">0.85 <span class="text-xs font-sans font-medium text-emerald-600 ml-0.5">SD</span></p>
                            </div>
                            <div class="bg-white/70 p-4 rounded-2xl border border-white/80 shadow-sm">
                                <p class="text-[11px] font-medium text-zinc-500 mb-1 uppercase tracking-wider">Status TB/U</p>
                                <p class="text-xl font-bold text-zinc-900 font-mono tracking-tight">1.12 <span class="text-xs font-sans font-medium text-emerald-600 ml-0.5">SD</span></p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Bento Grid 2.0 Section -->
    <section id="fitur" class="py-24 bg-white relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-16 max-w-2xl">
                <h2 class="text-4xl md:text-5xl font-semibold text-zinc-950 tracking-tight mb-5 leading-tight">Infrastruktur Data <br/><span class="text-zinc-400">Posyandu Digital.</span></h2>
                <p class="text-lg text-zinc-600 leading-relaxed">Arsitektur data yang dirancang untuk mendukung pengambilan keputusan cepat oleh tenaga kesehatan dan memberikan wawasan harian bagi orangtua.</p>
            </div>

            <!-- Bento Grid Architecture -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[auto]">
                
                <!-- Large Card (Span 2) -->
                <div class="md:col-span-2 bento-card p-10 lg:p-12 flex flex-col justify-between relative overflow-hidden group min-h-[400px]">
                    <!-- Subtle background radial glow on hover -->
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
                    
                    <div class="mb-12 relative z-10 max-w-md">
                        <div class="w-14 h-14 bg-zinc-950 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-zinc-900/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-semibold text-zinc-950 mb-4 tracking-tight">Monitoring SKDN Otomatis</h3>
                        <p class="text-zinc-600 leading-relaxed">Kader tidak perlu menghitung rekapitulasi persentase secara manual. Sistem mengagregasi data kunjungan dan status gizi menjadi visualisasi langsung.</p>
                    </div>

                    <!-- Perpetual Interaction: Shifting Data Bars -->
                    <div class="h-32 flex items-end space-x-4 w-full max-w-md bg-zinc-50/50 rounded-2xl p-6 border border-slate-100 relative z-10 mt-auto">
                        <div class="w-1/4 bg-zinc-200 rounded-t-lg transition-all duration-1000 h-[30%]"></div>
                        <div class="w-1/4 bg-emerald-400 rounded-t-lg transition-all duration-1000 h-[65%] animate-pulse-soft"></div>
                        <div class="w-1/4 bg-emerald-500 rounded-t-lg transition-all duration-1000 h-[85%]"></div>
                        <div class="w-1/4 bg-emerald-600 rounded-t-lg transition-all duration-1000 h-[50%] animate-pulse-soft" style="animation-delay: 1.5s;"></div>
                    </div>
                </div>

                <!-- Small Focus Card -->
                <div class="bento-card p-10 flex flex-col justify-between min-h-[400px]">
                    <div class="mb-8">
                        <div class="w-14 h-14 bg-blue-50 border border-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-zinc-950 mb-3 tracking-tight">Kalkulasi Z-Score WHO</h3>
                        <p class="text-zinc-600 text-sm leading-relaxed">Deteksi risiko *stunting* atau gizi buruk secara akurat berdasarkan standar kesehatan internasional yang terintegrasi.</p>
                    </div>
                    
                    <div class="bg-zinc-950 rounded-2xl p-6 text-white mt-auto shadow-xl shadow-zinc-900/20">
                        <p class="text-xs font-medium text-zinc-400 mb-2 uppercase tracking-widest">Deteksi Cepat</p>
                        <div class="flex items-center space-x-3">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <span class="text-xl font-semibold tracking-tight">Status Gizi Normal</span>
                        </div>
                    </div>
                </div>

                <!-- Wide Horizontal Card (Span 3) -->
                <div class="md:col-span-3 bento-card p-10 lg:p-12 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-10">
                    <div class="max-w-xl">
                        <h3 class="text-2xl md:text-3xl font-semibold text-zinc-950 mb-4 tracking-tight">Otoritas Berjenjang (RBAC)</h3>
                        <p class="text-zinc-600 leading-relaxed">Satu platform melayani berbagai tingkat akses dengan aman. Dari pantauan Orangtua, input Kader, intervensi Nakes, hingga makro-analitik tingkat Kota.</p>
                    </div>
                    
                    <!-- Roles Pill Tags -->
                    <div class="flex flex-wrap gap-3 max-w-md w-full">
                        <span class="px-5 py-2 bg-zinc-900 text-white rounded-full text-sm font-medium shadow-sm">Admin Kota</span>
                        <span class="px-5 py-2 bg-zinc-100 text-zinc-800 border border-zinc-200 rounded-full text-sm font-medium">Kecamatan</span>
                        <span class="px-5 py-2 bg-zinc-100 text-zinc-800 border border-zinc-200 rounded-full text-sm font-medium">Kelurahan</span>
                        <span class="px-5 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-sm font-medium">Nakes</span>
                        <span class="px-5 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-sm font-medium">Kader</span>
                        <span class="px-5 py-2 bg-white border border-slate-200 text-zinc-700 rounded-full text-sm font-medium shadow-sm">Orangtua</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats/Impact Section -->
    <section class="py-24 bg-zinc-950 relative overflow-hidden">
        <!-- Decorative radial glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-emerald-900/30 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <p class="text-emerald-400 text-sm font-medium uppercase tracking-widest mb-4">Dampak Nyata di Jakarta</p>
                <h2 class="text-4xl md:text-5xl font-semibold text-white tracking-tight leading-tight">Ribuan keluarga <br/><span class="text-zinc-400">sudah terlindungi.</span></h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center group">
                    <p class="text-5xl md:text-6xl font-bold text-white tracking-tight mb-2 group-hover:text-emerald-400 transition-colors duration-300">340<span class="text-emerald-500">+</span></p>
                    <p class="text-zinc-500 text-sm font-medium">Posyandu Aktif</p>
                </div>
                <div class="text-center group">
                    <p class="text-5xl md:text-6xl font-bold text-white tracking-tight mb-2 group-hover:text-emerald-400 transition-colors duration-300">44<span class="text-emerald-500">+</span></p>
                    <p class="text-zinc-500 text-sm font-medium">Puskesmas Mitra</p>
                </div>
                <div class="text-center group">
                    <p class="text-5xl md:text-6xl font-bold text-white tracking-tight mb-2 group-hover:text-emerald-400 transition-colors duration-300">120<span class="text-emerald-500">rb</span></p>
                    <p class="text-zinc-500 text-sm font-medium">Balita Terpantau</p>
                </div>
                <div class="text-center group">
                    <p class="text-5xl md:text-6xl font-bold text-white tracking-tight mb-2 group-hover:text-emerald-400 transition-colors duration-300">98<span class="text-emerald-500">%</span></p>
                    <p class="text-zinc-500 text-sm font-medium">Akurasi Data Gizi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Puskesmas Promotion Section -->
    <section id="puskesmas" class="py-28 bg-[#f9fafb]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-16">
                <div>
                    <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full border border-blue-200 bg-blue-50 text-blue-700 text-sm font-medium mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Jaringan Puskesmas Jakarta</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-semibold text-zinc-950 tracking-tight leading-tight mb-4">
                        Pelayanan prima, <br/><span class="text-zinc-400">satu ekosistem digital.</span>
                    </h2>
                    <p class="text-lg text-zinc-600 max-w-xl leading-relaxed">Setiap Puskesmas di DKI Jakarta kini terhubung langsung ke platform SiPosyandu — mempercepat rujukan, memudahkan pencatatan, dan meningkatkan kualitas layanan primer untuk seluruh warga.</p>
                </div>
                <a href="{{ route('login') }}" class="flex-shrink-0 px-7 py-3.5 rounded-full bg-zinc-950 text-white font-medium hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-lg shadow-zinc-900/15 flex items-center group text-sm">
                    Daftarkan Puskesmas
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Service Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Card: Rujukan Digital -->
                <div class="bento-card p-8 group">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 border border-red-100 text-red-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-950 mb-3 tracking-tight">Rujukan Digital Instan</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Balita yang terdeteksi berisiko gizi buruk atau perlu penanganan lanjutan dapat langsung dirujuk secara digital ke Puskesmas terdekat, tanpa proses administratif berbelit.</p>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-zinc-400 font-medium">Waktu respons</span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">&#60; 2 Menit</span>
                    </div>
                </div>

                <!-- Card: Rekam Medis Terkoneksi -->
                <div class="bento-card p-8 group">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-950 mb-3 tracking-tight">Rekam Medis Terkoneksi</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Nakes Puskesmas dapat mengakses riwayat lengkap pertumbuhan, imunisasi, dan catatan kunjungan balita secara real-time — tidak perlu buku KIA fisik yang mudah hilang.</p>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-zinc-400 font-medium">Data tersinkronisasi</span>
                        <span class="text-xs font-bold text-violet-600 bg-violet-50 px-2.5 py-1 rounded-full border border-violet-100">Real-time</span>
                    </div>
                </div>

                <!-- Card: Jadwal Imunisasi -->
                <div class="bento-card p-8 group">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-950 mb-3 tracking-tight">Pengingat Jadwal Imunisasi</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Sistem otomatis mengirimkan pengingat jadwal imunisasi berikutnya kepada orangtua dan Kader, memastikan tidak ada satu pun balita yang tertinggal jadwal vaksinasi nasional.</p>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-zinc-400 font-medium">Tingkat kepatuhan</span>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100">Naik 34%</span>
                    </div>
                </div>

                <!-- Card: Laporan Analitik -->
                <div class="bento-card p-8 group">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-950 mb-3 tracking-tight">Laporan SKDN Otomatis</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Puskesmas mendapatkan laporan SKDN (Sasaran, Kunjungan, Ditimbang, Naik) secara otomatis setiap bulan, siap kirim ke Dinas Kesehatan tanpa input manual yang memakan waktu.</p>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-zinc-400 font-medium">Hemat waktu pelaporan</span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">~8 Jam/Bulan</span>
                    </div>
                </div>

                <!-- Card: Intervensi Dini -->
                <div class="bento-card p-8 group">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-100 text-sky-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-950 mb-3 tracking-tight">Intervensi Dini Stunting</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Algoritma deteksi dini mengidentifikasi pola pertumbuhan yang menyimpang dari kurva WHO sebelum kondisi menjadi kronis — memberi jendela intervensi yang tepat waktu bagi Nakes.</p>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-zinc-400 font-medium">Akurasi deteksi</span>
                        <span class="text-xs font-bold text-sky-600 bg-sky-50 px-2.5 py-1 rounded-full border border-sky-100">94.7% Presisi</span>
                    </div>
                </div>

                <!-- Card: Multi-Wilayah -->
                <div class="bento-card p-8 group">
                    <div class="w-12 h-12 rounded-2xl bg-zinc-100 border border-zinc-200 text-zinc-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-950 mb-3 tracking-tight">Cakupan 5 Kota Jakarta</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed">Dari Jakarta Selatan hingga Kepulauan Seribu — seluruh wilayah administrasi DKI Jakarta terpantau dalam satu dasbor terpusat dengan filter kecamatan dan kelurahan yang presisi.</p>
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-zinc-400 font-medium">Cakupan wilayah</span>
                        <span class="text-xs font-bold text-zinc-600 bg-zinc-100 px-2.5 py-1 rounded-full border border-zinc-200">6 Kab/Kota</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Workflow / How It Works Section -->
    <section id="cara-kerja" class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <p class="text-emerald-600 text-sm font-medium uppercase tracking-widest mb-4">Alur Kerja Terintegrasi</p>
                <h2 class="text-4xl md:text-5xl font-semibold text-zinc-950 tracking-tight">Dari Posyandu ke <br/><span class="text-zinc-400">Puskesmas, tanpa hambatan.</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-0 relative">
                <!-- Connector line (desktop) -->
                <div class="hidden md:block absolute top-10 left-[12.5%] right-[12.5%] h-[2px] bg-gradient-to-r from-emerald-200 via-emerald-400 to-emerald-200 z-0"></div>
                
                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center text-center px-6 mb-12 md:mb-0">
                    <div class="w-20 h-20 rounded-full bg-zinc-950 text-white flex items-center justify-center mb-6 shadow-2xl shadow-zinc-900/20 ring-4 ring-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-2">Langkah 1</span>
                    <h4 class="text-lg font-semibold text-zinc-950 mb-2 tracking-tight">Penimbangan di Posyandu</h4>
                    <p class="text-sm text-zinc-500 leading-relaxed">Kader mencatat berat badan, tinggi badan, dan lingkar kepala balita langsung di aplikasi.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center text-center px-6 mb-12 md:mb-0">
                    <div class="w-20 h-20 rounded-full bg-emerald-500 text-white flex items-center justify-center mb-6 shadow-2xl shadow-emerald-500/30 ring-4 ring-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 7h.01M15 7h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-2">Langkah 2</span>
                    <h4 class="text-lg font-semibold text-zinc-950 mb-2 tracking-tight">Analisis Z-Score Otomatis</h4>
                    <p class="text-sm text-zinc-500 leading-relaxed">Sistem kalkulasi Z-Score WHO langsung menentukan status gizi dan menandai anomali pertumbuhan.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center text-center px-6 mb-12 md:mb-0">
                    <div class="w-20 h-20 rounded-full bg-emerald-500 text-white flex items-center justify-center mb-6 shadow-2xl shadow-emerald-500/30 ring-4 ring-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-2">Langkah 3</span>
                    <h4 class="text-lg font-semibold text-zinc-950 mb-2 tracking-tight">Notifikasi ke Nakes</h4>
                    <p class="text-sm text-zinc-500 leading-relaxed">Tenaga Kesehatan Puskesmas menerima notifikasi instan dan dapat merespons secara langsung melalui platform.</p>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 flex flex-col items-center text-center px-6">
                    <div class="w-20 h-20 rounded-full bg-zinc-950 text-white flex items-center justify-center mb-6 shadow-2xl shadow-zinc-900/20 ring-4 ring-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-2">Langkah 4</span>
                    <h4 class="text-lg font-semibold text-zinc-950 mb-2 tracking-tight">Intervensi & Pemantauan</h4>
                    <p class="text-sm text-zinc-500 leading-relaxed">Nakes memberikan intervensi gizi, dan perkembangannya terpantau secara berkala hingga status kembali normal.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-28 bg-[#f9fafb] relative overflow-hidden">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e5e7eb_1px,transparent_1px),linear-gradient(to_bottom,#e5e7eb_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_80%_80%_at_50%_50%,#000_60%,transparent_100%)] opacity-25"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-500 text-white mb-8 shadow-2xl shadow-emerald-500/30 mx-auto animate-float">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            
            <h2 class="text-4xl md:text-6xl font-semibold text-zinc-950 tracking-tight leading-tight mb-6">
                Mulai jaga kesehatan <br/><span class="text-emerald-600">generasi Jakarta.</span>
            </h2>
            <p class="text-lg text-zinc-600 max-w-2xl mx-auto leading-relaxed mb-12">
                Bergabunglah dengan ratusan Puskesmas dan ribuan Kader yang sudah menggunakan SiPosyandu untuk memastikan setiap balita Jakarta tumbuh sehat dan kuat.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="px-10 py-4 rounded-full bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition-all active:scale-[0.98] shadow-xl shadow-emerald-600/20 flex items-center group text-base w-full sm:w-auto justify-center">
                    Masuk ke Portal
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="#fitur" class="px-10 py-4 rounded-full bg-white text-zinc-900 font-medium hover:bg-zinc-50 border border-slate-200 transition-all active:scale-[0.98] shadow-sm text-base w-full sm:w-auto text-center">
                    Lihat Semua Fitur
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->

    <footer class="bg-zinc-950 text-zinc-400 py-16 border-t border-zinc-900 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-3 mb-6 md:mb-0">
                    <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="text-white font-medium tracking-tight">SiPosyandu Jakarta</span>
                </div>
                
                <div class="flex items-center space-x-6 text-sm">
                    <a href="{{ route('admin.login') }}" class="text-emerald-500 hover:text-emerald-400 font-medium transition-colors">Portal Petugas</a>
                    <a href="#" class="hover:text-white transition-colors">Panduan</a>
                    <a href="#" class="hover:text-white transition-colors">Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Dukungan</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-zinc-900/50 flex flex-col md:flex-row items-center justify-between text-xs text-zinc-600">
                <p>© {{ date('Y') }} Sistem Informasi Manajemen Posyandu Terpadu.</p>
                <p class="mt-2 md:mt-0">Dirancang untuk Jakarta Sehat.</p>
            </div>
        </div>
    </footer>

</body>
</html>
