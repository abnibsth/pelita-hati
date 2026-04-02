@extends('layouts.app')

@section('title', 'Data Kelurahan - SiPosyandu')

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
$kecamatanPrefix = "$routePrefix.kecamatan";
$posyanduPrefix = "$routePrefix.posyandu";
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Kelurahan</h2>
            <p class="mt-1 text-sm text-gray-600">Kelola data kelurahan di wilayah Anda.</p>
        </div>
        @if($userRole === 'admin_kota' || $userRole === 'admin_kecamatan')
        <a href="{{ route($kelurahanPrefix . '.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kelurahan
        </a>
        @endif
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route($kelurahanPrefix . '.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelurahan..." 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            @if(auth()->user()->role === 'admin_kota')
            <select name="kecamatan_id" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Kecamatan</option>
                @foreach(\App\Models\Kecamatan::all() as $kecamatan)
                <option value="{{ $kecamatan->id }}" {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                    {{ $kecamatan->name }}
                </option>
                @endforeach
            </select>
            @endif
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Kelurahan Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelurahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecamatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kelurahans as $kelurahan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $kelurahan->name }}</div>
                            <div class="text-xs text-gray-500">{{ $kelurahan->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $kelurahan->kecamatan->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 max-w-xs truncate">{{ $kelurahan->address ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $kelurahan->phone ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route($kelurahanPrefix . '.show', $kelurahan) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                Detail
                            </a>
                            @if($userRole === 'admin_kota' || $userRole === 'admin_kecamatan')
                            <a href="{{ route($kelurahanPrefix . '.edit', $kelurahan) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                Edit
                            </a>
                            @if($userRole === 'admin_kota')
                            <form action="{{ route($kelurahanPrefix . '.destroy', $kelurahan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelurahan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </form>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Kelurahan</h3>
                            <p class="text-gray-500 mb-4">Silakan tambahkan kelurahan baru untuk memulai.</p>
                            @if($userRole === 'admin_kota' || $userRole === 'admin_kecamatan')
                            <a href="{{ route($kelurahanPrefix . '.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                                Tambah Kelurahan
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($kelurahans->hasPages())
    <div class="mt-6">
        {{ $kelurahans->links() }}
    </div>
    @endif
</div>
@endsection
