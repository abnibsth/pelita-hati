@extends('layouts.app')

@section('title', 'Detail Kelurahan - SiPosyandu')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    'admin_kelurahan' => 'admin-kelurahan',
    default => '',
};
$kelurahanPrefix = "$routePrefix.kelurahan";
$posyanduPrefix = "$routePrefix.posyandu";
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-1">
                <a href="{{ route($kelurahanPrefix . '.index') }}" class="hover:text-gray-700">Kelurahan</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $kelurahan->name }}</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Detail Kelurahan</h2>
        </div>
        <div class="flex items-center space-x-3">
            @if($userRole === 'admin_kota' || $userRole === 'admin_kecamatan')
            <a href="{{ route($kelurahanPrefix . '.edit', $kelurahan) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            @endif
            <a href="{{ route($kelurahanPrefix . '.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Posyandu</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalPosyandu }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Balita</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalBalita }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Kader</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalKader }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kelurahan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Nama Kelurahan</label>
                <p class="mt-1 text-base text-gray-900">{{ $kelurahan->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Kode Kelurahan</label>
                <p class="mt-1 text-base text-gray-900">{{ $kelurahan->code }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Kecamatan</label>
                <p class="mt-1 text-base text-gray-900">{{ $kelurahan->kecamatan->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Telepon</label>
                <p class="mt-1 text-base text-gray-900">{{ $kelurahan->phone ?: '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Alamat</label>
                <p class="mt-1 text-base text-gray-900">{{ $kelurahan->address ?: '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Posyandu List -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Posyandu di Wilayah {{ $kelurahan->name }}</h3>
            @if($userRole === 'admin_kota' || $userRole === 'admin_kelurahan')
            <a href="{{ route($posyanduPrefix . '.create') }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">
                + Tambah Posyandu
            </a>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kelurahan->posyandus as $posyandu)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <h4 class="font-semibold text-gray-900">{{ $posyandu->name }}</h4>
                <p class="text-sm text-gray-500 mt-1">{{ $posyandu->address ?: 'Alamat belum diisi' }}</p>
                <div class="mt-3 flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Minggu ke-{{ $posyandu->jadwal_minggu_ke }}, {{ $posyandu->jadwal_hari }}
                </div>
                <div class="mt-4">
                    <a href="{{ route($posyanduPrefix . '.show', $posyandu) }}" class="text-primary-600 hover:text-primary-900 text-sm font-medium">
                        Lihat Detail →
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <p class="text-gray-500">Belum ada posyandu di kelurahan ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
