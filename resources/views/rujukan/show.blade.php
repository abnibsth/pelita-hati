@extends('layouts.app')

@section('title', 'Detail Rujukan - SiPosyandu')
@section('page-title', 'Detail Rujukan')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Info Balita -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Balita</h3>
            <a href="{{ route('nakes.balita.show', $rujukan->balita) }}" class="text-primary-600 hover:text-primary-900 text-sm font-medium">Lihat Profil Balita →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Nama Balita</p>
                <p class="font-medium text-gray-900">{{ $rujukan->balita->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">NIK</p>
                <p class="font-medium text-gray-900">{{ $rujukan->balita->nik }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Posyandu</p>
                <p class="font-medium text-gray-900">{{ $rujukan->balita->posyandu->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Kelurahan</p>
                <p class="font-medium text-gray-900">{{ $rujukan->balita->posyandu->kelurahan->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Umur</p>
                <p class="font-medium text-gray-900">{{ $rujukan->balita->age_months }} bulan</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Nama Ibu</p>
                <p class="font-medium text-gray-900">{{ $rujukan->balita->mother_name }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Rujukan -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Detail Rujukan</h3>
            @php
                $statusClass = match($rujukan->status) {
                    'dirujuk' => 'bg-yellow-100 text-yellow-800',
                    'diteruskan' => 'bg-blue-100 text-blue-800',
                    'selesai' => 'bg-green-100 text-green-800',
                    default => 'bg-gray-100 text-gray-800',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                {{ ucfirst($rujukan->status) }}
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Tanggal Rujuk</p>
                <p class="font-medium text-gray-900">{{ $rujukan->tanggal_rujuk->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status Gizi</p>
                @php
                    $giziClass = match($rujukan->status_gizi) {
                        'normal' => 'bg-green-100 text-green-800',
                        'kurang' => 'bg-yellow-100 text-yellow-800',
                        'lebih' => 'bg-blue-100 text-blue-800',
                        'gizi_buruk' => 'bg-red-100 text-red-800',
                        'stunting' => 'bg-purple-100 text-purple-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium {{ $giziClass }}">
                    {{ ucfirst(str_replace('_', ' ', $rujukan->status_gizi)) }}
                </span>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Jenis Keluhan</p>
                <p class="font-medium text-gray-900">{{ $rujukan->jenis_keluhan }}</p>
            </div>
            @if($rujukan->tindak_lanjut)
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Tindak Lanjut</p>
                <p class="font-medium text-gray-900">{{ $rujukan->tindak_lanjut }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-500">Nakes yang Merujuk</p>
                <p class="font-medium text-gray-900">{{ $rujukan->nakes->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Puskesmas</p>
                <p class="font-medium text-gray-900">{{ $rujukan->puskesmas->name }}</p>
            </div>
        </div>
    </div>

    <!-- Update Status Form (jika belum selesai) -->
    @if($rujukan->status !== 'selesai')
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status Rujukan</h3>
        <form action="{{ route('nakes.rujukan.update', $rujukan) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status Baru</label>
                <select name="status" id="status" required
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="dirujuk" {{ $rujukan->status === 'dirujuk' ? 'selected' : '' }}>Dirujuk</option>
                    <option value="diteruskan" {{ $rujukan->status === 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                    <option value="selesai" {{ $rujukan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div>
                <label for="tindak_lanjut" class="block text-sm font-medium text-gray-700">Catatan Tindak Lanjut</label>
                <textarea name="tindak_lanjut" id="tindak_lanjut" rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">{{ old('tindak_lanjut', $rujukan->tindak_lanjut) }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <x-button type="submit">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Update Status
                </x-button>
            </div>
        </form>
    </div>
    @endif

    <!-- Back Button -->
    <div class="flex items-center justify-start">
        <a href="{{ route('nakes.rujukan.index') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Rujukan
        </a>
    </div>
</div>
@endsection
