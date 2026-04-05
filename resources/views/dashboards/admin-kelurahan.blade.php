@extends('layouts.app')

@section('title', 'Dashboard Admin Kelurahan - SiPosyandu')
@section('page-title', 'Dashboard Kelurahan ' . $kelurahan->name)

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    {{-- Info Kelurahan --}}
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $kelurahan->name }}</h2>
                <p class="text-primary-100 mt-1">{{ $kelurahan->kecamatan->name }}, DKI Jakarta</p>
            </div>
            <div class="text-right">
                <p class="text-primary-100 text-sm">Total Posyandu</p>
                <p class="text-3xl font-bold">{{ $totalPosyandu }}</p>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card
            title="Posyandu Aktif"
            :value="$totalPosyandu"
            color="primary"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Total Balita"
            :value="$totalBalita"
            color="warning"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
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
            color="error"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Laporan SKDN & Status Gizi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Laporan SKDN --}}
        <x-card title="📋 Laporan SKDN - Bulan Ini">
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

        {{-- Status Gizi --}}
        <x-card title="📊 Distribusi Status Gizi">
            <div class="grid grid-cols-5 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ $statusGizi['normal'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Normal</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-600">{{ $statusGizi['kurang'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Kurang</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600">{{ $statusGizi['lebih'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Lebih</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-2xl font-bold text-red-600">{{ $statusGizi['gizi_buruk'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Gizi Buruk</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-2xl font-bold text-purple-600">{{ $statusGizi['stunting'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">Stunting</p>
                </div>
            </div>
        </x-card>
    </div>

    {{-- Data Seluruh Posyandu di Kelurahan --}}
    <x-card title="📍 Data Seluruh Posyandu di Kelurahan">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Koordinator</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($posyanduDetails as $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $detail['posyandu']->name }}</p>
                            <p class="text-xs text-gray-500">{{ $detail['posyandu']->address }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $detail['posyandu']->kaderKoordinator?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $detail['balita_count'] }} balita</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-gray-600">{{ $detail['posyandu']->jadwal_hari }}, Minggu ke-{{ $detail['posyandu']->jadwal_minggu_ke }}</span>
                            <p class="text-xs text-gray-500">{{ $detail['posyandu']->jadwal_jam_mulai }} - {{ $detail['posyandu']->jadwal_jam_selesai }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin-kelurahan.posyandu.show', $detail['posyandu']) }}" class="text-primary-600 hover:text-primary-900 font-medium">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Manajemen Kader Posyandu --}}
    <x-card title="👥 Manajemen Kader Posyandu">
        <x-slot:headerAction>
            <a href="{{ route('admin-kelurahan.users.index') }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">Kelola Semua →</a>
        </x-slot:headerAction>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kader</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kaders as $kader)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $kader->name }}</p>
                            <p class="text-xs text-gray-500">NIK: {{ $kader->nik }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $kader->posyandu?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $kader->email }}</td>
                        <td class="px-4 py-3">
                            @if($kader->is_active)
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <x-empty-state message="Belum ada kader terdaftar" />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Jadwal Kegiatan Posyandu --}}
    <x-card title="📅 Jadwal Kegiatan Posyandu">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Koordinator</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jadwalKegiatan->take(10) as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $item['posyandu']->name }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $item['jadwal']['tanggal']->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $item['jadwal']['hari'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $item['jadwal']['jam_mulai'] }} - {{ $item['jadwal']['jam_selesai'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $item['posyandu']->kaderKoordinator?->name ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Stok Obat & Vitamin Posyandu --}}
    <x-card title="💊 Stok Obat & Vitamin (Bulan Ini)">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($imunisasiTypes as $imunisasi)
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $imunisasi->total }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $imunisasi->jenis_imunisasi }}</p>
            </div>
            @empty
            <div class="col-span-full">
                <x-empty-state message="Belum ada data imunisasi bulan ini" />
            </div>
            @endforelse
        </div>
        <p class="text-xs text-gray-500 mt-4 italic">*Data menunjukkan jumlah pemberian imunisasi/vitamin di semua posyandu kelurahan ini</p>
    </x-card>

    {{-- Reminder Jadwal Posyandu --}}
    @if($reminderJadwal->count() > 0)
    <x-alert type="warning" title="🔔 Reminder Jadwal Posyandu Mendatang">
        <div class="mt-4 space-y-3">
            @foreach($reminderJadwal as $reminder)
            <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-yellow-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $reminder['posyandu']->name }}</p>
                        <p class="text-sm text-gray-600">{{ $reminder['jadwal']['tanggal']->format('d M Y') }} - {{ $reminder['jadwal']['hari'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @if($reminder['days_until'] === 0)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Hari Ini!</span>
                    @elseif($reminder['days_until'] === 1)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Besok</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">{{ $reminder['days_until'] }} hari lagi</span>
                    @endif
                    <p class="text-xs text-gray-500 mt-1">{{ $reminder['jadwal']['jam_mulai'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </x-alert>
    @endif

    {{-- Gizi Buruk Alert --}}
    @if($giziBurukBalitas->count() > 0)
    <x-card title="⚠️ Balita Gizi Buruk - Perlu Tindak Lanjut">
        <x-slot:headerAction>
            <a href="{{ route('admin-kelurahan.balita.index', ['status_gizi' => 'gizi_buruk']) }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">Lihat Semua →</a>
        </x-slot:headerAction>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Umur</th>
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
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->posyandu->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->age_months }} bulan</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $balita->pertumbuhanRecords->first()->z_score_bbu }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin-kelurahan.balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900 font-medium">Lihat Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif

    {{-- Quick Actions --}}
    <div class="flex flex-wrap gap-4">
        <x-button href="{{ route('admin-kelurahan.posyandu.index') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Kelola Posyandu
        </x-button>
        <x-button href="{{ route('admin-kelurahan.users.index') }}" variant="secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Kelola Kader
        </x-button>
        <x-button href="{{ route('admin-kelurahan.balita.index') }}" variant="outline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Data Balita
        </x-button>
    </div>
</div>
@endsection
