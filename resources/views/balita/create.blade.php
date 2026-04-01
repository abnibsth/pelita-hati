@extends('layouts.app')

@section('title', 'Tambah Balita - SiPosyandu')
@section('page-title', 'Tambah Balita Baru')

@section('sidebar')
<div class="space-y-1">
    <a href="{{ route('balita.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span>Kembali</span>
    </a>
</div>
@endsection

@section('content')
<x-card>
    <form action="{{ route('balita.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- NIK -->
            <div>
                <label for="nik" class="block text-sm font-medium text-gray-700">NIK Balita <span class="text-red-500">*</span></label>
                <input type="text" name="nik" id="nik" maxlength="16" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('nik') border-red-500 @enderror"
                    value="{{ old('nik') }}" placeholder="16 digit NIK">
                @error('nik')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" name="birth_date" id="birth_date" required max="{{ date('Y-m-d') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('birth_date') border-red-500 @enderror"
                    value="{{ old('birth_date') }}">
                @error('birth_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="gender" id="gender" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('gender') border-red-500 @enderror">
                    <option value="">Pilih</option>
                    <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Nama Ibu -->
            <div>
                <label for="mother_name" class="block text-sm font-medium text-gray-700">Nama Ibu Kandung <span class="text-red-500">*</span></label>
                <input type="text" name="mother_name" id="mother_name" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('mother_name') border-red-500 @enderror"
                    value="{{ old('mother_name') }}">
                @error('mother_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- NIK Ibu -->
            <div>
                <label for="mother_nik" class="block text-sm font-medium text-gray-700">NIK Ibu <span class="text-red-500">*</span></label>
                <input type="text" name="mother_nik" id="mother_nik" maxlength="16" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('mother_nik') border-red-500 @enderror"
                    value="{{ old('mother_nik') }}" placeholder="16 digit NIK">
                @error('mother_nik')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Nama Ayah -->
            <div>
                <label for="father_name" class="block text-sm font-medium text-gray-700">Nama Ayah Kandung</label>
                <input type="text" name="father_name" id="father_name" 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('father_name') border-red-500 @enderror"
                    value="{{ old('father_name') }}">
                @error('father_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- No HP Orangtua -->
            <div>
                <label for="parent_phone" class="block text-sm font-medium text-gray-700">No. HP Orangtua</label>
                <input type="text" name="parent_phone" id="parent_phone" 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('parent_phone') border-red-500 @enderror"
                    value="{{ old('parent_phone') }}" placeholder="08xxxxxxxxxx">
                @error('parent_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- RT/RW -->
            <div>
                <label for="rt_rw" class="block text-sm font-medium text-gray-700">RT/RW</label>
                <input type="text" name="rt_rw" id="rt_rw" 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('rt_rw') border-red-500 @enderror"
                    value="{{ old('rt_rw') }}" placeholder="001/002">
                @error('rt_rw')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Posyandu -->
            <div>
                <label for="posyandu_id" class="block text-sm font-medium text-gray-700">Posyandu <span class="text-red-500">*</span></label>
                <select name="posyandu_id" id="posyandu_id" required 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('posyandu_id') border-red-500 @enderror">
                    <option value="">Pilih Posyandu</option>
                    @foreach($posyandus as $posyandu)
                    <option value="{{ $posyandu->id }}" {{ old('posyandu_id') == $posyandu->id ? 'selected' : '' }}>
                        {{ $posyandu->name }}
                    </option>
                    @endforeach
                </select>
                @error('posyandu_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- User ID (Orangtua) -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700">Akun Orangtua (Opsional)</label>
                <select name="user_id" id="user_id" 
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('user_id') border-red-500 @enderror">
                    <option value="">Tidak ada</option>
                    <!-- Bisa diisi dengan list orangtua jika ada -->
                </select>
                @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Alamat -->
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
            <textarea name="address" id="address" rows="3" 
                class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('balita.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">Batal</a>
            <x-button type="submit">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Data Balita
            </x-button>
        </div>
    </form>
</x-card>
@endsection
