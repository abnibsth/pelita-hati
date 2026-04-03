<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Posyandu;
use App\Services\GiziCalculator;
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
            });
        } elseif ($user->role === 'admin_kecamatan') {
            $query->whereHas('posyandu.kelurahan', function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        } elseif ($user->role === 'orangtua') {
            $query->where('user_id', $user->id);
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
