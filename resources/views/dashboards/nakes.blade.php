@extends('layouts.app')

@section('title', 'Dashboard Nakes Puskesmas - SiPosyandu')
@section('page-title', 'Dashboard Puskesmas ' . $puskesmas->name)

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Info Puskesmas -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $puskesmas->name }}</h2>
                <p class="text-blue-100 mt-1">{{ $puskesmas->kecamatan->name }}, DKI Jakarta</p>
                <p class="text-blue-100 text-sm">{{ $puskesmas->address }}</p>
            </div>
            <div class="text-right">
                <p class="text-blue-100 text-sm">Telepon</p>
                <p class="text-lg font-semibold">{{ $puskesmas->phone }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card
            title="Balita Gizi Buruk"
            :value="$giziBurukBalitas->count()"
            color="danger"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Rujukan Aktif"
            :value="$rujukanAktif->count()"
            color="warning"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Total Rujukan"
            :value="$rujukanAktif->where('status', 'diteruskan')->count() + $rujukanAktif->where('status', 'dirujuk')->count()"
            color="info"
        >
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <!-- Balita Gizi Buruk -->
    @if($giziBurukBalitas->count() > 0)
    <x-card title="🚨 Balita Gizi Buruk - Perlu Tindak Lanjut">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Umur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
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
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->posyandu->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->posyandu->kelurahan->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $balita->pertumbuhanRecords->first()->z_score_bbu }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('nakes.balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900 font-medium">Lihat</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @else
    <x-card title="✅ Tidak Ada Balita Gizi Buruk">
        <div class="flex items-center justify-center py-8">
            <div class="text-center">
                <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 font-medium">Tidak ada balita gizi buruk di wilayah Anda</p>
                <p class="text-gray-500 text-sm mt-1">Terus pantau pertumbuhan balita secara rutin</p>
            </div>
        </div>
    </x-card>
    @endif

    <!-- Rujukan Aktif -->
    @if($rujukanAktif->count() > 0)
    <x-card title="📋 Rujukan yang Sedang Diproses">
        <x-slot:headerAction>
            <a href="{{ route('nakes.rujukan.index') }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">Lihat Semua →</a>
        </x-slot:headerAction>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Rujuk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Keluhan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rujukanAktif as $rujukan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $rujukan->balita->name }}</p>
                            <p class="text-xs text-gray-500">{{ $rujukan->balita->mother_name }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $rujukan->tanggal_rujuk->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $rujukan->jenis_keluhan }}</td>
                        <td class="px-4 py-3">
                            @if($rujukan->status === 'dirujuk')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Dirujuk
                                </span>
                            @elseif($rujukan->status === 'diteruskan')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Diteruskan
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('nakes.rujukan.show', $rujukan) }}" class="text-primary-600 hover:text-primary-900 font-medium">Lihat Detail</a>
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
        <x-button href="{{ route('nakes.rujukan.index') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Daftar Rujukan
        </x-button>
        <x-button href="{{ route('nakes.imunisasi.index') }}" variant="secondary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
            Data Imunisasi
        </x-button>
        <x-button href="{{ route('nakes.balita.index') }}" variant="outline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Lihat Semua Balita
        </x-button>
    </div>
</div>
@endsection
