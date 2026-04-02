@extends('layouts.app')

@section('title', 'Data Posyandu - SiPosyandu')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    'admin_kelurahan' => 'admin-kelurahan',
    'kader' => 'kader',
    default => '',
};
$posyanduPrefix = "$routePrefix.posyandu";
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Posyandu</h2>
            <p class="mt-1 text-sm text-gray-600">Kelola data posyandu di wilayah Anda.</p>
        </div>
        @if($userRole === 'admin_kota' || $userRole === 'admin_kelurahan')
        <a href="{{ route($posyanduPrefix . '.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Posyandu
        </a>
        @endif
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route($posyanduPrefix . '.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama posyandu..." 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Posyandu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posyandus as $posyandu)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $posyandu->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $posyandu->kelurahan->name }}, {{ $posyandu->kelurahan->kecamatan->name }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Aktif
                    </span>
                </div>

                <div class="mt-4 space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $posyandu->address ?: 'Alamat belum diisi' }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Minggu ke-{{ $posyandu->jadwal_minggu_ke }}, {{ $posyandu->jadwal_hari }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $posyandu->jadwal_jam_mulai }} - {{ $posyandu->jadwal_jam_selesai }}
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100">
                    <a href="{{ route($posyanduPrefix . '.show', $posyandu) }}" class="text-primary-600 hover:text-primary-900 font-medium text-sm">
                        Lihat Detail →
                    </a>
                    @if($userRole === 'admin_kota' || $userRole === 'admin_kelurahan')
                    <a href="{{ route($posyanduPrefix . '.edit', $posyandu) }}" class="text-yellow-600 hover:text-yellow-900 text-sm">
                        Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Posyandu</h3>
                <p class="text-gray-500 mb-4">Silakan tambahkan posyandu baru untuk memulai.</p>
                @if($userRole === 'admin_kota' || $userRole === 'admin_kelurahan')
                <a href="{{ route($posyanduPrefix . '.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Tambah Posyandu
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posyandus->hasPages())
    <div class="mt-6">
        {{ $posyandus->links() }}
    </div>
    @endif
</div>
@endsection
