@extends('layouts.app')

@section('title', 'Edit Posyandu - SiPosyandu')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    'admin_kelurahan' => 'admin-kelurahan',
    default => '',
};
$posyanduPrefix = "$routePrefix.posyandu";
@endphp

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ route($posyanduPrefix . '.index') }}" class="hover:text-gray-700">Posyandu</a>
        <span>/</span>
        <a href="{{ route($posyanduPrefix . '.show', $posyandu) }}" class="hover:text-gray-700">{{ $posyandu->name }}</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Edit</span>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
        <form action="{{ route($posyanduPrefix . '.update', $posyandu) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Posyandu <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $posyandu->name) }}" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Kode Posyandu <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $posyandu->code) }}" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('code') border-red-500 @enderror">
                    @error('code')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kelurahan_id" class="block text-sm font-medium text-gray-700">Kelurahan <span class="text-red-500">*</span></label>
                    <select name="kelurahan_id" id="kelurahan_id" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('kelurahan_id') border-red-500 @enderror">
                        <option value="">Pilih Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                        <option value="{{ $kelurahan->id }}" {{ old('kelurahan_id', $posyandu->kelurahan_id) == $kelurahan->id ? 'selected' : '' }}>
                            {{ $kelurahan->name }} - {{ $kelurahan->kecamatan->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('kelurahan_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('address') border-red-500 @enderror">{{ old('address', $posyandu->address) }}</textarea>
                    @error('address')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jadwal_minggu_ke" class="block text-sm font-medium text-gray-700">Minggu Ke- <span class="text-red-500">*</span></label>
                        <select name="jadwal_minggu_ke" id="jadwal_minggu_ke" required
                            class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('jadwal_minggu_ke') border-red-500 @enderror">
                            <option value="">Pilih</option>
                            <option value="1" {{ old('jadwal_minggu_ke', $posyandu->jadwal_minggu_ke) == '1' ? 'selected' : '' }}>1</option>
                            <option value="2" {{ old('jadwal_minggu_ke', $posyandu->jadwal_minggu_ke) == '2' ? 'selected' : '' }}>2</option>
                            <option value="3" {{ old('jadwal_minggu_ke', $posyandu->jadwal_minggu_ke) == '3' ? 'selected' : '' }}>3</option>
                            <option value="4" {{ old('jadwal_minggu_ke', $posyandu->jadwal_minggu_ke) == '4' ? 'selected' : '' }}>4</option>
                        </select>
                        @error('jadwal_minggu_ke')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jadwal_hari" class="block text-sm font-medium text-gray-700">Hari <span class="text-red-500">*</span></label>
                        <select name="jadwal_hari" id="jadwal_hari" required
                            class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('jadwal_hari') border-red-500 @enderror">
                            <option value="">Pilih</option>
                            <option value="Senin" {{ old('jadwal_hari', $posyandu->jadwal_hari) == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ old('jadwal_hari', $posyandu->jadwal_hari) == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ old('jadwal_hari', $posyandu->jadwal_hari) == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ old('jadwal_hari', $posyandu->jadwal_hari) == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ old('jadwal_hari', $posyandu->jadwal_hari) == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ old('jadwal_hari', $posyandu->jadwal_hari) == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ old('jadwal_hari', $posyandu->jadwal_hari) == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                        </select>
                        @error('jadwal_hari')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jadwal_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="jadwal_jam_mulai" id="jadwal_jam_mulai" value="{{ old('jadwal_jam_mulai', $posyandu->jadwal_jam_mulai) }}" required
                            class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('jadwal_jam_mulai') border-red-500 @enderror">
                        @error('jadwal_jam_mulai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jadwal_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="jadwal_jam_selesai" id="jadwal_jam_selesai" value="{{ old('jadwal_jam_selesai', $posyandu->jadwal_jam_selesai) }}" required
                            class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('jadwal_jam_selesai') border-red-500 @enderror">
                        @error('jadwal_jam_selesai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="kader_koordinator_id" class="block text-sm font-medium text-gray-700">Kader Koordinator</label>
                    <select name="kader_koordinator_id" id="kader_koordinator_id"
                        class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('kader_koordinator_id') border-red-500 @enderror">
                        <option value="">Pilih Koordinator</option>
                        @foreach($kaders as $kader)
                        <option value="{{ $kader->id }}" {{ old('kader_koordinator_id', $posyandu->kader_koordinator_id) == $kader->id ? 'selected' : '' }}>
                            {{ $kader->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('kader_koordinator_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route($posyanduPrefix . '.show', $posyandu) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
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
