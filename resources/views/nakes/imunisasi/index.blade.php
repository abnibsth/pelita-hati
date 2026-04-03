@extends('layouts.app')

@section('title', 'Data Imunisasi - SiPosyandu')
@section('page-title', 'Data Imunisasi')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Imunisasi</h2>
            <p class="text-sm text-gray-600">Puskesmas {{ $puskesmas->name }}</p>
        </div>
        <a href="{{ route('nakes.imunisasi.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Input Imunisasi Baru
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $totalImunisasi = $records->total();
            $bulanIni = \App\Models\ImunisasiRecord::whereHas('balita.posyandu.kelurahan', function ($q) use ($puskesmas) {
                $q->where('kecamatan_id', $puskesmas->kecamatan_id);
            })->whereMonth('tanggal_diberikan', now()->month)->count();
            $bcg = \App\Models\ImunisasiRecord::whereHas('balita.posyandu.kelurahan', function ($q) use ($puskesmas) {
                $q->where('kecamatan_id', $puskesmas->kecamatan_id);
            })->where('jenis_imunisasi', 'BCG')->count();
            $polio = \App\Models\ImunisasiRecord::whereHas('balita.posyandu.kelurahan', function ($q) use ($puskesmas) {
                $q->where('kecamatan_id', $puskesmas->kecamatan_id);
            })->where('jenis_imunisasi', 'like', 'Polio%')->count();
        @endphp

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Imunisasi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalImunisasi }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Bulan Ini</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $bulanIni }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">BCG</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $bcg }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Polio</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ $polio }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route('nakes.imunisasi.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <select name="jenis_imunisasi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Jenis Imunisasi</option>
                    <option value="BCG" {{ request('jenis_imunisasi') === 'BCG' ? 'selected' : '' }}>BCG</option>
                    <option value="HB-0" {{ request('jenis_imunisasi') === 'HB-0' ? 'selected' : '' }}>Hepatitis B (HB-0)</option>
                    <option value="Polio-1" {{ request('jenis_imunisasi') === 'Polio-1' ? 'selected' : '' }}>Polio 1</option>
                    <option value="Polio-2" {{ request('jenis_imunisasi') === 'Polio-2' ? 'selected' : '' }}>Polio 2</option>
                    <option value="Polio-3" {{ request('jenis_imunisasi') === 'Polio-3' ? 'selected' : '' }}>Polio 3</option>
                    <option value="Polio-4" {{ request('jenis_imunisasi') === 'Polio-4' ? 'selected' : '' }}>Polio 4</option>
                    <option value="DPT-HB-1" {{ request('jenis_imunisasi') === 'DPT-HB-1' ? 'selected' : '' }}>DPT-HB 1</option>
                    <option value="DPT-HB-2" {{ request('jenis_imunisasi') === 'DPT-HB-2' ? 'selected' : '' }}>DPT-HB 2</option>
                    <option value="DPT-HB-3" {{ request('jenis_imunisasi') === 'DPT-HB-3' ? 'selected' : '' }}>DPT-HB 3</option>
                    <option value="Campak" {{ request('jenis_imunisasi') === 'Campak' ? 'selected' : '' }}>Campak</option>
                    <option value="MR" {{ request('jenis_imunisasi') === 'MR' ? 'selected' : '' }}>MR</option>
                </select>
            </div>
            <div>
                <input type="month" name="bulan" value="{{ request('bulan') }}" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Filter</button>
            <a href="{{ route('nakes.imunisasi.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Reset</a>
        </form>
    </div>

    <!-- Imunisasi Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Imunisasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balita</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Imunisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posyandu/Kelurahan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->tanggal_diberikan->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-primary-700 font-semibold text-xs">{{ substr($item->balita->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->balita->name }}</p>
                                    <p class="text-xs text-gray-500">NIK: {{ $item->balita->nik }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ str_replace('_', '-', $item->jenis_imunisasi) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->lokasi }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <p>{{ $item->balita->posyandu->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->balita->posyandu->kelurahan->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('nakes.imunisasi.show', $item) }}" class="text-primary-600 hover:text-primary-900">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                <p>Belum ada data imunisasi</p>
                                <a href="{{ route('nakes.imunisasi.create') }}" class="mt-2 text-primary-600 hover:text-primary-900 text-sm font-medium">Input Imunisasi Pertama →</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
