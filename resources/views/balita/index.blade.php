@extends('layouts.app')

@section('title', 'Data Balita - SiPosyandu')
@section('page-title', 'Data Balita')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    @php
    $userRole = auth()->user()->role;
    $routePrefix = match($userRole) {
        'admin_kota' => 'admin-kota',
        'admin_kecamatan' => 'admin-kecamatan',
        'admin_kelurahan' => 'admin-kelurahan',
        'nakes_puskesmas' => 'nakes',
        'kader' => 'kader',
        default => null,
    };
    $balitaPrefix = $routePrefix ? "$routePrefix.balita" : 'balita';
    @endphp

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex-1 max-w-md">
            <form method="GET" action="{{ route($balitaPrefix . '.index') }}" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau nama ibu..." 
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <x-button type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </x-button>
            </form>
        </div>
        @can('create', App\Models\Balita::class)
        <x-button href="{{ route($balitaPrefix . '.create') }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Balita
        </x-button>
        @endcan
    </div>

    <!-- Table -->
    <x-card>
        @if($balitas->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Umur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Kelamin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ibu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($balitas as $balita)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $balita->name }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->nik }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->birth_date->diffInMonths(now()) }} bulan</td>
                        <td class="px-4 py-3">
                            <x-badge :color="$balita->gender === 'L' ? 'blue' : 'purple'">
                                {{ $balita->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </x-badge>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->mother_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $balita->posyandu->name }}</td>
                        <td class="px-4 py-3">
                            <x-badge :color="$balita->status === 'aktif' ? 'green' : 'gray'">
                                {{ ucfirst($balita->status) }}
                            </x-badge>
                        </td>
                        <td class="px-4 py-3 text-sm space-x-2">
                            <a href="{{ route($balitaPrefix . '.show', $balita) }}" class="text-primary-600 hover:text-primary-900">Lihat</a>
                            @can('update', $balita)
                            <a href="{{ route($balitaPrefix . '.edit', $balita) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            @endcan
                            @can('delete', $balita)
                            <form action="{{ route($balitaPrefix . '.destroy', $balita) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $balitas->links() }}
        </div>
        @else
        <x-empty-state message="Belum ada data balita">
            <x-slot:action>
                @can('create', App\Models\Balita::class)
                <x-button href="{{ route($balitaPrefix . '.create') }}">Tambah Balita Baru</x-button>
                @endcan
            </x-slot:action>
        </x-empty-state>
        @endif
    </x-card>
</div>
@endsection
