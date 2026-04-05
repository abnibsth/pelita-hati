@extends('layouts.app')

@section('title', 'Dashboard Admin Kota - SiPosyandu')
@section('page-title', 'Dashboard DKI Jakarta')

@section('sidebar')
    <x-sidebar-navigation />
@endsection

@section('content')
<div class="space-y-6">
    {{-- Statistik Keseluruhan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <x-stat-card title="Kecamatan" :value="$totalKecamatan" color="primary" />
        <x-stat-card title="Kelurahan" :value="$totalKelurahan" color="info" />
        <x-stat-card title="Posyandu" :value="$totalPosyandu" color="success" />
        <x-stat-card title="Balita" :value="$totalBalita" color="warning" />
        <x-stat-card title="Kader" :value="$totalKader" color="purple" />
        <x-stat-card title="Nakes" :value="$totalNakes" color="danger" />
    </div>

    {{-- Alert Kecamatan dengan Cakupan Rendah --}}
    @if($lowCoverageKecamatan->count() > 0)
    <x-alert type="error" title="⚠️ Peringatan Dini - Stunting Rate Tinggi">
        <p class="text-sm text-gray-600 mb-4">
            Terdapat {{ $lowCoverageKecamatan->count() }} kecamatan dengan stunting rate di atas 20%. Diperlukan tindakan segera.
        </p>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-red-800 uppercase">Kecamatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-red-800 uppercase">Total Balita</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-red-800 uppercase">Stunting</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-red-800 uppercase">Stunting Rate</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($lowCoverageKecamatan as $kec)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $kec['name'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $kec['total_balita'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $kec['stunting_count'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                {{ number_format($kec['stunting_rate'], 1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-alert>
    @endif

    {{-- Status Gizi --}}
    <x-card title="📊 Distribusi Status Gizi - DKI Jakarta">
        <div class="grid grid-cols-5 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-3xl font-bold text-green-600">{{ $statusGizi['normal'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Normal</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-3xl font-bold text-yellow-600">{{ $statusGizi['kurang'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Kurang</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-3xl font-bold text-blue-600">{{ $statusGizi['lebih'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Lebih</p>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <p class="text-3xl font-bold text-red-600">{{ $statusGizi['gizi_buruk'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Gizi Buruk</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-3xl font-bold text-purple-600">{{ $statusGizi['stunting'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Stunting</p>
            </div>
        </div>
    </x-card>

    {{-- Grafik Stunting Rate per Kecamatan --}}
    <x-card title="📈 Stunting Rate per Kecamatan">
        <div class="h-64">
            <canvas id="stuntingChart"></canvas>
        </div>
    </x-card>

    {{-- Laporan Kunjungan Posyandu Bulanan --}}
    <x-card title="📅 Laporan Kunjungan Posyandu Bulanan (6 Bulan Terakhir)">
        <div class="h-64">
            <canvas id="monthlyVisitsChart"></canvas>
        </div>
        <div class="mt-4 grid grid-cols-6 gap-4">
            @foreach($monthlyVisits as $visit)
            <div class="text-center">
                <p class="text-xs text-gray-500">{{ $visit['month'] }}</p>
                <p class="text-lg font-bold text-primary-600">{{ number_format($visit['visits']) }}</p>
                <p class="text-xs text-gray-400">{{ $visit['posyandu_visited'] }} posyandu</p>
            </div>
            @endforeach
        </div>
    </x-card>

    {{-- Balita Gizi Buruk --}}
    @if($giziBurukBalitas->count() > 0)
    <x-card title="⚠️ Balita Gizi Buruk - Seluruh Jakarta">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posyandu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Z-Score</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($giziBurukBalitas as $balita)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $balita->name }}</p>
                            <p class="text-xs text-gray-500">{{ $balita->mother_name }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $balita->posyandu->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $balita->posyandu->kelurahan->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                {{ $balita->pertumbuhanRecords->first()->z_score_bbu }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin-kota.balita.show', $balita) }}" class="text-primary-600 hover:text-primary-900">Lihat</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Stunting Rate Chart
    const stuntingCtx = document.getElementById('stuntingChart').getContext('2d');
    new Chart(stuntingCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stuntingByKecamatan->pluck('name')) !!},
            datasets: [{
                label: 'Stunting Rate (%)',
                data: {!! json_encode($stuntingByKecamatan->pluck('stunting_rate')) !!},
                backgroundColor: {!! json_encode($stuntingByKecamatan->map(fn($k) => $k['stunting_rate'] > 20 ? 'rgba(239, 68, 68, 0.7)' : 'rgba(16, 185, 129, 0.7)')) !!},
                borderColor: {!! json_encode($stuntingByKecamatan->map(fn($k) => $k['stunting_rate'] > 20 ? 'rgb(239, 68, 68)' : 'rgb(16, 185, 129)')) !!},
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Stunting Rate (%)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Stunting Rate: ' + context.parsed.y.toFixed(2) + '%';
                        }
                    }
                }
            }
        }
    });

    // Monthly Visits Chart
    const visitsCtx = document.getElementById('monthlyVisitsChart').getContext('2d');
    new Chart(visitsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyVisits->pluck('month')) !!},
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: {!! json_encode($monthlyVisits->pluck('visits')) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Kunjungan'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection
