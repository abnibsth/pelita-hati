@extends('layouts.app')

@section('title', 'Detail User - SiPosyandu')

@php
$roleLabels = [
    'admin_kota' => 'Admin Kota',
    'admin_kecamatan' => 'Admin Kecamatan', 
    'admin_kelurahan' => 'Admin Kelurahan',
    'nakes_puskesmas' => 'Nakes Puskesmas',
    'kader' => 'Kader',
    'orangtua' => 'Orangtua',
];

$roleColors = [
    'admin_kota' => 'bg-purple-100 text-purple-800',
    'admin_kecamatan' => 'bg-blue-100 text-blue-800',
    'admin_kelurahan' => 'bg-indigo-100 text-indigo-800',
    'nakes_puskesmas' => 'bg-green-100 text-green-800',
    'kader' => 'bg-yellow-100 text-yellow-800',
    'orangtua' => 'bg-gray-100 text-gray-800',
];
@endphp

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar User
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Detail User</h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Profile Header -->
            <div class="flex items-center mb-8">
                <div class="flex-shrink-0 h-20 w-20 rounded-full bg-primary-100 flex items-center justify-center">
                    <span class="text-primary-600 font-bold text-2xl">{{ substr($user->name, 0, 2) }}</span>
                </div>
                <div class="ml-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-2 {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $roleLabels[$user->role] ?? $user->role }}
                    </span>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Informasi Kontak</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="text-gray-900">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-gray-900">{{ $user->phone ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3 3 0 00-3 3m0 0v.01"></path></svg>
                            <span class="text-gray-900 font-mono">{{ $user->nik }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Informasi Akun</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dibuat pada</span>
                            <span class="text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Terakhir diupdate</span>
                            <span class="text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wilayah -->
            @if($user->kecamatan || $user->kelurahan || $user->posyandu || $user->puskesmas)
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-500 mb-3">Wilayah</h4>
                <div class="flex items-center flex-wrap gap-2">
                    @if($user->kecamatan)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-100 text-blue-800 text-sm">
                            Kecamatan: {{ $user->kecamatan->name }}
                        </span>
                    @endif
                    @if($user->kelurahan)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-100 text-indigo-800 text-sm">
                            Kelurahan: {{ $user->kelurahan->name }}
                        </span>
                    @endif
                    @if($user->posyandu)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-green-100 text-green-800 text-sm">
                            Posyandu: {{ $user->posyandu->name }}
                        </span>
                    @endif
                    @if($user->puskesmas)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-purple-100 text-purple-800 text-sm">
                            Puskesmas: {{ $user->puskesmas->name }}
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
