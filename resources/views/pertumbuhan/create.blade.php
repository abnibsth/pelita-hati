@extends('layouts.app')

@section('title', 'Tambah Data Pertumbuhan - SiPosyandu')
@section('page-title', 'Input Data Penimbangan')

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    'admin_kelurahan' => 'admin-kelurahan',
    'nakes_puskesmas' => 'nakes',
    'kader' => 'kader',
    default => '',
};
$balitaPrefix = "$routePrefix.balita";
$pertumbuhanPrefix = "$routePrefix.pertumbuhan";
@endphp

@section('sidebar')
    <a href="{{ route($balitaPrefix . '.show', $balita) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span>Kembali ke Detail Balita</span>
    </a>
@endsection

@section('content')
<x-card>
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                <span class="text-primary-600 font-bold">{{ substr($balita->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="font-semibold">{{ $balita->name }}</p>
                <p class="text-sm text-gray-600">Umur: {{ $balita->age_months }} bulan | {{ $balita->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route($pertumbuhanPrefix . '.store', ['balita' => $balita]) }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tanggal -->
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Penimbangan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" id="tanggal" required max="{{ date('Y-m-d') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('tanggal') border-red-500 @enderror"
                    value="{{ old('tanggal', date('Y-m-d')) }}">
                @error('tanggal')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Berat Badan -->
            <div>
                <label for="berat_badan" class="block text-sm font-medium text-gray-700">Berat Badan (kg) <span class="text-red-500">*</span></label>
                <input type="number" name="berat_badan" id="berat_badan" step="0.01" min="0" max="50" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('berat_badan') border-red-500 @enderror"
                    value="{{ old('berat_badan') }}" placeholder="Contoh: 12.5">
                @error('berat_badan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Tinggi Badan -->
            <div>
                <label for="tinggi_badan" class="block text-sm font-medium text-gray-700">Tinggi Badan (cm) <span class="text-red-500">*</span></label>
                <input type="number" name="tinggi_badan" id="tinggi_badan" step="0.1" min="30" max="150" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('tinggi_badan') border-red-500 @enderror"
                    value="{{ old('tinggi_badan') }}" placeholder="Contoh: 85.5">
                @error('tinggi_badan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Lingkar Kepala -->
            <div>
                <label for="lingkar_kepala" class="block text-sm font-medium text-gray-700">Lingkar Kepala (cm) <span class="text-gray-400">(Opsional)</span></label>
                <input type="number" name="lingkar_kepala" id="lingkar_kepala" step="0.1" min="20" max="80"
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('lingkar_kepala') border-red-500 @enderror"
                    value="{{ old('lingkar_kepala') }}" placeholder="Contoh: 48.0">
                @error('lingkar_kepala')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Lingkar Lengan Atas -->
            <div>
                <label for="lingkar_lengan_atas" class="block text-sm font-medium text-gray-700">Lingkar Lengan Atas (cm) <span class="text-gray-400">(Opsional)</span></label>
                <input type="number" name="lingkar_lengan_atas" id="lingkar_lengan_atas" step="0.1" min="5" max="50"
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('lingkar_lengan_atas') border-red-500 @enderror"
                    value="{{ old('lingkar_lengan_atas') }}" placeholder="Contoh: 14.5">
                @error('lingkar_lengan_atas')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Catatan -->
        <div>
            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea name="catatan" id="catatan" rows="3" 
                class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('catatan') border-red-500 @enderror"
                placeholder="Keluhan, riwayat penyakit, atau catatan lainnya">{{ old('catatan') }}</textarea>
            @error('catatan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Info -->
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>ℹ️ Info:</strong> Status gizi dan Z-Score akan dihitung otomatis berdasarkan standar WHO 2005 setelah data disimpan.
            </p>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route($balitaPrefix . '.show', $balita) }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">Batal</a>
            <x-button type="submit">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Data Penimbangan
            </x-button>
        </div>
    </form>
</x-card>
@endsection
