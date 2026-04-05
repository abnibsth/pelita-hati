@extends('layouts.app')

@section('title', 'Dashboard Admin Kecamatan - SiPosyandu')
@section('page-title', 'Dashboard Kecamatan ' . $kecamatan->name)

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    {{-- Statistik Kecamatan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card
            title="Kelurahan"
            :value="$totalKelurahan"
            color="primary"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Posyandu"
            :value="$totalPosyandu"
            color="success"
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
    </div>

    {{-- Status Gizi --}}
    <x-card title="📊 Distribusi Status Gizi - {{ $kecamatan->name }}">
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

    {{-- Statistik Posyandu per Kelurahan --}}
    <x-card title="📋 Statistik Posyandu per Kelurahan">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kader</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($posyanduStats as $stat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $stat['kelurahan']->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $stat['posyandu_count'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $stat['balita_count'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $stat['kader_count'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Data Balita per Kelurahan --}}
    <x-card title="👶 Data Balita per Kelurahan">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stunting</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gizi Buruk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stunting Rate</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($balitaPerKelurahan as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $data['kelurahan']->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $data['total_balita'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $data['stunting'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $data['stunting'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $data['gizi_buruk'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $data['gizi_buruk'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-{{ $data['stunting_rate'] > 20 ? 'red' : 'green' }}-500 h-2 rounded-full" style="width: {{ min($data['stunting_rate'], 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ number_format($data['stunting_rate'], 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Monitoring Imunisasi per Kelurahan --}}
    <x-card title="💉 Monitoring Imunisasi Bulan Ini ({{ now()->format('F Y') }})">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Imunisasi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($imunisasiPerKelurahan as $imunisasi)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ is_array($imunisasi) ? $imunisasi['kelurahan_name'] : $imunisasi->kelurahan_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ is_array($imunisasi) ? $imunisasi['total_imunisasi'] : $imunisasi->total_imunisasi }}</td>
                        <td class="px-4 py-3">
                            @php
                                $totalImunisasi = is_array($imunisasi) ? $imunisasi['total_imunisasi'] : $imunisasi->total_imunisasi;
                            @endphp
                            @if($totalImunisasi > 0)
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Belum Ada</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Jadwal Posyandu Seluruh Kelurahan --}}
    <x-card title="📅 Jadwal Posyandu Mendatang">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Koordinator</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jadwalPosyandu as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item['posyandu']->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $item['posyandu']->kelurahan->name }}</td>
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
        @if($jadwalPosyandu->count() === 0)
            <p class="text-center text-gray-500 py-4">Tidak ada jadwal posyandu yang terdaftar.</p>
        @endif
    </x-card>

    {{-- Gizi Buruk Alert --}}
    @if($giziBurukBalitas->count() > 0)
    <x-card title="⚠️ Balita Gizi Buruk - Perlu Tindak Lanjut">
        <x-slot:headerAction>
            <span class="text-sm text-gray-500">{{ $giziBurukBalitas->count() }} balita</span>
        </x-slot:headerAction>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
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
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->posyandu->kelurahan->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->age_months }} bulan</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $balita->pertumbuhanRecords->first()->z_score_bbu }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin-kecamatan.balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900 font-medium">Lihat Detail</a>
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
        <x-button href="{{ route('admin-kecamatan.kelurahan.index') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Kelola Kelurahan
        </x-button>
        <x-button href="{{ route('admin-kecamatan.posyandu.index') }}" variant="secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Kelola Posyandu
        </x-button>
        <x-button href="{{ route('admin-kecamatan.users.index') }}" variant="outline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Manage User
        </x-button>
    </div>
</div>
@endsection
