@extends('layouts.app')

@section('title', 'Edit Kelurahan - SiPosyandu')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    default => '',
};
$kelurahanPrefix = "$routePrefix.kelurahan";
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ route($kelurahanPrefix . '.index') }}" class="hover:text-gray-700">Kelurahan</a>
        <span>/</span>
        <a href="{{ route($kelurahanPrefix . '.show', $kelurahan) }}" class="hover:text-gray-700">{{ $kelurahan->name }}</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Edit</span>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
        <form action="{{ route($kelurahanPrefix . '.update', $kelurahan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kelurahan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $kelurahan->name) }}" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Kode Kelurahan <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $kelurahan->code) }}" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('code') border-red-500 @enderror">
                    @error('code')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kecamatan_id" class="block text-sm font-medium text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('kecamatan_id') border-red-500 @enderror">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $kelurahan->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                            {{ $kecamatan->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('kecamatan_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('address') border-red-500 @enderror">{{ old('address', $kelurahan->address) }}</textarea>
                    @error('address')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $kelurahan->phone) }}"
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route($kelurahanPrefix . '.show', $kelurahan) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
