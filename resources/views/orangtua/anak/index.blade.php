@extends('layouts.app')

@section('title', 'Data Anak - SiPosyandu')
@section('page-title', 'Data Anak Saya')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Anak Saya</h2>
            <p class="text-sm text-gray-600">Kelola data pertumbuhan dan kesehatan anak Anda</p>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route('orangtua.anak.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anak..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Anak Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($balitas as $balita)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <span class="text-lg font-bold">{{ substr($balita->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">{{ $balita->name }}</h3>
                            <p class="text-sm text-primary-100">
                                {{ $balita->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} • {{ $balita->age_months }} bulan
                            </p>
                        </div>
                    </div>
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
                    @if($lastGrowth)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $lastGrowth->status_gizi)) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5l-4-4z"></path>
                        </svg>
                        <span class="font-medium">NIK:</span>
                        <span class="ml-2">{{ $balita->nik }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Ibu:</span>
                        <span class="ml-2">{{ $balita->mother_name }}</span>
                    </div>
                    @if($balita->posyandu)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-medium">Posyandu:</span>
                        <span class="ml-2">{{ $balita->posyandu->name }}</span>
                    </div>
                    @endif
                </div>

                @if($lastGrowth)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500 mb-2">Pengukuran Terakhir ({{ $lastGrowth->tanggal->format('d M Y') }})</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="bg-gray-50 rounded-lg p-2 text-center">
                            <p class="text-xs text-gray-500">Berat</p>
                            <p class="font-semibold text-gray-900">{{ $lastGrowth->berat_badan }} kg</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2 text-center">
                            <p class="text-xs text-gray-500">Tinggi</p>
                            <p class="font-semibold text-gray-900">{{ $lastGrowth->tinggi_badan }} cm</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('orangtua.anak.show', $balita) }}" class="flex-1 text-center px-3 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                        Lihat Detail
                    </a>
                    <a href="{{ route('orangtua.anak.pertumbuhan.index', $balita) }}" class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        Pertumbuhan
                    </a>
                    <a href="{{ route('orangtua.anak.imunisasi.index', $balita) }}" class="flex-1 text-center px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                        Imunisasi
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Anak</h3>
                <p class="text-gray-600 mb-4">Data anak Anda akan ditambahkan oleh kader posyandu saat pertama kali mendaftar.</p>
                <p class="text-sm text-gray-500">Silakan hubungi kader posyandu terdekat untuk mendaftarkan anak Anda.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($balitas->hasPages())
    <div class="mt-6">
        {{ $balitas->links() }}
    </div>
    @endif
</div>
@endsection
