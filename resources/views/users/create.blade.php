@extends('layouts.app')

@section('title', 'Tambah User - SiPosyandu')

@php
$roleLabels = [
    'admin_kota' => 'Admin Kota',
    'admin_kecamatan' => 'Admin Kecamatan', 
    'admin_kelurahan' => 'Admin Kelurahan',
    'nakes_puskesmas' => 'Nakes Puskesmas',
    'kader' => 'Kader',
    'orangtua' => 'Orangtua',
];

$hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
@endphp

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Tambah User Baru</h2>
            <p class="mt-1 text-sm text-gray-600">Isi data lengkap user yang akan didaftarkan.</p>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role User <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                    <label class="relative flex cursor-pointer group">
                        <input type="radio" name="role" value="{{ $role }}" class="peer sr-only" {{ old('role') === $role ? 'checked' : '' }} required>
                        <div class="w-full p-4 bg-white border-2 border-gray-200 rounded-lg peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:bg-gray-50 transition">
                            <div class="font-medium text-gray-900">{{ $roleLabels[$role] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700">NIK <span class="text-red-500">*</span></label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required maxlength="16"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">No. HP</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <!-- Role-specific Fields -->
            <div id="role-specific-fields" class="border-t border-gray-200 pt-6">
                <!-- Admin Kecamatan -->
                <div id="field-kecamatan" class="role-field hidden">
                    <label for="kecamatan_id" class="block text-sm font-medium text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                    <select id="kecamatan_id" name="kecamatan_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}">{{ $kecamatan->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Admin Kelurahan & Kader -->
                <div id="field-kelurahan" class="role-field hidden">
                    <label for="kelurahan_id" class="block text-sm font-medium text-gray-700">Kelurahan <span class="text-red-500">*</span></label>
                    <select id="kelurahan_id" name="kelurahan_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Pilih Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                        <option value="{{ $kelurahan->id }}">{{ $kelurahan->name }} ({{ $kelurahan->kecamatan->name }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kader -->
                <div id="field-posyandu" class="role-field hidden">
                    <label for="posyandu_id" class="block text-sm font-medium text-gray-700">Posyandu <span class="text-red-500">*</span></label>
                    <select id="posyandu_id" name="posyandu_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Pilih Posyandu</option>
                        @foreach($posyandus as $posyandu)
                        <option value="{{ $posyandu->id }}">{{ $posyandu->name }} ({{ $posyandu->kelurahan->name }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nakes -->
                <div id="field-puskesmas" class="role-field hidden">
                    <label for="puskesmas_id" class="block text-sm font-medium text-gray-700">Puskesmas <span class="text-red-500">*</span></label>
                    <select id="puskesmas_id" name="puskesmas_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Pilih Puskesmas</option>
                        @foreach($puskesmas as $puskesmasItem)
                        <option value="{{ $puskesmasItem->id }}">{{ $puskesmasItem->name }} ({{ $puskesmasItem->kecamatan->name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Hide all role-specific fields
            document.querySelectorAll('.role-field').forEach(field => field.classList.add('hidden'));
            
            // Show relevant fields based on role
            const role = this.value;
            if (role === 'admin_kecamatan') {
                document.getElementById('field-kecamatan').classList.remove('hidden');
            } else if (role === 'admin_kelurahan') {
                document.getElementById('field-kelurahan').classList.remove('hidden');
            } else if (role === 'kader') {
                document.getElementById('field-kelurahan').classList.remove('hidden');
                document.getElementById('field-posyandu').classList.remove('hidden');
            } else if (role === 'nakes_puskesmas') {
                document.getElementById('field-puskesmas').classList.remove('hidden');
            }
        });
    });

    // Trigger change on page load if role is selected
    const selectedRole = document.querySelector('input[name="role"]:checked');
    if (selectedRole) {
        selectedRole.dispatchEvent(new Event('change'));
    }
</script>
@endpush
@endsection
