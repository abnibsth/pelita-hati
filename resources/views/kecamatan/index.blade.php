@extends('layouts.app')

@section('title', 'Data Kecamatan - SiPosyandu')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    default => '',
};
$kecamatanPrefix = "$routePrefix.kecamatan";
@endphp

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Kecamatan</h2>
            <p class="mt-1 text-sm text-gray-600">Kelola data kecamatan di wilayah Anda.</p>
        </div>
        @if($userRole === 'admin_kota')
        <a href="{{ route($kecamatanPrefix . '.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kecamatan
        </a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route($kecamatanPrefix . '.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kecamatan..." 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Cari
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kecamatans as $kecamatan)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $kecamatan->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $kecamatan->code }}</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $kecamatan->address ?: 'Alamat belum diisi' }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ $kecamatan->phone ?: '-' }}
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100">
                    <a href="{{ route($kecamatanPrefix . '.show', $kecamatan) }}" class="text-primary-600 hover:text-primary-900 font-medium text-sm">
                        Lihat Detail →
                    </a>
                    @if($userRole === 'admin_kota')
                    <a href="{{ route($kecamatanPrefix . '.edit', $kecamatan) }}" class="text-yellow-600 hover:text-yellow-900 text-sm">
                        Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Kecamatan</h3>
                <p class="text-gray-500 mb-4">Silakan tambahkan kecamatan baru untuk memulai.</p>
                @if($userRole === 'admin_kota')
                <a href="{{ route($kecamatanPrefix . '.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Tambah Kecamatan
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    @if($kecamatans->hasPages())
    <div class="mt-6">
        {{ $kecamatans->links() }}
    </div>
    @endif
</div>
@endsection
