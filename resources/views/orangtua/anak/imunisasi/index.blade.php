@extends('layouts.app')

@section('title', 'Riwayat Imunisasi - SiPosyandu')
@section('page-title', 'Riwayat Imunisasi')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-start">
        <a href="{{ route('orangtua.anak.show', $balita) }}" class="inline-flex items-center text-gray-700 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Detail {{ $balita->name }}
        </a>
    </div>

    <!-- Info Balita Card -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ substr($balita->name, 0, 1) }}</span>
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
                <p class="text-primary-100 text-sm">Total Imunisasi</p>
                <p class="text-3xl font-bold">{{ $records->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="text-sm font-semibold text-green-900">✓ Imunisasi Lengkap Penting untuk Kesehatan Anak</h4>
                <p class="text-sm text-green-800 mt-1">
                    Pastikan anak Anda mendapatkan semua imunisasi dasar lengkap sesuai jadwal yang direkomendasikan.
                </p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Imunisasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Imunisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $record->tanggal_diberikan->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ str_replace('_', '-', $record->jenis_imunisasi) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                @if($record->lokasi === 'Posyandu') bg-green-100 text-green-800
                                @elseif($record->lokasi === 'Puskesmas') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $record->lokasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $record->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                <p class="font-medium text-gray-900">Belum ada riwayat imunisasi</p>
                                <p class="text-sm mt-2">Silakan kunjungi posyandu atau puskesmas untuk mendapatkan imunisasi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Jadwal Imunisasi -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📅 Jadwal Imunisasi Dasar Lengkap</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usia</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Imunisasi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pencegahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">0 Bulan</td>
                        <td class="px-4 py-3"><span class="font-medium text-blue-600">BCG</span></td>
                        <td class="px-4 py-3 text-gray-600">Tuberculosis</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">0 Bulan</td>
                        <td class="px-4 py-3"><span class="font-medium text-blue-600">Hepatitis B (HB-0)</span></td>
                        <td class="px-4 py-3 text-gray-600">Hepatitis B</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">2, 3, 4 Bulan</td>
                        <td class="px-4 py-3"><span class="font-medium text-blue-600">Polio 1-4</span></td>
                        <td class="px-4 py-3 text-gray-600">Poliomyelitis</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">2, 3, 4 Bulan</td>
                        <td class="px-4 py-3"><span class="font-medium text-blue-600">DPT-HB-Hib 1-3</span></td>
                        <td class="px-4 py-3 text-gray-600">Difteri, Pertusis, Tetanus, Hepatitis B, Hib</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">9 Bulan</td>
                        <td class="px-4 py-3"><span class="font-medium text-blue-600">Campak / MR</span></td>
                        <td class="px-4 py-3 text-gray-600">Campak / Measles Rubella</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
