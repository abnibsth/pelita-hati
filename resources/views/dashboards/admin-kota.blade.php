@extends('layouts.app')

@section('title', 'Dashboard Admin Kota - SiPosyandu')
@section('page-title', 'Dashboard DKI Jakarta')

@section('sidebar')
<div class="space-y-1">
    <a href="{{ route('admin-kota.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-primary-50 text-primary-700 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <span>Manage User</span>
    </a>
    <a href="{{ route('balita.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span>Data Balita</span>
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card title="Kecamatan" :value="$totalKecamatan" color="primary" />
        <x-stat-card title="Kelurahan" :value="$totalKelurahan" color="info" />
        <x-stat-card title="Posyandu" :value="$totalPosyandu" color="success" />
        <x-stat-card title="Total Balita" :value="$totalBalita" color="warning" />
    </div>

    <x-card title="Distribusi Status Gizi - DKI Jakarta">
        <div class="grid grid-cols-5 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg"><p class="text-3xl font-bold text-green-600">{{ $statusGizi['normal'] }}</p><p class="text-xs text-gray-600 mt-1">Normal</p></div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg"><p class="text-3xl font-bold text-yellow-600">{{ $statusGizi['kurang'] }}</p><p class="text-xs text-gray-600 mt-1">Kurang</p></div>
            <div class="text-center p-4 bg-blue-50 rounded-lg"><p class="text-3xl font-bold text-blue-600">{{ $statusGizi['lebih'] }}</p><p class="text-xs text-gray-600 mt-1">Lebih</p></div>
            <div class="text-center p-4 bg-red-50 rounded-lg"><p class="text-3xl font-bold text-red-600">{{ $statusGizi['gizi_buruk'] }}</p><p class="text-xs text-gray-600 mt-1">Gizi Buruk</p></div>
            <div class="text-center p-4 bg-purple-50 rounded-lg"><p class="text-3xl font-bold text-purple-600">{{ $statusGizi['stunting'] }}</p><p class="text-xs text-gray-600 mt-1">Stunting</p></div>
        </div>
    </x-card>

    @if($giziBurukBalitas->count() > 0)
    <x-card title="⚠️ Balita Gizi Buruk - Seluruh Jakarta">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Z-Score</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($giziBurukBalitas as $balita)
                    <tr><td class="px-4 py-3"><p class="font-medium">{{ $balita->name }}</p><p class="text-xs text-gray-500">{{ $balita->mother_name }}</p></td><td class="px-4 py-3 text-sm">{{ $balita->posyandu->name }}</td><td class="px-4 py-3 text-sm">{{ $balita->posyandu->kelurahan->name }}</td><td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">{{ $balita->pertumbuhanRecords->first()->z_score_bbu }}</span></td><td class="px-4 py-3"><a href="{{ route('balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900">Lihat</a></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif
</div>
@endsection
