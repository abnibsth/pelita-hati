@extends('layouts.app')

@section('title', 'Data Balita - SiPosyandu')
@section('page-title', 'Data Balita')

@section('sidebar')
<div class="space-y-1">
    <a href="{{ route('kader.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('balita.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-primary-50 text-primary-700 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span>Data Balita</span>
    </a>
    <a href="{{ route('posyandu.show', auth()->user()->posyandu) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        <span>Posyandu</span>
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex-1 max-w-md">
            <form method="GET" action="{{ route('balita.index') }}" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau nama ibu..." 
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <x-button type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </x-button>
            </form>
        </div>
        @can('create', App\Models\Balita::class)
        <x-button href="{{ route('balita.create') }}">
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
                            <a href="{{ route('balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900">Lihat</a>
                            @can('update', $balita)
                            <a href="{{ route('balita.edit', $balita) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            @endcan
                            @can('delete', $balita)
                            <form action="{{ route('balita.destroy', $balita) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                <x-button href="{{ route('balita.create') }}">Tambah Balita Baru</x-button>
                @endcan
            </x-slot:action>
        </x-empty-state>
        @endif
    </x-card>
</div>
@endsection
