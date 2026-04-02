@extends('layouts.app')

@section('title', 'Edit User - SiPosyandu')

@php
$roleLabels = [
    'admin_kota' => 'Admin Kota',
    'admin_kecamatan' => 'Admin Kecamatan', 
    'admin_kelurahan' => 'Admin Kelurahan',
    'nakes_puskesmas' => 'Nakes Puskesmas',
    'kader' => 'Kader',
    'orangtua' => 'Orangtua',
];
@endphp

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Edit User</h2>
                <p class="mt-1 text-sm text-gray-600">Perbarui data user {{ $user->name }}.</p>
            </div>
            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                    Hapus User
                </button>
            </form>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Role (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role User</label>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="font-medium text-gray-900">{{ $roleLabels[$user->role] ?? $user->role }}</span>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700">NIK <span class="text-red-500">*</span></label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik', $user->nik) }}" required maxlength="16"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">No. HP</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password (Optional) -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Ganti Password (Kosongkan jika tidak ingin mengubah)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" id="password" name="password"
                            class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
            </div>

            <!-- Role-specific Fields -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Wilayah</h3>
                
                @if($user->role === 'admin_kecamatan' && $kecamatans->count() > 0)
                <div>
                    <label for="kecamatan_id" class="block text-sm font-medium text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                    <select id="kecamatan_id" name="kecamatan_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $user->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>{{ $kecamatan->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(in_array($user->role, ['admin_kelurahan', 'kader']) && $kelurahans->count() > 0)
                <div>
                    <label for="kelurahan_id" class="block text-sm font-medium text-gray-700">Kelurahan <span class="text-red-500">*</span></label>
                    <select id="kelurahan_id" name="kelurahan_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @foreach($kelurahans as $kelurahan)
                        <option value="{{ $kelurahan->id }}" {{ old('kelurahan_id', $user->kelurahan_id) == $kelurahan->id ? 'selected' : '' }}>{{ $kelurahan->name }} ({{ $kelurahan->kecamatan->name }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if($user->role === 'kader' && $posyandus->count() > 0)
                <div class="mt-4">
                    <label for="posyandu_id" class="block text-sm font-medium text-gray-700">Posyandu <span class="text-red-500">*</span></label>
                    <select id="posyandu_id" name="posyandu_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @foreach($posyandus as $posyandu)
                        <option value="{{ $posyandu->id }}" {{ old('posyandu_id', $user->posyandu_id) == $posyandu->id ? 'selected' : '' }}>{{ $posyandu->name }} ({{ $posyandu->kelurahan->name }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if($user->role === 'nakes_puskesmas' && $puskesmas->count() > 0)
                <div>
                    <label for="puskesmas_id" class="block text-sm font-medium text-gray-700">Puskesmas <span class="text-red-500">*</span></label>
                    <select id="puskesmas_id" name="puskesmas_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @foreach($puskesmas as $puskesmasItem)
                        <option value="{{ $puskesmasItem->id }}" {{ old('puskesmas_id', $user->puskesmas_id) == $puskesmasItem->id ? 'selected' : '' }}>{{ $puskesmasItem->name }} ({{ $puskesmasItem->kecamatan->name }})</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
