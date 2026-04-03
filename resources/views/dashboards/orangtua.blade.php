@extends('layouts.app')

@section('title', 'Dashboard Orangtua - SiPosyandu')
@section('page-title', 'Dashboard Orangtua')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    @forelse($balitas as $balita)
    <!-- Card untuk setiap anak -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $balita->name }}</h2>
                        <p class="text-primary-100">
                            {{ $balita->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} • 
                            {{ $balita->age_months }} bulan
                        </p>
                        <p class="text-primary-100 text-sm">NIK: {{ $balita->nik }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-primary-100 text-sm">Status Gizi Terakhir</p>
                    @php
                        $lastGrowth = $balita->pertumbuhanRecords->first();
                        $statusClass = match($lastGrowth?->status_gizi) {
                            'normal' => 'bg-green-100 text-green-800',
                            'kurang' => 'bg-yellow-100 text-yellow-800',
                            'lebih' => 'bg-blue-100 text-blue-800',
                            'gizi_buruk' => 'bg-red-100 text-red-800',
                            'stunting' => 'bg-purple-100 text-purple-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    @if($lastGrowth)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $lastGrowth->status_gizi)) }}
                        </span>
                        <p class="text-xs text-primary-100 mt-1">
                            {{ $lastGrowth->tanggal->format('d M Y') }}
                        </p>
                    @else
                        <span class="text-primary-100">Belum ada data</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informasi Dasar -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Dasar
                    </h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium">Tanggal Lahir:</span> {{ $balita->birth_date->format('d F Y') }}</p>
                        <p><span class="font-medium">Nama Ibu:</span> {{ $balita->mother_name }}</p>
                        <p><span class="font-medium">Nama Ayah:</span> {{ $balita->father_name ?? '-' }}</p>
                        <p><span class="font-medium">Posyandu:</span> {{ $balita->posyandu?->name ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>

                <!-- Pengukuran Terakhir -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                        Pengukuran Terakhir
                    </h3>
                    @if($lastGrowth)
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><span class="font-medium">Berat Badan:</span> {{ $lastGrowth->berat_badan }} kg</p>
                            <p><span class="font-medium">Tinggi Badan:</span> {{ $lastGrowth->tinggi_badan }} cm</p>
                            @if($lastGrowth->lingkar_kepala)
                                <p><span class="font-medium">Lingkar Kepala:</span> {{ $lastGrowth->lingkar_kepala }} cm</p>
                            @endif
                            <p><span class="font-medium">Tanggal:</span> {{ $lastGrowth->tanggal->format('d M Y') }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Belum ada data pengukuran</p>
                    @endif
                </div>

                <!-- Jadwal Posyandu -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Jadwal Posyandu
                    </h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        @if($balita->posyandu)
                            <p><span class="font-medium">Hari:</span> {{ $balita->posyandu->jadwal_hari ?? '-' }}</p>
                            <p><span class="font-medium">Minggu:</span> Ke-{{ $balita->posyandu->jadwal_minggu_ke ?? '-' }} setiap bulan</p>
                            <p><span class="font-medium">Jam:</span> {{ $balita->posyandu->jadwal_jam_mulai ?? '-' }} - {{ $balita->posyandu->jadwal_jam_selesai ?? '-' }}</p>
                        @else
                            <p class="text-gray-500">Posyandu tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Riwayat Imunisasi -->
            @php
                $ageMonths = $balita->age_months;
                $givenVaccines = $balita->imunisasiRecords->pluck('jenis_imunisasi')->toArray();
                // Jadwal imunisasi nasional Indonesia: [nama, usia_bulan, label_usia]
                $jadwalImunisasi = [
                    ['kode' => 'hepatitis_b_0', 'nama' => 'Hepatitis B', 'dosis' => 'Dosis 0', 'usia_bulan' => 0, 'label' => '0-7 hari'],
                    ['kode' => 'BCG', 'nama' => 'BCG', 'dosis' => '', 'usia_bulan' => 1, 'label' => '1 bulan'],
                    ['kode' => 'Polio_1', 'nama' => 'Polio', 'dosis' => 'Dosis 1', 'usia_bulan' => 1, 'label' => '1 bulan'],
                    ['kode' => 'DPT_HB_Hib_1', 'nama' => 'DPT-HB-Hib', 'dosis' => 'Dosis 1', 'usia_bulan' => 2, 'label' => '2 bulan'],
                    ['kode' => 'Polio_2', 'nama' => 'Polio', 'dosis' => 'Dosis 2', 'usia_bulan' => 2, 'label' => '2 bulan'],
                    ['kode' => 'DPT_HB_Hib_2', 'nama' => 'DPT-HB-Hib', 'dosis' => 'Dosis 2', 'usia_bulan' => 3, 'label' => '3 bulan'],
                    ['kode' => 'Polio_3', 'nama' => 'Polio', 'dosis' => 'Dosis 3', 'usia_bulan' => 3, 'label' => '3 bulan'],
                    ['kode' => 'DPT_HB_Hib_3', 'nama' => 'DPT-HB-Hib', 'dosis' => 'Dosis 3', 'usia_bulan' => 4, 'label' => '4 bulan'],
                    ['kode' => 'Polio_4', 'nama' => 'Polio', 'dosis' => 'Dosis 4', 'usia_bulan' => 4, 'label' => '4 bulan'],
                    ['kode' => 'Campak', 'nama' => 'Campak/MR', 'dosis' => 'Dosis 1', 'usia_bulan' => 9, 'label' => '9 bulan'],
                    ['kode' => 'MR', 'nama' => 'MR', 'dosis' => 'Dosis 2', 'usia_bulan' => 18, 'label' => '18 bulan'],
                    ['kode' => 'DPT_HB_Hib_4', 'nama' => 'DPT-HB-Hib', 'dosis' => 'Booster', 'usia_bulan' => 18, 'label' => '18 bulan'],
                ];
                $totalJadwal = count($jadwalImunisasi);
                $sudahDiberikan = collect($jadwalImunisasi)->filter(fn($v) => in_array($v['kode'], $givenVaccines))->count();
                $progressPct = $totalJadwal > 0 ? round(($sudahDiberikan / $totalJadwal) * 100) : 0;
            @endphp
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        Status Imunisasi
                    </h3>
                    <span class="text-sm font-medium text-primary-600">{{ $sudahDiberikan }}/{{ $totalJadwal }} selesai</span>
                </div>

                {{-- Progress bar --}}
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-primary-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressPct }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $progressPct }}% jadwal imunisasi selesai</p>
                </div>

                {{-- Grid jadwal imunisasi --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 mb-4">
                    @foreach($jadwalImunisasi as $jadwal)
                        @php
                            $sudah = in_array($jadwal['kode'], $givenVaccines);
                            $terlambat = !$sudah && $ageMonths > $jadwal['usia_bulan'];
                            $mendatang = !$sudah && !$terlambat;
                            // Cari tanggal pemberian jika sudah ada
                            $record = $balita->imunisasiRecords->firstWhere('jenis_imunisasi', $jadwal['kode']);
                        @endphp
                        <div class="flex items-start p-2.5 rounded-lg border {{ $sudah ? 'bg-green-50 border-green-200' : ($terlambat ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200') }}">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($sudah)
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($terlambat)
                                    <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-2 min-w-0">
                                <p class="text-xs font-medium {{ $sudah ? 'text-green-800' : ($terlambat ? 'text-red-700' : 'text-gray-700') }} leading-tight">
                                    {{ $jadwal['nama'] }}{{ $jadwal['dosis'] ? ' ('.$jadwal['dosis'].')' : '' }}
                                </p>
                                <p class="text-xs {{ $sudah ? 'text-green-600' : ($terlambat ? 'text-red-500' : 'text-gray-400') }}">
                                    @if($sudah && $record)
                                        {{ $record->tanggal_diberikan->format('d M Y') }}
                                    @else
                                        Usia {{ $jadwal['label'] }}
                                    @endif
                                </p>
                                @if($terlambat)
                                    <p class="text-xs text-red-500 font-medium">Belum diberikan</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Legenda --}}
                <div class="flex flex-wrap gap-3 text-xs text-gray-500 mb-3">
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Sudah diberikan
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Terlambat / belum diberikan
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span> Akan datang
                    </span>
                </div>

                {{-- Riwayat tambahan jika ada --}}
                @if($balita->imunisasiRecords->count() > 0)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Riwayat lengkap ({{ $balita->imunisasiRecords->count() }} catatan)</p>
                        <a href="{{ route('orangtua.anak.imunisasi.index', $balita) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                            Lihat semua riwayat imunisasi →
                        </a>
                    </div>
                @else
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            <svg class="w-4 h-4 inline mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Belum ada riwayat imunisasi tercatat. Hubungi kader atau nakes untuk mencatat imunisasi anak Anda.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 pt-6 border-t border-gray-200 flex flex-wrap gap-3">
                <x-button href="{{ route('orangtua.anak.show', $balita) }}" size="sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat Profil Lengkap
                </x-button>
                <x-button href="{{ route('orangtua.anak.pertumbuhan.index', $balita) }}" variant="secondary" size="sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    Riwayat Pertumbuhan
                </x-button>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Data Anak</h3>
        <p class="text-gray-600 mb-6">Data anak Anda akan ditambahkan oleh kader posyandu saat pertama kali mendaftar.</p>
        <p class="text-sm text-gray-500">Silakan hubungi kader posyandu terdekat untuk mendaftarkan anak Anda.</p>
    </div>
    @endforelse
</div>
@endsection
