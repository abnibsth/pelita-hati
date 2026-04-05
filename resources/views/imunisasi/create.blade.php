@extends('layouts.app')

@section('title', 'Tambah Data Imunisasi - SiPosyandu')
@section('page-title', 'Input Data Imunisasi')

@php
$userRole = auth()->user()->role;
$routePrefix = match($userRole) {
    'admin_kota' => 'admin-kota',
    'admin_kecamatan' => 'admin-kecamatan',
    'admin_kelurahan' => 'admin-kelurahan',
    'kader' => 'kader',
    'nakes_puskesmas' => 'nakes',
    default => '',
};
$balitaPrefix = "$routePrefix.balita";
$imunisasiPrefix = "$routePrefix.imunisasi";
@endphp

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-primary-600 font-bold">{{ substr($balita->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-semibold">{{ $balita->name }}</p>
                    <p class="text-sm text-gray-600">NIK: {{ $balita->nik }} | Umur: {{ $balita->age_months }} bulan</p>
                </div>
            </div>
        </div>

        <form action="{{ route($imunisasiPrefix . '.store', ['balita' => $balita]) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_diberikan" class="block text-sm font-medium text-gray-700">Tanggal Imunisasi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_diberikan" id="tanggal_diberikan" required max="{{ date('Y-m-d') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('tanggal_diberikan') border-red-500 @enderror"
                        value="{{ old('tanggal_diberikan', date('Y-m-d')) }}">
                    @error('tanggal_diberikan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jenis_imunisasi" class="block text-sm font-medium text-gray-700">Jenis Imunisasi <span class="text-red-500">*</span></label>
                    <select name="jenis_imunisasi" id="jenis_imunisasi" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('jenis_imunisasi') border-red-500 @enderror">
                        <option value="">Pilih Imunisasi</option>
                        <option value="BCG" {{ old('jenis_imunisasi') === 'BCG' ? 'selected' : '' }}>BCG (Tuberculosis)</option>
                        <option value="Hepatitis_B" {{ old('jenis_imunisasi') === 'Hepatitis_B' ? 'selected' : '' }}>Hepatitis B</option>
                        <option value="Polio_1" {{ old('jenis_imunisasi') === 'Polio_1' ? 'selected' : '' }}>Polio 1</option>
                        <option value="DPT_HB_Hib_1" {{ old('jenis_imunisasi') === 'DPT_HB_Hib_1' ? 'selected' : '' }}>DPT-HB-Hib 1</option>
                        <option value="Polio_2" {{ old('jenis_imunisasi') === 'Polio_2' ? 'selected' : '' }}>Polio 2</option>
                        <option value="DPT_HB_Hib_2" {{ old('jenis_imunisasi') === 'DPT_HB_Hib_2' ? 'selected' : '' }}>DPT-HB-Hib 2</option>
                        <option value="Polio_3" {{ old('jenis_imunisasi') === 'Polio_3' ? 'selected' : '' }}>Polio 3</option>
                        <option value="DPT_HB_Hib_3" {{ old('jenis_imunisasi') === 'DPT_HB_Hib_3' ? 'selected' : '' }}>DPT-HB-Hib 3</option>
                        <option value="Polio_4" {{ old('jenis_imunisasi') === 'Polio_4' ? 'selected' : '' }}>Polio 4</option>
                        <option value="Campak" {{ old('jenis_imunisasi') === 'Campak' ? 'selected' : '' }}>Campak</option>
                        <option value="MR" {{ old('jenis_imunisasi') === 'MR' ? 'selected' : '' }}>MR (Measles Rubella)</option>
                        <option value="JE" {{ old('jenis_imunisasi') === 'JE' ? 'selected' : '' }}>JE (Japanese Encephalitis)</option>
                        <option value="PCV" {{ old('jenis_imunisasi') === 'PCV' ? 'selected' : '' }}>PCV (Pneumococcal)</option>
                        <option value="Rotavirus" {{ old('jenis_imunisasi') === 'Rotavirus' ? 'selected' : '' }}>Rotavirus</option>
                        <option value="Varicella" {{ old('jenis_imunisasi') === 'Varicella' ? 'selected' : '' }}>Varicella (Cacar Air)</option>
                        <option value="Influenza" {{ old('jenis_imunisasi') === 'Influenza' ? 'selected' : '' }}>Influenza</option>
                    </select>
                    @error('jenis_imunisasi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('keterangan') border-red-500 @enderror"
                    placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                @error('keterangan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>✓</strong> Imunisasi akan tercatat dan dapat dilihat pada riwayat imunisasi balita.
                </p>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route($balitaPrefix . '.show', $balita) }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Simpan Data Imunisasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
