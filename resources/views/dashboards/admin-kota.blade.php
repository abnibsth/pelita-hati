@extends('layouts.app')

@section('title', 'Dashboard Admin Kota - SiPosyandu')
@section('page-title', 'Dashboard DKI Jakarta')

@section('sidebar')
    <x-sidebar-navigation />
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
                    <tr><td class="px-4 py-3"><p class="font-medium">{{ $balita->name }}</p><p class="text-xs text-gray-500">{{ $balita->mother_name }}</p></td><td class="px-4 py-3 text-sm">{{ $balita->posyandu->name }}</td><td class="px-4 py-3 text-sm">{{ $balita->posyandu->kelurahan->name }}</td><td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">{{ $balita->pertumbuhanRecords->first()->z_score_bbu }}</span></td><td class="px-4 py-3"><a href="{{ route('admin-kota.balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900">Lihat</a></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif
</div>
@endsection
