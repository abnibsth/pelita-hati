@extends('layouts.app')

@section('title', 'Detail Imunisasi - SiPosyandu')
@section('page-title', 'Detail Imunisasi')

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
$imunisasiPrefix = "$routePrefix.imunisasi";
@endphp

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route($imunisasiPrefix . '.index', ['balita' => $record->balita]) }}" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Info Card -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Detail Imunisasi</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ str_replace('_', ' ', $record->jenis_imunisasi) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal Imunisasi</label>
                <p class="mt-1 text-base text-gray-900">{{ $record->tanggal->format('d F Y') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Nama Balita</label>
                <p class="mt-1 text-base text-gray-900">{{ $record->balita->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">NIK Balita</label>
                <p class="mt-1 text-base text-gray-900">{{ $record->balita->nik }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Umur Saat Imunisasi</label>
                <p class="mt-1 text-base text-gray-900">{{ $record->balita->age_months }} bulan</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Keterangan</label>
                <p class="mt-1 text-base text-gray-900">{{ $record->keterangan ?: '-' }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Dicatat Oleh</label>
                <p class="mt-1 text-base text-gray-900">{{ $record->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $record->user->role }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Tanggal Dicatat</label>
                <p class="mt-1 text-base text-gray-900">{{ $record->created_at->format('d F Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <p class="text-sm text-blue-800 font-medium">Informasi Imunisasi</p>
                <p class="text-sm text-blue-700 mt-1">Imunisasi ini telah tercatat dan dapat menjadi referensi untuk jadwal imunisasi selanjutnya.</p>
            </div>
        </div>
    </div>
</div>
@endsection
