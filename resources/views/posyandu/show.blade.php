@extends('layouts.app')

@section('title', $posyandu->name . ' - SiPosyandu')
@section('page-title', 'Detail Posyandu')

@section('sidebar')
<div class="space-y-1">
    <a href="{{ route('posyandu.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span>Kembali</span>
    </a>
    <a href="{{ route('kader.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span>Dashboard</span>
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $posyandu->name }}</h2>
                <p class="text-primary-100 mt-1">{{ $posyandu->address }}</p>
                <p class="text-sm text-primary-100 mt-2">{{ $posyandu->kelurahan->name }}, {{ $posyandu->kelurahan->kecamatan->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-primary-100 text-sm">Jadwal Tetap</p>
                <p class="font-semibold text-lg">Minggu ke-{{ $posyandu->jadwal_minggu_ke }}, {{ $posyandu->jadwal_hari }}</p>
                <p class="text-sm">{{ $posyandu->jadwal_jam_mulai }} - {{ $posyandu->jadwal_jam_selesai }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card title="Total Balita Aktif" :value="$totalBalita" color="primary">
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card title="Total Kader" :value="$totalKader" color="success">
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card title="Kode Posyandu" :value="$posyandu->code" color="info" />
    </div>

    <!-- Kader & Balita -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kader Aktif -->
        <x-card title="Kader Aktif">
            @if($posyandu->users->count() > 0)
            <div class="space-y-3">
                @foreach($posyandu->users as $kader)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-primary-600 font-semibold">{{ substr($kader->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium">{{ $kader->name }}</p>
                            <p class="text-sm text-gray-500">{{ $kader->phone }}</p>
                        </div>
                    </div>
                    @if($kader->id === $posyandu->kader_koordinator_id)
                    <x-badge color="green">Koordinator</x-badge>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <x-empty-state message="Belum ada kader" />
            @endif
        </x-card>

        <!-- Balita Terbaru -->
        <x-card title="Balita Terdaftar ({{ $totalBalita }})">
            @if($posyandu->balitas->count() > 0)
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @foreach($posyandu->balitas->take(10) as $balita)
                <a href="{{ route('balita.show', $balita) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-medium">{{ $balita->name }}</p>
                        <p class="text-sm text-gray-500">{{ $balita->mother_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ $balita->birth_date->diffInMonths(now()) }} bulan</p>
                        <x-badge :color="$balita->gender === 'L' ? 'blue' : 'purple'">{{ $balita->gender }}</x-badge>
                    </div>
                </a>
                @endforeach
            </div>
            @if($posyandu->balitas->count() > 10)
            <p class="text-center text-sm text-gray-500 mt-4">+ {{ $posyandu->balitas->count() - 10 }} balita lainnya</p>
            @endif
            @else
            <x-empty-state message="Belum ada balita terdaftar" />
            @endif
        </x-card>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap gap-4">
        <x-button href="{{ route('balita.create', ['posyandu_id' => $posyandu->id]) }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Balita Baru
        </x-button>
        @can('update', $posyandu)
        <x-button href="{{ route('posyandu.edit', $posyandu) }}" variant="outline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Posyandu
        </x-button>
        @endcan
    </div>
</div>
@endsection
