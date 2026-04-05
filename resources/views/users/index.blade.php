@extends('layouts.app')

@section('title', 'Manage Users - SiPosyandu')

@php
$roleLabels = [
    'admin_kota' => 'Admin Kota',
    'admin_kecamatan' => 'Admin Kecamatan', 
    'admin_kelurahan' => 'Admin Kelurahan',
    'nakes_puskesmas' => 'Nakes Puskesmas',
    'kader' => 'Kader',
    'orangtua' => 'Orangtua',
];
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    'admin_kelurahan' => 'admin-kelurahan',
    default => 'users',
};
$usersPrefix = "$routePrefix.users";
@endphp

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Manage Users</h2>
        <a href="{{ route($usersPrefix . '.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah User
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route($usersPrefix . '.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIK..." 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <select name="role" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Role</option>
                    @foreach($roleLabels as $key => $label)
                        <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-primary-600 font-semibold">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->nik }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($user->role === 'admin_kota') bg-purple-100 text-purple-800
                            @elseif($user->role === 'admin_kecamatan') bg-blue-100 text-blue-800
                            @elseif($user->role === 'admin_kelurahan') bg-indigo-100 text-indigo-800
                            @elseif($user->role === 'nakes_puskesmas') bg-green-100 text-green-800
                            @elseif($user->role === 'kader') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $roleLabels[$user->role] ?? $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($user->kecamatan)
                            {{ $user->kecamatan->name }}
                        @elseif($user->kelurahan)
                            {{ $user->kelurahan->name }}
                        @elseif($user->posyandu)
                            {{ $user->posyandu->name }}
                        @elseif($user->puskesmas)
                            {{ $user->puskesmas->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $user->email }}</div>
                        <div>{{ $user->phone ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            @if($user->role === 'kader')
                            <form action="{{ route($routePrefix . '.users.toggle-status', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}"
                                    onclick="return confirm('Ubah status user menjadi {{ $user->is_active ? 'nonaktif' : 'aktif' }}?')">
                                    {{ $user->is_active ? '✓ Aktif' : '✗ Nonaktif' }}
                                </button>
                            </form>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ?? true ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $user->is_active ?? true ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            @endif
                            <a href="{{ route($usersPrefix . '.show', $user) }}" class="text-primary-600 hover:text-primary-900">Lihat</a>
                            <a href="{{ route($usersPrefix . '.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Tidak ada data user yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
