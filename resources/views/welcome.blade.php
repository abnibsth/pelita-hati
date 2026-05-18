<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiPosyandu Jakarta - Infrastruktur Data Kesehatan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Using Outfit as a clean Grotesk representation -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --bg-base: #FAFAFA;
            --text-main: #0A0A0A;
            --emerald-brand: #059669;
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: var(--bg-base); 
            color: var(--text-main); 
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* High-End Design System Overrides */
        .tracking-tighter-plus { letter-spacing: -0.04em; }
        .tabular-nums { font-variant-numeric: tabular-nums; }
        
        .bento-surface {
            background: #FFFFFF;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .bento-surface:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.05), 0 4px 8px -4px rgba(0, 0, 0, 0.03);
        }

        .dark-surface {
            background-color: #121212;
            color: #FAFAFA;
        }

        /* Ambient subtle grain */
        .noise-bg {
            position: relative;
        }
        .noise-bg::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.025;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body class="selection:bg-emerald-500 selection:text-white noise-bg">

    <!-- Minimalist Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-zinc-200/60">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded bg-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="text-zinc-950 font-semibold text-lg tracking-tight">SiPosyandu</span>
            </a>

            <!-- Actions -->
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950 transition-colors hidden sm:block">Dasbor</a>
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-colors">
                        Buka Aplikasi
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-950 transition-colors hidden sm:block">Masuk</a>
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full bg-zinc-950 text-white text-sm font-medium hover:bg-zinc-800 transition-colors shadow-sm">
                        Akses Portal
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mid Editorial Hero Section -->
    <section class="pt-32 pb-20 px-4 md:px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 relative z-10 order-2 lg:order-1">
                <p class="text-emerald-600 font-semibold tracking-wide uppercase text-xs mb-4">V2.0 Infrastruktur Digital</p>
                <h1 class="text-5xl lg:text-7xl font-semibold text-zinc-950 tracking-tighter-plus leading-[1.05] mb-6">
                    Pantau tumbuh kembang dengan <span class="text-zinc-400">data presisi.</span>
                </h1>
                <p class="text-lg text-zinc-600 leading-relaxed max-w-md mb-10">
                    Platform manajemen terpadu Jakarta. Identifikasi risiko dini dan pantau jadwal imunisasi secara real-time untuk generasi yang lebih sehat.
                </p>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-7 py-3.5 rounded-full bg-zinc-950 text-white font-medium hover:bg-zinc-800 transition-all text-sm">Masuk Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" class="px-7 py-3.5 rounded-full bg-zinc-950 text-white font-medium hover:bg-zinc-800 transition-all text-sm">Mulai Sekarang</a>
                    @endauth
                    <a href="#fitur" class="px-7 py-3.5 rounded-full bg-transparent text-zinc-900 border border-zinc-200 font-medium hover:bg-zinc-50 transition-all text-sm">Pelajari Fitur</a>
                </div>
            </div>

            <!-- Premium Visual Reference -->
            <div class="lg:col-span-7 order-1 lg:order-2">
                <div class="rounded-3xl overflow-hidden border border-zinc-100 shadow-2xl shadow-zinc-200/50 relative aspect-[4/3] lg:aspect-[16/10] bg-zinc-100">
                    <img src="{{ asset('images/landing/hero_siposyandu_1779117397655.png') }}" alt="SiPosyandu Dashboard Visual" class="w-full h-full object-cover" />
                </div>
            </div>

        </div>
    </section>

    <!-- Pristine Gapless Bento Grid -->
    <section id="fitur" class="py-32 bg-white relative z-20">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="mb-16">
                <h2 class="text-4xl md:text-5xl font-semibold text-zinc-950 tracking-tighter-plus leading-tight max-w-2xl">
                    Infrastruktur data posyandu modern.
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Large Feature -->
                <div class="md:col-span-2 bento-surface p-10 flex flex-col justify-between min-h-[420px] overflow-hidden relative">
                    <div class="max-w-md relative z-10">
                        <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-semibold text-zinc-950 mb-3 tracking-tight">Monitoring SKDN Otomatis</h3>
                        <p class="text-zinc-600 leading-relaxed text-sm">Sistem mengagregasi data kunjungan dan status gizi menjadi visualisasi langsung tanpa rekapitulasi manual oleh kader.</p>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-3/4 opacity-80 mix-blend-multiply pointer-events-none">
                        <img src="{{ asset('images/landing/bento_features_siposyandu_1779117411857.png') }}" class="rounded-xl shadow-lg" alt="Bento UI Crop">
                    </div>
                </div>

                <!-- Vertical Feature -->
                <div class="bento-surface p-10 flex flex-col min-h-[420px]">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-zinc-950 mb-3 tracking-tight">Kalkulasi Z-Score WHO</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed mb-auto">Deteksi risiko stunting secara akurat berdasarkan standar kesehatan internasional yang terintegrasi.</p>
                    <div class="mt-8 pt-6 border-t border-zinc-100">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-zinc-100 text-zinc-800 text-xs font-semibold tracking-wide">Akurasi 99.9%</span>
                    </div>
                </div>

                <!-- Horizontal Feature -->
                <div class="md:col-span-3 bento-surface p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
                    <div class="max-w-lg">
                        <h3 class="text-2xl font-semibold text-zinc-950 mb-3 tracking-tight">Otoritas Berjenjang (RBAC)</h3>
                        <p class="text-zinc-600 text-sm leading-relaxed">Satu platform, ragam akses. Dari pantauan Orangtua, input Kader, intervensi Nakes, hingga makro-analitik tingkat Kota.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-4 py-2 bg-zinc-950 text-white rounded-lg text-xs font-medium">Admin Kota</span>
                        <span class="px-4 py-2 bg-zinc-100 text-zinc-800 rounded-lg text-xs font-medium">Kecamatan</span>
                        <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium">Nakes</span>
                        <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium">Kader</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Oversized Metrics Strip -->
    <section class="py-32 dark-surface relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-semibold text-white tracking-tighter-plus leading-tight mb-4">
                        Ribuan keluarga sudah terlindungi.
                    </h2>
                    <p class="text-zinc-400 text-lg">Dampak nyata digitalisasi Posyandu di DKI Jakarta.</p>
                </div>
                
                <div class="grid grid-cols-2 gap-x-8 gap-y-12">
                    <div>
                        <div class="text-6xl md:text-7xl font-semibold tracking-tighter tabular-nums text-white mb-2">340<span class="text-emerald-500">.</span></div>
                        <div class="text-zinc-400 text-sm font-medium tracking-wide uppercase">Posyandu Aktif</div>
                    </div>
                    <div>
                        <div class="text-6xl md:text-7xl font-semibold tracking-tighter tabular-nums text-white mb-2">120<span class="text-emerald-500">k</span></div>
                        <div class="text-zinc-400 text-sm font-medium tracking-wide uppercase">Balita Terpantau</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Puskesmas Promo / Masonry Section -->
    <section class="py-32 bg-[#F7F7F7]">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Image Reference -->
                <div class="relative aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/landing/puskesmas_promo_siposyandu_1779117444347.png') }}" class="w-full h-full object-cover" alt="Tenaga Kesehatan Profesional">
                </div>
                
                <div class="max-w-lg">
                    <h2 class="text-4xl md:text-5xl font-semibold text-zinc-950 tracking-tighter-plus leading-tight mb-6">
                        Satu ekosistem layanan prima.
                    </h2>
                    <p class="text-lg text-zinc-600 leading-relaxed mb-8">
                        Setiap Puskesmas di DKI Jakarta kini terhubung langsung ke platform SiPosyandu — mempercepat rujukan, memudahkan pencatatan, dan meningkatkan kualitas layanan primer.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="mt-1 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mr-4">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-zinc-950 font-semibold mb-1">Rujukan Digital Instan</h4>
                                <p class="text-zinc-600 text-sm">Penanganan lanjutan balita berisiko langsung dirujuk ke Puskesmas terdekat tanpa proses berbelit.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="mt-1 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mr-4">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-zinc-950 font-semibold mb-1">Rekam Medis Terkoneksi</h4>
                                <p class="text-zinc-600 text-sm">Nakes mengakses riwayat pertumbuhan dan imunisasi balita secara real-time tanpa buku KIA fisik.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Rhythm Lines Section -->
    <section class="py-32 bg-white border-t border-zinc-100">
        <div class="max-w-4xl mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl font-semibold text-zinc-950 tracking-tight mb-20">Alur Kerja Terintegrasi</h2>
            
            <div class="relative">
                <!-- Hairline Vertical Line (Mobile) / Horizontal (Desktop) -->
                <div class="absolute left-[27px] top-10 bottom-10 w-[1px] bg-zinc-200 md:hidden"></div>
                <div class="hidden md:block absolute top-[27px] left-10 right-10 h-[1px] bg-zinc-200"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-6 relative z-10 text-left md:text-center">
                    
                    <!-- Step 1 -->
                    <div class="relative flex md:flex-col items-start md:items-center">
                        <div class="w-14 h-14 bg-white border border-zinc-200 rounded-full flex items-center justify-center z-10 mb-0 md:mb-6 mr-6 md:mr-0 flex-shrink-0">
                            <span class="text-sm font-semibold text-zinc-900">01</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-zinc-950 mb-2 tracking-tight">Posyandu</h4>
                            <p class="text-sm text-zinc-500 leading-relaxed">Kader mencatat berat & tinggi badan balita.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative flex md:flex-col items-start md:items-center">
                        <div class="w-14 h-14 bg-white border border-zinc-200 rounded-full flex items-center justify-center z-10 mb-0 md:mb-6 mr-6 md:mr-0 flex-shrink-0">
                            <span class="text-sm font-semibold text-zinc-900">02</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-zinc-950 mb-2 tracking-tight">Z-Score</h4>
                            <p class="text-sm text-zinc-500 leading-relaxed">Sistem kalkulasi status gizi otomatis.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative flex md:flex-col items-start md:items-center">
                        <div class="w-14 h-14 bg-white border border-emerald-200 rounded-full flex items-center justify-center z-10 mb-0 md:mb-6 mr-6 md:mr-0 flex-shrink-0">
                            <span class="text-sm font-semibold text-emerald-600">03</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-zinc-950 mb-2 tracking-tight">Notifikasi</h4>
                            <p class="text-sm text-zinc-500 leading-relaxed">Nakes menerima *alert* jika ada anomali pertumbuhan.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative flex md:flex-col items-start md:items-center">
                        <div class="w-14 h-14 bg-zinc-950 border border-zinc-950 rounded-full flex items-center justify-center z-10 mb-0 md:mb-6 mr-6 md:mr-0 flex-shrink-0">
                            <span class="text-sm font-semibold text-white">04</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-zinc-950 mb-2 tracking-tight">Intervensi</h4>
                            <p class="text-sm text-zinc-500 leading-relaxed">Puskesmas memberikan penanganan terukur.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mini Minimalist CTA Section -->
    <section class="py-40 bg-[#FAFAFA] border-t border-zinc-100 flex items-center justify-center">
        <div class="text-center px-4">
            <div class="w-2 h-2 rounded-full bg-emerald-500 mx-auto mb-8"></div>
            <h2 class="text-4xl md:text-6xl font-semibold text-zinc-950 tracking-tighter-plus mb-10">
                Jaga kesehatan <br/>generasi Jakarta.
            </h2>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-full bg-zinc-950 text-white text-sm font-medium hover:bg-zinc-800 transition-colors">
                Mulai Sekarang
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>

    <!-- Clean Footer -->
    <footer class="bg-white py-12 border-t border-zinc-100">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between text-sm text-zinc-500">
            <div class="flex items-center space-x-2 mb-4 md:mb-0">
                <div class="w-5 h-5 rounded bg-zinc-200 flex items-center justify-center">
                    <svg class="w-3 h-3 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="font-medium text-zinc-900 tracking-tight">SiPosyandu Jakarta</span>
            </div>
            
            <div class="flex space-x-6">
                <a href="{{ route('admin.login') }}" class="hover:text-zinc-900 transition-colors">Admin Portal</a>
                <a href="#" class="hover:text-zinc-900 transition-colors">Panduan</a>
                <a href="#" class="hover:text-zinc-900 transition-colors">Privasi</a>
            </div>
        </div>
    </footer>

</body>
</html>
