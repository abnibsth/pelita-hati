@extends('layouts.app')

@section('title', $balita->name . ' - SiPosyandu')
@section('page-title', 'Detail Balita')

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    'admin_kelurahan' => 'admin-kelurahan',
    'kader' => 'kader',
    default => '',
};
$balitaPrefix = "$routePrefix.balita";
$pertumbuhanPrefix = "$routePrefix.pertumbuhan";
@endphp

@section('sidebar')
    <a href="{{ route($balitaPrefix . '.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span>Kembali</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-primary-600 text-2xl font-bold">{{ substr($balita->name, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $balita->name }}</h2>
                    <p class="text-gray-600">NIK: {{ $balita->nik }}</p>
                    <p class="text-sm text-gray-500">{{ $balita->posyandu->name }} - {{ $balita->posyandu->kelurahan->name }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                @can('update', $balita)
                <x-button href="{{ route($balitaPrefix . '.edit', $balita) }}" variant="outline" size="sm">Edit</x-button>
                @endcan
                <x-button href="#pertumbuhan" variant="primary" size="sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Penimbangan
                </x-button>
            </div>
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card>
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Lahir</p>
                    <p class="font-semibold">{{ $balita->birth_date->format('d M Y') }}</p>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nama Ibu</p>
                    <p class="font-semibold">{{ $balita->mother_name }}</p>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kontak Orangtua</p>
                    <p class="font-semibold">{{ $balita->parent_phone ?? '-' }}</p>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <x-badge :color="$balita->status === 'aktif' ? 'green' : 'gray'">{{ ucfirst($balita->status) }}</x-badge>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Latest Growth Record -->
    @if($latestGrowth)
    <x-card title="Pemeriksaan Terakhir">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600">Berat Badan</p>
                <p class="text-2xl font-bold text-blue-600">{{ $latestGrowth->berat_badan }} kg</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Tinggi Badan</p>
                <p class="text-2xl font-bold text-green-600">{{ $latestGrowth->tinggi_badan }} cm</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600">Umur Saat Ukur</p>
                <p class="text-lg font-bold text-purple-600">{{ $latestGrowth->umur_saat_ukur }}</p>
            </div>
            <div class="text-center p-4 {{ $latestGrowth->status_gizi === 'normal' ? 'bg-green-50' : 'bg-red-50' }} rounded-lg">
                <p class="text-sm text-gray-600">Status Gizi</p>
                <p class="text-lg font-bold {{ $latestGrowth->status_gizi === 'normal' ? 'text-green-600' : 'text-red-600' }}">{{ strtoupper(str_replace('_', ' ', $latestGrowth->status_gizi)) }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
            <span>Tanggal: {{ $latestGrowth->tanggal->format('d M Y') }}</span>
            <span>Z-Score BB/U: {{ $latestGrowth->z_score_bbu }} | TB/U: {{ $latestGrowth->z_score_tbu }} | BB/TB: {{ $latestGrowth->z_score_bbtb }}</span>
        </div>
    </x-card>
    @endif

    <!-- Growth Chart -->
    <x-card title="Grafik Pertumbuhan" id="pertumbuhan">
        @if($growthTrend['labels']->count() > 0)
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <p class="text-gray-500">Grafik pertumbuhan (memerlukan Chart.js)</p>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-4">
            <div class="text-center">
                <p class="text-sm text-gray-600">Berat Badan (kg)</p>
                <div class="flex justify-center space-x-2 mt-2">
                    @foreach($growthTrend['berat_badan']->take(5) as $bb)
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $bb }}</span>
                    @endforeach
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600">Tinggi Badan (cm)</p>
                <div class="flex justify-center space-x-2 mt-2">
                    @foreach($growthTrend['tinggi_badan']->take(5) as $tb)
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">{{ $tb }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <x-empty-state message="Belum ada data pertumbuhan">
            <x-slot:action>
                <x-button href="{{ route($pertumbuhanPrefix . '.store', ['balita' => $balita]) }}">Tambah Data Penimbangan</x-button>
            </x-slot:action>
        </x-empty-state>
        @endif
    </x-card>

    <!-- Riwayat Pertumbuhan Table -->
    <x-card title="Riwayat Penimbangan">
        @if($balita->pertumbuhanRecords->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">BB (kg)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">TB (cm)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Umur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Gizi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Z-Score</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kader</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($balita->pertumbuhanRecords->take(10) as $record)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $record->tanggal->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $record->berat_badan }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $record->tinggi_badan }}</td>
                        <td class="px-4 py-3 text-sm">{{ $record->umur_saat_ukur }}</td>
                        <td class="px-4 py-3"><x-badge :color="$record->status_gizi === 'normal' ? 'green' : 'red'">{{ $record->status_gizi }}</x-badge></td>
                        <td class="px-4 py-3 text-sm">BB/U: {{ $record->z_score_bbu }} | TB/U: {{ $record->z_score_tbu }}</td>
                        <td class="px-4 py-3 text-sm">{{ $record->kader->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <x-empty-state message="Belum ada riwayat penimbangan" />
        @endif
    </x-card>

    <!-- Imunisasi Section -->
    <x-card title="Riwayat Imunisasi">
        @if($balita->imunisasiRecords->count() > 0)
        <div class="space-y-2">
            @foreach($balita->imunisasiRecords as $imunisasi)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $imunisasi->jenis_imunisasi }}</p>
                    <p class="text-sm text-gray-600">{{ $imunisasi->tanggal_diberikan->format('d M Y') }} - {{ $imunisasi->lokasi }}</p>
                </div>
                <x-badge color="green">Selesai</x-badge>
            </div>
            @endforeach
        </div>
        @else
        <x-empty-state message="Belum ada riwayat imunisasi" />
        @endif
    </x-card>
</div>
@endsection
