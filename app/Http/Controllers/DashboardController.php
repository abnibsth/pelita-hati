<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Kecamatan;
use App\Models\Kehadiran;
use App\Models\Kelurahan;
use App\Models\PertumbuhanRecord;
use App\Models\Posyandu;
use App\Models\Rujukan;
use App\Models\User;
use App\Services\ReportGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $totalKecamatan = Kecamatan::count();
        $totalKelurahan = Kelurahan::count();
        $totalPosyandu = Posyandu::count();
        $totalBalita = Balita::where('status', 'aktif')->count();
        $totalKader = User::where('role', 'kader')->count();
        $totalNakes = User::where('role', 'nakes_puskesmas')->count();

        // Status gizi seluruh Jakarta
        $latestRecords = PertumbuhanRecord::select('status_gizi')
            ->join('balitas', 'pertumbuhan_records.balita_id', '=', 'balitas.id')
            ->where('balitas.status', 'aktif')
            ->orderBy('pertumbuhan_records.tanggal', 'desc')
            ->get()
            ->groupBy('balita_id')
            ->map(fn ($records) => $records->first());

        $statusGizi = [
            'normal' => $latestRecords->where('status_gizi', 'normal')->count(),
            'kurang' => $latestRecords->where('status_gizi', 'kurang')->count(),
            'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
            'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
            'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
        ];

        // Stunting rate per kecamatan
        $stuntingByKecamatan = Kecamatan::with(['kelurahans.posyandus.balitas.pertumbuhanRecords' => function ($q) {
            $q->select('balita_id', 'status_gizi')
                ->orderBy('tanggal', 'desc');
        }])
            ->get()
            ->map(function ($kecamatan) {
                $totalBalita = 0;
                $stuntingCount = 0;

                foreach ($kecamatan->kelurahans as $kelurahan) {
                    foreach ($kelurahan->posyandus as $posyandu) {
                        foreach ($posyandu->balitas as $balita) {
                            if ($balita->status === 'aktif') {
                                $totalBalita++;
                                $latestRecord = $balita->pertumbuhanRecords->first();
                                if ($latestRecord && $latestRecord->status_gizi === 'stunting') {
                                    $stuntingCount++;
                                }
                            }
                        }
                    }
                }

                return [
                    'id' => $kecamatan->id,
                    'name' => $kecamatan->name,
                    'total_balita' => $totalBalita,
                    'stunting_count' => $stuntingCount,
                    'stunting_rate' => $totalBalita > 0 ? ($stuntingCount / $totalBalita) * 100 : 0,
                ];
            });

        // Monthly posyandu visits (last 6 months)
        $monthlyVisits = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $monthName = $date->format('M Y');

            $visitCount = Kehadiran::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('hadir', true)
                ->count();

            $posyanduCount = Kehadiran::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->distinct('posyandu_id')
                ->count('posyandu_id');

            return [
                'month' => $monthName,
                'visits' => $visitCount,
                'posyandu_visited' => $posyanduCount,
            ];
        });

        // Kecamatan with low coverage (alert)
        $lowCoverageKecamatan = $stuntingByKecamatan
            ->filter(fn ($k) => $k['stunting_rate'] > 20) // Threshold: 20%
            ->sortByDesc('stunting_rate')
            ->values();

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
            'totalKader',
            'totalNakes',
            'statusGizi',
            'stuntingByKecamatan',
            'monthlyVisits',
            'lowCoverageKecamatan',
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

        // Check if kecamatan exists
        if (! $kecamatan) {
            abort(403, 'User tidak memiliki kecamatan yang valid. Silakan hubungi administrator.');
        }

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
            ->map(fn ($records) => $records->first());

        $statusGizi = [
            'normal' => $latestRecords->where('status_gizi', 'normal')->count(),
            'kurang' => $latestRecords->where('status_gizi', 'kurang')->count(),
            'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
            'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
            'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
        ];

        // Statistik Posyandu per Kelurahan
        $posyanduStats = $kecamatan->kelurahans->load('posyandus')->map(function ($kelurahan) {
            $posyanduCount = $kelurahan->posyandus->count();
            $balitaCount = $kelurahan->posyandus->sum(function ($posyandu) {
                return $posyandu->balitas()->where('status', 'aktif')->count();
            });
            $kaderCount = User::where('role', 'kader')
                ->whereIn('posyandu_id', $kelurahan->posyandus->pluck('id'))
                ->count();

            return [
                'kelurahan' => $kelurahan,
                'posyandu_count' => $posyanduCount,
                'balita_count' => $balitaCount,
                'kader_count' => $kaderCount,
            ];
        });

        // Data Balita per Kelurahan
        $balitaPerKelurahan = $kecamatan->kelurahans->load('posyandus.balitas')->map(function ($kelurahan) {
            $balitas = $kelurahan->posyandus->flatMap->balitas->where('status', 'aktif');
            $stuntingCount = $balitas->filter(function ($balita) {
                $latest = $balita->pertumbuhanRecords->first();

                return $latest && $latest->status_gizi === 'stunting';
            })->count();
            $giziBurukCount = $balitas->filter(function ($balita) {
                $latest = $balita->pertumbuhanRecords->first();

                return $latest && $latest->status_gizi === 'gizi_buruk';
            })->count();

            return [
                'kelurahan' => $kelurahan,
                'total_balita' => $balitas->count(),
                'stunting' => $stuntingCount,
                'gizi_buruk' => $giziBurukCount,
                'stunting_rate' => $balitas->count() > 0 ? ($stuntingCount / $balitas->count()) * 100 : 0,
            ];
        });

        // Monitoring Imunisasi per Kelurahan
        $imunisasiPerKelurahan = DB::table('imunisasi_records')
            ->select('kelurahans.name as kelurahan_name', 'kelurahans.id as kelurahan_id')
            ->join('balitas', 'imunisasi_records.balita_id', '=', 'balitas.id')
            ->join('posyandus', 'balitas.posyandu_id', '=', 'posyandus.id')
            ->join('kelurahans', 'posyandus.kelurahan_id', '=', 'kelurahans.id')
            ->whereIn('kelurahans.id', $kecamatan->kelurahans->pluck('id'))
            ->whereYear('imunisasi_records.tanggal_diberikan', now()->year)
            ->whereMonth('imunisasi_records.tanggal_diberikan', now()->month)
            ->groupBy('kelurahans.id', 'kelurahans.name')
            ->selectRaw('kelurahans.id as kelurahan_id, kelurahans.name as kelurahan_name, COUNT(*) as total_imunisasi')
            ->get()
            ->keyBy('kelurahan_id');

        // Add kelurahans with zero immunizations
        foreach ($kecamatan->kelurahans as $kelurahan) {
            if (! $imunisasiPerKelurahan->has($kelurahan->id)) {
                $imunisasiPerKelurahan->put($kelurahan->id, [
                    'kelurahan_name' => $kelurahan->name,
                    'kelurahan_id' => $kelurahan->id,
                    'total_imunisasi' => 0,
                ]);
            }
        }

        // Jadwal Posyandu Seluruh Kelurahan (upcoming)
        $jadwalPosyandu = Posyandu::whereIn('kelurahan_id', $kecamatan->kelurahans->pluck('id'))
            ->with(['kelurahan', 'kaderKoordinator'])
            ->get()
            ->map(function ($posyandu) {
                $jadwal = $this->getNextJadwalForPosyandu($posyandu);

                return [
                    'posyandu' => $posyandu,
                    'jadwal' => $jadwal,
                ];
            })
            ->sortBy('jadwal.tanggal')
            ->take(10);

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
            'posyanduStats',
            'balitaPerKelurahan',
            'imunisasiPerKelurahan',
            'jadwalPosyandu',
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

        // Data seluruh posyandu di kelurahan dengan detail
        $posyanduDetails = $kelurahan->posyandus()
            ->with(['kaderKoordinator', 'kelurahan.kecamatan'])
            ->get()
            ->map(function ($posyandu) {
                $balitaCount = $posyandu->balitas()->where('status', 'aktif')->count();
                $kaderCount = User::where('role', 'kader')
                    ->where('posyandu_id', $posyandu->id)
                    ->count();

                return [
                    'posyandu' => $posyandu,
                    'balita_count' => $balitaCount,
                    'kader_count' => $kaderCount,
                ];
            });

        // Manajemen kader posyandu
        $kaders = User::where('role', 'kader')
            ->where('kelurahan_id', $kelurahan->id)
            ->with(['posyandu'])
            ->orderBy('name')
            ->get();

        // Jadwal kegiatan posyandu (bulan ini)
        $jadwalKegiatan = $kelurahan->posyandus()
            ->with(['kaderKoordinator', 'kelurahan'])
            ->get()
            ->map(function ($posyandu) {
                $jadwal = $this->getNextJadwal($posyandu);

                return [
                    'posyandu' => $posyandu,
                    'jadwal' => $jadwal,
                ];
            })
            ->sortBy('jadwal.tanggal');

        // Stok obat & vitamin posyandu (aggregate dari semua posyandu)
        // Note: Ini menggunakan data imunisasi records sebagai proxy untuk tracking
        $imunisasiTypes = DB::table('imunisasi_records')
            ->select('jenis_imunisasi', DB::raw('COUNT(*) as total'))
            ->join('balitas', 'imunisasi_records.balita_id', '=', 'balitas.id')
            ->join('posyandus', 'balitas.posyandu_id', '=', 'posyandus.id')
            ->where('posyandus.kelurahan_id', $kelurahan->id)
            ->whereYear('imunisasi_records.tanggal_diberikan', now()->year)
            ->whereMonth('imunisasi_records.tanggal_diberikan', now()->month)
            ->groupBy('jenis_imunisasi')
            ->get();

        // Reminder jadwal posyandu (yang akan datang dalam 7 hari)
        $reminderJadwal = $kelurahan->posyandus()
            ->with(['kaderKoordinator', 'kelurahan'])
            ->get()
            ->map(function ($posyandu) {
                $jadwal = $this->getNextJadwal($posyandu);

                return [
                    'posyandu' => $posyandu,
                    'jadwal' => $jadwal,
                    'days_until' => now()->diffInDays($jadwal['tanggal'], false),
                ];
            })
            ->filter(fn ($item) => $item['days_until'] >= 0 && $item['days_until'] <= 7)
            ->sortBy('jadwal.tanggal');

        return view('dashboards.admin-kelurahan', compact(
            'kelurahan',
            'totalPosyandu',
            'totalBalita',
            'statusGizi',
            'giziBurukBalitas',
            'skdn',
            'posyanduDetails',
            'kaders',
            'jadwalKegiatan',
            'imunisasiTypes',
            'reminderJadwal'
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
        $rujukanAktif = Rujukan::where('puskesmas_id', $puskesmas->id)
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

        $hadirBulanIni = Kehadiran::where('posyandu_id', $posyandu->id)
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

        // Kehadiran summary for current month
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $kehadiranSummary = Kehadiran::select(
            'tanggal',
            DB::raw('COUNT(CASE when hadir = 1 THEN 1 END) as total_hadir'),
            DB::raw('COUNT(CASE when hadir = 0 THEN 1 END) as total_tidak_hadir'),
            DB::raw('COUNT(*) as total')
        )
            ->whereHas('balita', function ($q) use ($posyandu) {
                $q->where('posyandu_id', $posyandu->id);
            })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->persentase = $item->total > 0
                    ? ($item->total_hadir / $item->total) * 100
                    : 0;

                return $item;
            });

        // Calculate attendance statistics
        $totalKehadiranBulanIni = Kehadiran::whereHas('balita', function ($q) use ($posyandu) {
            $q->where('posyandu_id', $posyandu->id);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        $totalHadirBulanIni = Kehadiran::whereHas('balita', function ($q) use ($posyandu) {
            $q->where('posyandu_id', $posyandu->id);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('hadir', 1)
            ->count();

        $avgKehadiran = $totalKehadiranBulanIni > 0
            ? ($totalHadirBulanIni / $totalKehadiranBulanIni) * 100
            : 0;

        $frekuensiPosyandu = Kehadiran::whereHas('balita', function ($q) use ($posyandu) {
            $q->where('posyandu_id', $posyandu->id);
        })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->distinct('tanggal')
            ->count('tanggal');

        return view('dashboards.kader', compact(
            'posyandu',
            'totalBalita',
            'hadirBulanIni',
            'statusGizi',
            'jadwalBerikutnya',
            'giziBurukBalitas',
            'skdn',
            'kehadiranSummary',
            'avgKehadiran',
            'frekuensiPosyandu'
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
            ->with(['posyandu.kelurahan.kecamatan', 'pertumbuhanRecords' => function ($q) {
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
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 0,
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
     * Alias untuk getNextJadwal - digunakan di adminKecamatan
     */
    private function getNextJadwalForPosyandu(Posyandu $posyandu): array
    {
        return $this->getNextJadwal($posyandu);
    }

    /**
     * Helper method untuk getStatusGiziStats dengan multiple posyandu
     */
    public function getStatusGiziStatsForPosyanduIds($posyanduIds): array
    {
        $latestRecords = DB::table('pertumbuhan_records as pr')
            ->select('pr.balita_id', 'pr.status_gizi')
            ->join('balitas as b', 'pr.balita_id', '=', 'b.id')
            ->whereIn('b.posyandu_id', $posyanduIds)
            ->orderBy('pr.tanggal', 'desc')
            ->get()
            ->groupBy('balita_id')
            ->map(fn ($records) => $records->first());

        return [
            'normal' => $latestRecords->where('status_gizi', 'normal')->count(),
            'kurang' => $latestRecords->where('status_gizi', 'kurang')->count(),
            'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
            'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
            'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
        ];
    }
}
