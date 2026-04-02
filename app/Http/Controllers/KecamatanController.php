<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Kecamatan::query();

        // Filter based on user role
        if ($user->role === 'admin_kecamatan') {
            $query->where('id', $user->kecamatan_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $kecamatans = $query->orderBy('name')->paginate(15);

        return view('kecamatan.index', compact('kecamatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Kecamatan::class);

        return view('kecamatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Kecamatan::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:kecamatans,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        Kecamatan::create($validated);

        return redirect()->route('kecamatan.index')
            ->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kecamatan $kecamatan)
    {
        Gate::authorize('view', $kecamatan);

        $kecamatan->load(['kelurahans', 'puskesmas']);

        // Get statistics
        $totalKelurahan = $kecamatan->kelurahans()->count();
        $totalPosyandu = $kecamatan->kelurahans()->withCount('posyandus')->get()->sum('posyandus_count');
        $totalBalita = $kecamatan->kelurahans()
            ->whereHas('posyandus')
            ->withCount(['posyandus' => function ($q) {
                $q->withCount('balitas');
            }])
            ->get()
            ->sum(function ($kelurahan) {
                return $kelurahan->posyandus->sum('balitas_count');
            });

        return view('kecamatan.show', compact('kecamatan', 'totalKelurahan', 'totalPosyandu', 'totalBalita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kecamatan $kecamatan)
    {
        Gate::authorize('update', $kecamatan);

        return view('kecamatan.edit', compact('kecamatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        Gate::authorize('update', $kecamatan);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:kecamatans,code,'.$kecamatan->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $kecamatan->update($validated);

        return redirect()->route('kecamatan.show', $kecamatan)
            ->with('success', 'Data kecamatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kecamatan $kecamatan)
    {
        Gate::authorize('delete', $kecamatan);

        $kecamatan->delete();

        return redirect()->route('kecamatan.index')
            ->with('success', 'Kecamatan berhasil dihapus.');
    }
}
