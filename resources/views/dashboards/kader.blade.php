@extends('layouts.app')

@section('title', 'Dashboard Kader - SiPosyandu')

@section('page-title', 'Dashboard')

@section('sidebar')
<div class="space-y-1">
    <a href="{{ route('kader.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-primary-50 text-primary-700 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('kader.balita.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <span>Data Balita</span>
    </a>
    <a href="{{ route('kader.kehadiran.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <span>Kehadiran</span>
    </a>
    <a href="{{ route('kader.posyandu.show', $posyandu) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <span>Posyandu</span>
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Info Posyandu -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $posyandu->name }}</h2>
                <p class="text-primary-100 mt-1">{{ $posyandu->kelurahan->name }}, {{ $posyandu->kelurahan->kecamatan->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-primary-100 text-sm">Jadwal Posyandu</p>
                <p class="font-semibold">Minggu ke-{{ $posyandu->jadwal_minggu_ke }}, {{ $posyandu->jadwal_hari }}</p>
                <p class="text-sm">{{ $posyandu->jadwal_jam_mulai }} - {{ $posyandu->jadwal_jam_selesai }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total Balita" 
            :value="$totalBalita"
            color="primary"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Hadir Bulan Ini" 
            :value="$hadirBulanIni"
            color="success"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Gizi Buruk" 
            :value="$statusGizi['gizi_buruk']"
            color="danger"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card 
            title="Stunting" 
            :value="$statusGizi['stunting']"
            color="warning"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Jadwal Berikutnya & Laporan SKDN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Jadwal Berikutnya -->
        <x-card title="Jadwal Posyandu Berikutnya">
            <div class="flex items-center space-x-4 p-4 bg-primary-50 rounded-lg">
                <div class="flex-shrink-0 w-16 h-16 bg-primary-500 rounded-lg flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">{{ $jadwalBerikutnya['tanggal']->format('d') }}</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $jadwalBerikutnya['tanggal']->format('l, F Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $jadwalBerikutnya['jam_mulai'] }} - {{ $jadwalBerikutnya['jam_selesai'] }}</p>
                    <p class="text-sm text-gray-600">{{ $jadwalBerikutnya['hari'] }}, Minggu ke-{{ $posyandu->jadwal_minggu_ke }}</p>
                </div>
            </div>
        </x-card>

        <!-- Laporan SKDN -->
        <x-card title="Laporan SKDN - Bulan Ini">
            <div class="grid grid-cols-4 gap-4 text-center">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600">{{ $skdn['sasaran'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Sasaran (S)</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ $skdn['kunjungan'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Kunjungan (K)</p>
                </div>
                <div class="p-4 bg-purple-50 rounded-lg">
                    <p class="text-2xl font-bold text-purple-600">{{ $skdn['diberi'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Diberi (D)</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-2xl font-bold text-red-600">{{ $skdn['nutrisi'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Nutrisi (N)</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cakupan Pelayanan</span>
                    <span class="text-lg font-bold {{ $skdn['coverage'] >= 80 ? 'text-green-600' : 'text-yellow-600' }}">{{ $skdn['coverage'] }}%</span>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary-500 h-2 rounded-full transition-all" style="width: {{ $skdn['coverage'] }}%"></div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Ringkasan Kehadiran Bulan Ini -->
    <x-card title="📊 Ringkasan Kehadiran Bulan Ini">
        <x-slot:headerAction>
            <a href="{{ route('kader.kehadiran.index') }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">Lihat Semua →</a>
        </x-slot:headerAction>
        
        <!-- Statistik Kehadiran -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-blue-50 rounded-lg text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $totalBalita }}</p>
                <p class="text-xs text-gray-600 mt-1">Total Balita</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($avgKehadiran, 1) }}%</p>
                <p class="text-xs text-gray-600 mt-1">Rata-rata Kehadiran</p>
            </div>
            <div class="p-4 bg-purple-50 rounded-lg text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $frekuensiPosyandu }}</p>
                <p class="text-xs text-gray-600 mt-1">Frekuensi Posyandu</p>
            </div>
        </div>

        <!-- Tabel Kehadiran -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hadir</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tidak Hadir</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kehadiranSummary as $summary)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $summary->tanggal->format('d F Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $summary->total_hadir }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $summary->total_tidak_hadir }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $summary->persentase }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ number_format($summary->persentase, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p>Belum ada data kehadiran bulan ini</p>
                                <a href="{{ route('kader.kehadiran.index') }}" class="mt-2 text-primary-600 hover:text-primary-900 text-sm font-medium">Catat Kehadiran Sekarang →</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Status Gizi Pie Chart Placeholder -->
    <x-card title="Distribusi Status Gizi">
        <div class="grid grid-cols-5 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-3xl font-bold text-green-600">{{ $statusGizi['normal'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Normal</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-3xl font-bold text-yellow-600">{{ $statusGizi['kurang'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Kurang</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-3xl font-bold text-blue-600">{{ $statusGizi['lebih'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Lebih</p>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <p class="text-3xl font-bold text-red-600">{{ $statusGizi['gizi_buruk'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Gizi Buruk</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-3xl font-bold text-purple-600">{{ $statusGizi['stunting'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Stunting</p>
            </div>
        </div>
    </x-card>

    <!-- Gizi Buruk Alert -->
    @if($giziBurukBalitas->count() > 0)
    <x-card title="⚠️ Balita Gizi Buruk (Perlu Tindak Lanjut)">
        <x-slot:headerAction>
            <x-button href="{{ route('kader.balita.index', ['status_gizi' => 'gizi_buruk']) }}" variant="outline" size="sm">Lihat Semua</x-button>
        </x-slot:headerAction>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Umur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">BB (kg)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">TB (cm)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Z-Score</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($giziBurukBalitas as $balita)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $balita->name }}</p>
                            <p class="text-xs text-gray-500">{{ $balita->mother_name }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->age_months }} bulan</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->pertumbuhanRecords->first()->berat_badan }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->pertumbuhanRecords->first()->tinggi_badan }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $balita->pertumbuhanRecords->first()->z_score_bbu }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('kader.balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900">Lihat</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-4">
        <x-button href="{{ route('kader.balita.create') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Balita Baru
        </x-button>
        <x-button href="{{ route('kader.balita.index') }}" variant="secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Lihat Semua Balita
        </x-button>
    </div>
</div>
@endsection
