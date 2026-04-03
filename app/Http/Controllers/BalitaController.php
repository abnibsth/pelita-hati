<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\PertumbuhanRecord;
use App\Models\Posyandu;
use App\Services\GiziCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BalitaController extends Controller
{
    private GiziCalculator $giziCalculator;

    public function __construct(GiziCalculator $giziCalculator)
    {
        $this->giziCalculator = $giziCalculator;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Build query based on user role
        $query = Balita::with(['posyandu.kelurahan.kecamatan', 'orangtua']);

        if ($user->role === 'kader') {
            $query->where('posyandu_id', $user->posyandu_id);
        } elseif ($user->role === 'admin_kelurahan') {
            $query->whereHas('posyandu', function ($q) use ($user) {
                $q->where('kelurahan_id', $user->kelurahan_id);
            })
                ->with(['pertumbuhanRecords' => function ($q) {
                    $q->orderBy('tanggal', 'desc')->limit(1);
                }]);
        } elseif ($user->role === 'admin_kecamatan') {
            $query->whereHas('posyandu.kelurahan', function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            })
                ->with(['pertumbuhanRecords' => function ($q) {
                    $q->orderBy('tanggal', 'desc')->limit(1);
                }]);
        } elseif ($user->role === 'orangtua') {
            $query->where('user_id', $user->id)
                ->with(['posyandu.kelurahan.kecamatan', 'pertumbuhanRecords' => function ($q) {
                    $q->orderBy('tanggal', 'desc')->limit(1);
                }]);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('mother_name', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $balitas = $query->orderBy('name', 'asc')->paginate(15);

        // Use different view for orangtua
        if ($user->role === 'orangtua') {
            return view('orangtua.anak.index', compact('balitas'));
        }

        // For admin_kecamatan and admin_kelurahan, add status gizi stats
        if (in_array($user->role, ['admin_kecamatan', 'admin_kelurahan'])) {
            $posyanduIds = [];

            if ($user->role === 'admin_kecamatan') {
                $kecamatan = $user->kecamatan;
                $posyanduIds = Posyandu::whereIn('kelurahan_id', $kecamatan->kelurahans->pluck('id'))->pluck('id');
            } else {
                $kelurahan = $user->kelurahan;
                $posyanduIds = Posyandu::where('kelurahan_id', $kelurahan->id)->pluck('id');
            }

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
                'lebih' => $latestRecords->where('status_gizi', 'lebih')->count(),
                'gizi_buruk' => $latestRecords->where('status_gizi', 'gizi_buruk')->count(),
                'stunting' => $latestRecords->where('status_gizi', 'stunting')->count(),
            ];

            if ($user->role === 'admin_kecamatan') {
                return view('admin-kecamatan.balita.index', compact('balitas', 'statusGizi', 'kecamatan'));
            } else {
                return view('admin-kelurahan.balita.index', compact('balitas', 'statusGizi', 'kelurahan'));
            }
        }

        return view('balita.index', compact('balitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Balita::class);

        $user = Auth::user();
        $posyandus = $this->getAvailablePosyandus($user);

        return view('balita.create', compact('posyandus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Balita::class);

        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:balitas,nik',
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:L,P',
            'mother_name' => 'required|string|max:255',
            'mother_nik' => 'required|string|size:16',
            'father_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'rt_rw' => 'nullable|string|max:20',
            'posyandu_id' => 'required|exists:posyandus,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $validated['registration_date'] = now();
        $validated['status'] = 'aktif';

        // Calculate and store age in months at registration
        $birthDate = Carbon::parse($validated['birth_date']);
        $validated['age_months'] = $birthDate->diffInMonths(now());

        Balita::create($validated);

        return redirect()->route($this->getBalitaIndexRoute())
            ->with('success', 'Data balita berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Balita $balita)
    {
        Gate::authorize('view', $balita);

        $user = Auth::user();

        $balita->load([
            'pertumbuhanRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc');
            },
            'imunisasiRecords' => function ($q) {
                $q->orderBy('tanggal_diberikan', 'desc');
            },
            'vitaminRecords' => function ($q) {
                $q->orderBy('tanggal', 'desc');
            },
            'orangtua',
            'posyandu.kelurahan.kecamatan',
        ]);

        // Get latest growth record
        $latestGrowth = $balita->pertumbuhanRecords->first();

        // Get growth trend for chart
        $growthTrend = [
            'labels' => $balita->pertumbuhanRecords->sortBy('tanggal')
                ->map(fn ($r) => $r->tanggal->format('d M Y')),
            'berat_badan' => $balita->pertumbuhanRecords->sortBy('tanggal')
                ->map(fn ($r) => $r->berat_badan),
            'tinggi_badan' => $balita->pertumbuhanRecords->sortBy('tanggal')
                ->map(fn ($r) => $r->tinggi_badan),
        ];

        // Use different view for orangtua
        if ($user->role === 'orangtua') {
            return view('orangtua.anak.show', compact('balita', 'latestGrowth', 'growthTrend'));
        }

        return view('balita.show', compact('balita', 'latestGrowth', 'growthTrend'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Balita $balita)
    {
        Gate::authorize('update', $balita);

        $user = Auth::user();
        $posyandus = $this->getAvailablePosyandus($user);

        return view('balita.edit', compact('balita', 'posyandus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Balita $balita)
    {
        Gate::authorize('update', $balita);

        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:balitas,nik,'.$balita->id,
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:L,P',
            'mother_name' => 'required|string|max:255',
            'mother_nik' => 'required|string|size:16',
            'father_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'rt_rw' => 'nullable|string|max:20',
            'posyandu_id' => 'required|exists:posyandus,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:aktif,pindah,meninggal',
        ]);

        $balita->update($validated);

        return redirect()->route($this->getBalitaIndexRoute())
            ->with('success', 'Data balita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Balita $balita)
    {
        Gate::authorize('delete', $balita);

        $balita->delete();

        return redirect()->route($this->getBalitaIndexRoute())
            ->with('success', 'Data balita berhasil dihapus.');
    }

    /**
     * Get available posyandus based on user role
     */
    private function getAvailablePosyandus($user)
    {
        if ($user->role === 'admin_kota') {
            return Posyandu::all();
        } elseif ($user->role === 'admin_kecamatan') {
            return Posyandu::whereHas('kelurahan', function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            })->get();
        } elseif ($user->role === 'admin_kelurahan') {
            return Posyandu::where('kelurahan_id', $user->kelurahan_id)->get();
        } elseif ($user->role === 'kader') {
            return Posyandu::where('id', $user->posyandu_id)->get();
        }

        return collect();
    }

    /**
     * Get balita index route based on user role
     */
    private function getBalitaIndexRoute()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin_kota' => 'admin-kota.balita.index',
            'admin_kecamatan' => 'admin-kecamatan.balita.index',
            'admin_kelurahan' => 'admin-kelurahan.balita.index',
            'kader' => 'kader.balita.index',
            'orangtua' => 'orangtua.anak.index',
            default => 'balita.index',
        };
    }
}
