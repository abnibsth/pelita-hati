<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Kelurahan::with('kecamatan');

        // Filter based on user role
        if ($user->role === 'admin_kecamatan') {
            $query->where('kecamatan_id', $user->kecamatan_id);
        } elseif ($user->role === 'admin_kelurahan') {
            $query->where('id', $user->kelurahan_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Filter by kecamatan
        if ($request->has('kecamatan_id') && $user->role === 'admin_kota') {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        $kelurahans = $query->orderBy('name')->paginate(15);

        return view('kelurahan.index', compact('kelurahans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Kelurahan::class);

        $kecamatans = $this->getAvailableKecamatans();

        return view('kelurahan.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Kelurahan::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:kelurahans,code',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        Kelurahan::create($validated);

        return redirect()->route('kelurahan.index')
            ->with('success', 'Kelurahan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelurahan $kelurahan)
    {
        Gate::authorize('view', $kelurahan);

        $kelurahan->load(['kecamatan', 'posyandus', 'users']);

        // Get statistics
        $totalPosyandu = $kelurahan->posyandus()->count();
        $totalBalita = $kelurahan->posyandus()->withCount('balitas as total_balita')
            ->get()
            ->sum('total_balita');
        $totalKader = $kelurahan->users()->where('role', 'kader')->count();

        return view('kelurahan.show', compact('kelurahan', 'totalPosyandu', 'totalBalita', 'totalKader'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelurahan $kelurahan)
    {
        Gate::authorize('update', $kelurahan);

        $kecamatans = $this->getAvailableKecamatans();

        return view('kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelurahan $kelurahan)
    {
        Gate::authorize('update', $kelurahan);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:kelurahans,code,'.$kelurahan->id,
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $kelurahan->update($validated);

        return redirect()->route('kelurahan.show', $kelurahan)
            ->with('success', 'Data kelurahan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelurahan $kelurahan)
    {
        Gate::authorize('delete', $kelurahan);

        $kelurahan->delete();

        return redirect()->route('kelurahan.index')
            ->with('success', 'Kelurahan berhasil dihapus.');
    }

    /**
     * Get available kecamatans based on user role
     */
    private function getAvailableKecamatans()
    {
        $user = auth()->user();

        if ($user->role === 'admin_kota') {
            return Kecamatan::all();
        } elseif ($user->role === 'admin_kecamatan') {
            return Kecamatan::where('id', $user->kecamatan_id)->get();
        }

        return collect();
    }
}
