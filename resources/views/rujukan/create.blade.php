@extends('layouts.app')

@section('title', 'Buat Rujukan - SiPosyandu')
@section('page-title', 'Buat Rujukan Balita')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('nakes.rujukan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pilih Balita -->
            <div>
                <label for="balita_id" class="block text-sm font-medium text-gray-700">Pilih Balita <span class="text-red-500">*</span></label>
                <select name="balita_id" id="balita_id" required
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('balita_id') border-red-500 @enderror">
                    <option value="">-- Pilih Balita --</option>
                    @foreach($balitas as $balita)
                    <option value="{{ $balita->id }}" {{ old('balita_id') == $balita->id ? 'selected' : '' }}>
                        {{ $balita->name }} ({{ $balita->posyandu->name }} - {{ $balita->posyandu->kelurahan->name }})
                    </option>
                    @endforeach
                </select>
                @error('balita_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Rujuk -->
                <div>
                    <label for="tanggal_rujuk" class="block text-sm font-medium text-gray-700">Tanggal Rujuk <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_rujuk" id="tanggal_rujuk" required max="{{ date('Y-m-d') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('tanggal_rujuk') border-red-500 @enderror"
                        value="{{ old('tanggal_rujuk', date('Y-m-d')) }}">
                    @error('tanggal_rujuk')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Status Gizi -->
                <div>
                    <label for="status_gizi" class="block text-sm font-medium text-gray-700">Status Gizi <span class="text-red-500">*</span></label>
                    <select name="status_gizi" id="status_gizi" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('status_gizi') border-red-500 @enderror">
                        <option value="">Pilih Status Gizi</option>
                        <option value="normal" {{ old('status_gizi') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="kurang" {{ old('status_gizi') === 'kurang' ? 'selected' : '' }}>Kurang</option>
                        <option value="lebih" {{ old('status_gizi') === 'lebih' ? 'selected' : '' }}>Lebih</option>
                        <option value="gizi_buruk" {{ old('status_gizi') === 'gizi_buruk' ? 'selected' : '' }}>Gizi Buruk</option>
                        <option value="stunting" {{ old('status_gizi') === 'stunting' ? 'selected' : '' }}>Stunting</option>
                    </select>
                    @error('status_gizi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Jenis Keluhan -->
            <div>
                <label for="jenis_keluhan" class="block text-sm font-medium text-gray-700">Jenis Keluhan <span class="text-red-500">*</span></label>
                <textarea name="jenis_keluhan" id="jenis_keluhan" rows="3" required
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('jenis_keluhan') border-red-500 @enderror"
                    placeholder="Deskripsikan keluhan atau alasan rujukan">{{ old('jenis_keluhan') }}</textarea>
                @error('jenis_keluhan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Tindak Lanjut -->
            <div>
                <label for="tindak_lanjut" class="block text-sm font-medium text-gray-700">Rencana Tindak Lanjut</label>
                <textarea name="tindak_lanjut" id="tindak_lanjut" rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('tindak_lanjut') border-red-500 @enderror"
                    placeholder="Rencana tindak lanjut yang disarankan">{{ old('tindak_lanjut') }}</textarea>
                @error('tindak_lanjut')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status Rujukan <span class="text-red-500">*</span></label>
                <select name="status" id="status" required
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('status') border-red-500 @enderror">
                    <option value="dirujuk" {{ old('status') === 'dirujuk' ? 'selected' : '' }}>Dirujuk</option>
                    <option value="diteruskan" {{ old('status') === 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Info -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>ℹ️ Info:</strong> Rujukan akan tercatat dan dapat dipantau statusnya hingga selesai.
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('nakes.rujukan.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">Batal</a>
                <x-button type="submit">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Buat Rujukan
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection
