<?php

namespace App\Services;

use App\Models\Balita;
use App\Models\ImunisasiRecord;
use App\Models\Kecamatan;
use App\Models\Kehadiran;
use App\Models\Kelurahan;
use App\Models\PertumbuhanRecord;
use App\Models\Posyandu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk generate laporan dan statistik
 */
class ReportGenerator
{
    /**
     * Generate laporan SKDN (Sistem Pencatatan Posyandu)
     * S: Sasaran (balita 0-59 bulan)
     * K: Kunjungan (jumlah kunjungan balita)
     * D: Diberi (jumlah balita dapat pelayanan)
     * N: Nutrisi (jumlah balita dengan gizi buruk)
     */
    public function generateSKDN(Posyandu $posyandu, int $month, int $year): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // S - Sasaran: Total balita 0-59 bulan di posyandu
        $sasaran = Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->whereRaw('TIMESTAMPDIFF(MONTH, birth_date, NOW()) BETWEEN 0 AND 59')
            ->count();

        // K - Kunjungan: Jumlah kunjungan balita ke posyandu bulan ini
        $kunjungan = Kehadiran::where('posyandu_id', $posyandu->id)
            ->where('hadir', true)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        // D - Diberi: Jumlah balita yang dapat pelayanan (penimbangan)
        $diberi = PertumbuhanRecord::whereHas('balita', function ($q) use ($posyandu) {
            $q->where('posyandu_id', $posyandu->id);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        // N - Nutrisi: Jumlah balita dengan gizi buruk
        $nutrisi = PertumbuhanRecord::whereHas('balita', function ($q) use ($posyandu) {
            $q->where('posyandu_id', $posyandu->id);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status_gizi', 'gizi_buruk')
            ->count();

        return [
            'sasaran' => $sasaran,
            'kunjungan' => $kunjungan,
            'diberi' => $diberi,
            'nutrisi' => $nutrisi,
            'coverage' => $sasaran > 0 ? round(($diberi / $sasaran) * 100, 2) : 0,
            'month' => $month,
            'year' => $year,
        ];
    }

    /**
     * Generate laporan SKDN untuk Kelurahan
     */
    public function generateSKDNKelurahan(Kelurahan $kelurahan, int $month, int $year): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $posyanduIds = $kelurahan->posyandus->pluck('id');

        // S - Sasaran
        $sasaran = Balita::whereIn('posyandu_id', $posyanduIds)
            ->where('status', 'aktif')
            ->whereRaw('TIMESTAMPDIFF(MONTH, birth_date, NOW()) BETWEEN 0 AND 59')
            ->count();

        // K - Kunjungan
        $kunjungan = Kehadiran::whereIn('posyandu_id', $posyanduIds)
            ->where('hadir', true)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        // D - Diberi
        $diberi = PertumbuhanRecord::whereHas('balita', function ($q) use ($posyanduIds) {
            $q->whereIn('posyandu_id', $posyanduIds);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        // N - Nutrisi
        $nutrisi = PertumbuhanRecord::whereHas('balita', function ($q) use ($posyanduIds) {
            $q->whereIn('posyandu_id', $posyanduIds);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status_gizi', 'gizi_buruk')
            ->count();

        return [
            'sasaran' => $sasaran,
            'kunjungan' => $kunjungan,
            'diberi' => $diberi,
            'nutrisi' => $nutrisi,
            'coverage' => $sasaran > 0 ? round(($diberi / $sasaran) * 100, 2) : 0,
        ];
    }

    /**
     * Generate laporan SKDN untuk Kecamatan
     */
    public function generateSKDNKecamatan(Kecamatan $kecamatan, int $month, int $year): array
    {
        $kelurahanIds = $kecamatan->kelurahans->pluck('id');
        $posyanduIds = DB::table('posyandus')
            ->whereIn('kelurahan_id', $kelurahanIds)
            ->pluck('id');

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // S - Sasaran
        $sasaran = Balita::whereIn('posyandu_id', $posyanduIds)
            ->where('status', 'aktif')
            ->whereRaw('TIMESTAMPDIFF(MONTH, birth_date, NOW()) BETWEEN 0 AND 59')
            ->count();

        // K - Kunjungan
        $kunjungan = Kehadiran::whereIn('posyandu_id', $posyanduIds)
            ->where('hadir', true)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        // D - Diberi
        $diberi = PertumbuhanRecord::whereHas('balita', function ($q) use ($posyanduIds) {
            $q->whereIn('posyandu_id', $posyanduIds);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        // N - Nutrisi
        $nutrisi = PertumbuhanRecord::whereHas('balita', function ($q) use ($posyanduIds) {
            $q->whereIn('posyandu_id', $posyanduIds);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status_gizi', 'gizi_buruk')
            ->count();

        return [
            'sasaran' => $sasaran,
            'kunjungan' => $kunjungan,
            'diberi' => $diberi,
            'nutrisi' => $nutrisi,
            'coverage' => $sasaran > 0 ? round(($diberi / $sasaran) * 100, 2) : 0,
        ];
    }

    /**
     * Statistik status gizi untuk dashboard
     */
    public function getStatusGiziStats(Posyandu $posyandu): array
    {
        $latestRecords = DB::table('pertumbuhan_records as pr')
            ->select('pr.balita_id', 'pr.status_gizi', 'pr.tanggal')
            ->join('balitas as b', 'pr.balita_id', '=', 'b.id')
            ->where('b.posyandu_id', $posyandu->id)
            ->orderBy('pr.tanggal', 'desc')
            ->get()
            ->groupBy('balita_id')
            ->map(function ($records) {
                return $records->first();
            });

        return [
            'normal' => $latestRecords->where('status_gizi', 'normal')->count(),
            'kurang' => $latestRecords->where('status_gizi', 'kurang')->count(),
            'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
            'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
            'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
        ];
    }

    /**
     * List balita dengan gizi buruk untuk tindak lanjut
     */
    public function getGiziBurukBalitas(Posyandu $posyandu, int $limit = 10)
    {
        return Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->whereHas('pertumbuhanRecords', function ($q) {
                $q->where('status_gizi', 'gizi_buruk')
                    ->orderBy('tanggal', 'desc');
            })
            ->with(['pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(1);
            }])
            ->limit($limit)
            ->get();
    }

    /**
     * List balita stunting untuk tindak lanjut
     */
    public function getStuntingBalitas(Posyandu $posyandu, int $limit = 10)
    {
        return Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->whereHas('pertumbuhanRecords', function ($q) {
                $q->where('status_gizi', 'stunting')
                    ->orderBy('tanggal', 'desc');
            })
            ->with(['pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(1);
            }])
            ->limit($limit)
            ->get();
    }

    /**
     * Statistik kehadiran bulanan
     */
    public function getKehadiranStats(Posyandu $posyandu, int $month, int $year): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $totalKehadiran = Kehadiran::where('posyandu_id', $posyandu->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        $hadir = Kehadiran::where('posyandu_id', $posyandu->id)
            ->where('hadir', true)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        $totalBalita = Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->count();

        return [
            'total_kehadiran' => $totalKehadiran,
            'hadir' => $hadir,
            'tidak_hadir' => $totalKehadiran - $hadir,
            'total_balita' => $totalBalita,
            'persentase' => $totalBalita > 0 ? round(($hadir / $totalBalita) * 100, 2) : 0,
        ];
    }

    /**
     * Statistik imunisasi
     */
    public function getImunisasiStats(Posyandu $posyandu): array
    {
        $balitaIds = Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->pluck('id');

        $imunisasiTypes = [
            'HB-0', 'BCG', 'Polio-1', 'Polio-2', 'Polio-3', 'Polio-4',
            'DPT-HB-1', 'DPT-HB-2', 'DPT-HB-3', 'Campak', 'Campak-Rubella',
        ];

        $stats = [];
        foreach ($imunisasiTypes as $type) {
            $count = ImunisasiRecord::whereIn('balita_id', $balitaIds)
                ->where('jenis_imunisasi', $type)
                ->count();
            $stats[$type] = $count;
        }

        $totalBalita = Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->count();

        return [
            'stats' => $stats,
            'total_balita' => $totalBalita,
        ];
    }

    /**
     * Trend pertumbuhan balita (untuk grafik)
     */
    public function getPertumbuhanTrend(Balita $balita, int $monthsBack = 12): array
    {
        $startDate = now()->subMonths($monthsBack);

        $records = PertumbuhanRecord::where('balita_id', $balita->id)
            ->where('tanggal', '>=', $startDate)
            ->orderBy('tanggal', 'asc')
            ->get();

        return [
            'labels' => $records->map(fn ($r) => $r->tanggal->format('M Y')),
            'berat_badan' => $records->map(fn ($r) => $r->berat_badan),
            'tinggi_badan' => $records->map(fn ($r) => $r->tinggi_badan),
            'z_score_bbu' => $records->map(fn ($r) => $r->z_score_bbu),
            'z_score_tbu' => $records->map(fn ($r) => $r->z_score_tbu),
            'z_score_bbtb' => $records->map(fn ($r) => $r->z_score_bbtb),
        ];
    }
}
