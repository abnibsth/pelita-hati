@extends('layouts.app')

@section('title', 'Detail Anak - SiPosyandu')
@section('page-title', 'Detail Anak')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-start">
        <a href="{{ route('orangtua.anak.index') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Data Anak
        </a>
    </div>

    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <span class="text-3xl font-bold">{{ substr($balita->name, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $balita->name }}</h2>
                    <p class="text-primary-100">
                        {{ $balita->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} • {{ $balita->age_months }} bulan
                    </p>
                    <p class="text-primary-100 text-sm">NIK: {{ $balita->nik }}</p>
                </div>
            </div>
            <div class="text-right">
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
                <p class="text-primary-100 text-sm mb-1">Status Gizi Terakhir</p>
                @if($lastGrowth)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $lastGrowth->status_gizi)) }}
                    </span>
                    <p class="text-xs text-primary-100 mt-1">{{ $lastGrowth->tanggal->format('d M Y') }}</p>
                @else
                    <span class="text-primary-100 text-sm">Belum ada data</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Detail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informasi Dasar -->
        <x-card title="📋 Informasi Dasar">
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Tanggal Lahir</span>
                    <span class="text-sm font-medium text-gray-900">{{ $balita->birth_date->format('d F Y') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Jenis Kelamin</span>
                    <span class="text-sm font-medium text-gray-900">{{ $balita->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Nama Ibu</span>
                    <span class="text-sm font-medium text-gray-900">{{ $balita->mother_name }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Nama Ayah</span>
                    <span class="text-sm font-medium text-gray-900">{{ $balita->father_name ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-500">No. HP Orangtua</span>
                    <span class="text-sm font-medium text-gray-900">{{ $balita->parent_phone ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Posyandu</span>
                    <span class="text-sm font-medium text-gray-900">{{ $balita->posyandu?->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm text-gray-500">Alamat</span>
                    <span class="text-sm font-medium text-gray-900 text-right">{{ $balita->address ?? '-' }}</span>
                </div>
            </div>
        </x-card>

        <!-- Pengukuran Terakhir -->
        <x-card title="📏 Pengukuran Terakhir">
            @if($lastGrowth)
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Berat Badan</p>
                            <p class="text-2xl font-bold text-green-600">{{ $lastGrowth->berat_badan }} <span class="text-sm font-normal">kg</span></p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Tinggi Badan</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $lastGrowth->tinggi_badan }} <span class="text-sm font-normal">cm</span></p>
                        </div>
                    </div>
                    @if($lastGrowth->lingkar_kepala)
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500 mb-1">Lingkar Kepala</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $lastGrowth->lingkar_kepala }} <span class="text-sm font-normal">cm</span></p>
                    </div>
                    @endif
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-500">Tanggal Pengukuran</span>
                            <span class="text-sm font-medium text-gray-900">{{ $lastGrowth->tanggal->format('d F Y') }}</span>
                        </div>
                        @if($lastGrowth->catatan)
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <p class="text-sm text-gray-500 mb-1">Catatan</p>
                            <p class="text-sm text-gray-700">{{ $lastGrowth->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada data pengukuran</p>
                    <p class="text-sm text-gray-400 mt-2">Silakan kunjungi posyandu untuk melakukan pengukuran</p>
                </div>
            @endif
        </x-card>
    </div>

    <!-- Riwayat -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Riwayat Pertumbuhan -->
        <x-card title="📊 Riwayat Pertumbuhan">
            <x-slot:headerAction>
                <a href="{{ route('orangtua.anak.pertumbuhan.index', $balita) }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">Lihat Semua →</a>
            </x-slot:headerAction>
            @if($balita->pertumbuhanRecords->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">BB</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">TB</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($balita->pertumbuhanRecords->take(5) as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $record->tanggal->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $record->berat_badan }} kg</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $record->tinggi_badan }} cm</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusClass = match($record->status_gizi) {
                                            'normal' => 'bg-green-100 text-green-800',
                                            'kurang' => 'bg-yellow-100 text-yellow-800',
                                            'lebih' => 'bg-blue-100 text-blue-800',
                                            'gizi_buruk' => 'bg-red-100 text-red-800',
                                            'stunting' => 'bg-purple-100 text-purple-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $record->status_gizi)) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-empty-state message="Belum ada riwayat pertumbuhan" />
            @endif
        </x-card>

        <!-- Riwayat Imunisasi -->
        <x-card title="💉 Riwayat Imunisasi">
            <x-slot:headerAction>
                <a href="{{ route('orangtua.anak.imunisasi.index', $balita) }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">Lihat Semua →</a>
            </x-slot:headerAction>
            @if($balita->imunisasiRecords->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($balita->imunisasiRecords->take(5) as $imunisasi)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ str_replace('_', '-', $imunisasi->jenis_imunisasi) }}</p>
                            <p class="text-xs text-gray-500">{{ $imunisasi->tanggal_diberikan->format('d M Y') }}</p>
                        </div>
                        <span class="text-xs text-gray-600 bg-white px-2 py-1 rounded">{{ $imunisasi->lokasi }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <x-empty-state message="Belum ada riwayat imunisasi" />
            @endif
        </x-card>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('orangtua.anak.pertumbuhan.index', $balita) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
            </svg>
            Lihat Pertumbuhan
        </a>
        <a href="{{ route('orangtua.anak.imunisasi.index', $balita) }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
            Lihat Imunisasi
        </a>
    </div>
</div>
@endsection
