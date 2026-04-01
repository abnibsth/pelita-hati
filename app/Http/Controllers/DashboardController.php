<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\PertumbuhanRecord;
use App\Models\Posyandu;
use App\Services\ReportGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private ReportGenerator $reportGenerator;

    public function __construct(ReportGenerator $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
    }

    /**
     * Dashboard untuk Admin Kota (Dinkes Jakarta)
     */
    public function adminKota()
    {
        $user = Auth::user();

        // Statistik seluruh Jakarta
        $totalKecamatan = \App\Models\Kecamatan::count();
        $totalKelurahan = \App\Models\Kelurahan::count();
        $totalPosyandu = Posyandu::count();
        $totalBalita = Balita::where('status', 'aktif')->count();

        // Status gizi seluruh Jakarta
        $latestRecords = PertumbuhanRecord::select('status_gizi')
            ->join('balitas', 'pertumbuhan_records.balita_id', '=', 'balitas.id')
            ->where('balitas.status', 'aktif')
            ->orderBy('pertumbuhan_records.tanggal', 'desc')
            ->get()
            ->groupBy('balita_id')
            ->map(fn($records) => $records->first());

        $statusGizi = [
            'normal' => $latestRecords->where('status_gizi', 'normal')->count(),
            'kurang' => $latestRecords->where('status_gizi', 'kurang')->count(),
            'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
            'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
            'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
        ];

        // Balita gizi buruk (untuk monitoring)
        $giziBurukBalitas = Balita::where('status', 'aktif')
            ->whereHas('pertumbuhanRecords', function ($q) {
                $q->where('status_gizi', 'gizi_buruk')
                  ->orderBy('tanggal', 'desc');
            })
            ->with(['posyandu.kelurahan.kecamatan', 'pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(1);
            }])
            ->limit(10)
            ->get();

        return view('dashboards.admin-kota', compact(
            'totalKecamatan',
            'totalKelurahan',
            'totalPosyandu',
            'totalBalita',
            'statusGizi',
            'giziBurukBalitas'
        ));
    }

    /**
     * Dashboard untuk Admin Kecamatan
     */
    public function adminKecamatan()
    {
        $user = Auth::user();
        $kecamatan = $user->kecamatan;

        $totalKelurahan = $kecamatan->kelurahans->count();
        $totalPosyandu = Posyandu::whereIn('kelurahan_id', $kecamatan->kelurahans->pluck('id'))->count();
        $totalBalita = Balita::whereHas('posyandu', function ($q) use ($kecamatan) {
                $q->whereIn('kelurahan_id', $kecamatan->kelurahans->pluck('id'));
            })
            ->where('status', 'aktif')
            ->count();

        // Status gizi di kecamatan
        $posyanduIds = Posyandu::whereIn('kelurahan_id', $kecamatan->kelurahans->pluck('id'))->pluck('id');
        $latestRecords = PertumbuhanRecord::select('pertumbuhan_records.*')
            ->join('balitas', 'pertumbuhan_records.balita_id', '=', 'balitas.id')
            ->whereIn('balitas.posyandu_id', $posyanduIds)
            ->where('balitas.status', 'aktif')
            ->orderBy('pertumbuhan_records.tanggal', 'desc')
            ->get()
            ->groupBy('balita_id')
            ->map(fn($records) => $records->first());

        $statusGizi = [
            'normal' => $latestRecords->where('status_gizi', 'normal')->count(),
            'kurang' => $latestRecords->where('status_gizi', 'kurang')->count(),
            'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
            'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
            'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
        ];

        // Gizi buruk di kecamatan
        $giziBurukBalitas = Balita::whereHas('posyandu', function ($q) use ($posyanduIds) {
                $q->whereIn('posyandu_id', $posyanduIds);
            })
            ->where('status', 'aktif')
            ->whereHas('pertumbuhanRecords', function ($q) {
                $q->where('status_gizi', 'gizi_buruk')
                  ->orderBy('tanggal', 'desc');
            })
            ->with(['posyandu.kelurahan', 'pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(1);
            }])
            ->limit(10)
            ->get();

        return view('dashboards.admin-kecamatan', compact(
            'kecamatan',
            'totalKelurahan',
            'totalPosyandu',
            'totalBalita',
            'statusGizi',
            'giziBurukBalitas'
        ));
    }

    /**
     * Dashboard untuk Admin Kelurahan
     */
    public function adminKelurahan()
    {
        $user = Auth::user();
        $kelurahan = $user->kelurahan;

        $totalPosyandu = $kelurahan->posyandus->count();
        $totalBalita = Balita::whereHas('posyandu', function ($q) use ($kelurahan) {
                $q->where('kelurahan_id', $kelurahan->id);
            })
            ->where('status', 'aktif')
            ->count();

        // Status gizi di kelurahan
        $posyanduIds = $kelurahan->posyandus->pluck('id');
        $statusGizi = $this->getStatusGiziStatsForPosyanduIds($posyanduIds);

        // Gizi buruk di kelurahan
        $giziBurukBalitas = Balita::whereIn('posyandu_id', $posyanduIds)
            ->where('status', 'aktif')
            ->whereHas('pertumbuhanRecords', function ($q) {
                $q->where('status_gizi', 'gizi_buruk')
                  ->orderBy('tanggal', 'desc');
            })
            ->with(['posyandu', 'pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(1);
            }])
            ->limit(10)
            ->get();

        // SKDN bulan ini
        $skdn = $this->reportGenerator->generateSKDNKelurahan(
            $kelurahan,
            now()->month,
            now()->year
        );

        return view('dashboards.admin-kelurahan', compact(
            'kelurahan',
            'totalPosyandu',
            'totalBalita',
            'statusGizi',
            'giziBurukBalitas',
            'skdn'
        ));
    }

    /**
     * Dashboard untuk Nakes Puskesmas
     */
    public function nakes()
    {
        $user = Auth::user();
        $puskesmas = $user->puskesmas;

        // Balita gizi buruk untuk tindak lanjut (rujukan)
        $giziBurukBalitas = Balita::whereHas('posyandu.kelurahan', function ($q) use ($puskesmas) {
                $q->where('kecamatan_id', $puskesmas->kecamatan_id);
            })
            ->where('status', 'aktif')
            ->whereHas('pertumbuhanRecords', function ($q) {
                $q->where('status_gizi', 'gizi_buruk')
                  ->orderBy('tanggal', 'desc');
            })
            ->with(['posyandu.kelurahan', 'pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(1);
            }])
            ->limit(20)
            ->get();

        // Rujukan yang sedang diproses
        $rujukanAktif = \App\Models\Rujukan::where('puskesmas_id', $puskesmas->id)
            ->whereIn('status', ['dirujuk', 'diteruskan'])
            ->with(['balita', 'nakes'])
            ->orderBy('tanggal_rujuk', 'desc')
            ->limit(10)
            ->get();

        return view('dashboards.nakes', compact(
            'puskesmas',
            'giziBurukBalitas',
            'rujukanAktif'
        ));
    }

    /**
     * Dashboard untuk Kader Posyandu
     */
    public function kader()
    {
        $user = Auth::user();
        $posyandu = $user->posyandu;

        // Statistik posyandu
        $totalBalita = Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->count();

        $hadirBulanIni = \App\Models\Kehadiran::where('posyandu_id', $posyandu->id)
            ->where('hadir', true)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Status gizi
        $statusGizi = $this->reportGenerator->getStatusGiziStats($posyandu);

        // Jadwal posyandu berikutnya
        $jadwalBerikutnya = $this->getNextJadwal($posyandu);

        // Gizi buruk untuk tindak lanjut
        $giziBurukBalitas = $this->reportGenerator->getGiziBurukBalitas($posyandu, 5);

        // SKDN bulan ini
        $skdn = $this->reportGenerator->generateSKDN($posyandu, now()->month, now()->year);

        return view('dashboards.kader', compact(
            'posyandu',
            'totalBalita',
            'hadirBulanIni',
            'statusGizi',
            'jadwalBerikutnya',
            'giziBurukBalitas',
            'skdn'
        ));
    }

    /**
     * Dashboard untuk Orangtua
     */
    public function orangtua()
    {
        $user = Auth::user();

        $balitas = Balita::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->with(['pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(1);
            }, 'imunisasiRecords'])
            ->get();

        return view('dashboards.orangtua', compact('balitas'));
    }

    /**
     * Hitung jadwal posyandu berikutnya
     */
    private function getNextJadwal(Posyandu $posyandu): array
    {
        $now = now();
        $mingguKe = $posyandu->jadwal_minggu_ke;
        $hari = $posyandu->jadwal_hari;

        // Mapping hari
        $hariMap = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 0
        ];

        $targetHari = $hariMap[$hari] ?? 1;

        // Hitung minggu ke-berapa saat ini
        $currentWeek = ceil($now->day / 7);

        // Tentukan tanggal
        if ($currentWeek < $mingguKe) {
            $targetDate = clone $now;
            $targetDate->day = 1;
            $targetDate->addWeeks($mingguKe - 1);
            $targetDate->next($targetHari);
        } else {
            // Bulan depan
            $targetDate = clone $now;
            $targetDate->addMonth();
            $targetDate->day = 1;
            $targetDate->addWeeks($mingguKe - 1);
            $targetDate->next($targetHari);
        }

        return [
            'tanggal' => $targetDate,
            'hari' => $hari,
            'jam_mulai' => $posyandu->jadwal_jam_mulai,
            'jam_selesai' => $posyandu->jadwal_jam_selesai,
        ];
    }

    /**
     * Helper method untuk getStatusGiziStats dengan multiple posyandu
     */
    public function getStatusGiziStatsForPosyanduIds($posyanduIds): array
    {
        $latestRecords = \Illuminate\Support\Facades\DB::table('pertumbuhan_records as pr')
            ->select('pr.balita_id', 'pr.status_gizi')
            ->join('balitas as b', 'pr.balita_id', '=', 'b.id')
            ->whereIn('b.posyandu_id', $posyanduIds)
            ->orderBy('pr.tanggal', 'desc')
            ->get()
            ->groupBy('balita_id')
            ->map(fn($records) => $records->first());

        return [
            'normal' => $latestRecords->where('status_gizi', 'normal')->count(),
            'kurang' => $latestRecords->where('status_gizi', 'kurang')->count(),
            'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
            'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
            'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
        ];
    }
}
