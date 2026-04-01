<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PosyanduController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Posyandu::with('kelurahan.kecamatan');

        // Filter based on user role
        if ($user->role === 'admin_kecamatan') {
            $query->whereHas('kelurahan', function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        } elseif ($user->role === 'admin_kelurahan') {
            $query->where('kelurahan_id', $user->kelurahan_id);
        } elseif ($user->role === 'kader') {
            $query->where('id', $user->posyandu_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $posyandus = $query->orderBy('name')->paginate(15);

        return view('posyandu.index', compact('posyandus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Posyandu::class);

        $user = auth()->user();
        $kelurahans = $this->getAvailableKelurahans($user);

        return view('posyandu.create', compact('kelurahans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Posyandu::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:posyandus,code',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'address' => 'nullable|string',
            'jadwal_minggu_ke' => 'required|in:1,2,3,4',
            'jadwal_hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jadwal_jam_mulai' => 'required|date_format:H:i',
            'jadwal_jam_selesai' => 'required|date_format:H:i|after:jadwal_jam_mulai',
            'kader_koordinator_id' => 'nullable|exists:users,id',
        ]);

        Posyandu::create($validated);

        return redirect()->route('posyandu.index')
            ->with('success', 'Posyandu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Posyandu $posyandu)
    {
        Gate::authorize('view', $posyandu);

        $posyandu->load([
            'kelurahan.kecamatan',
            'kaderKoordinator',
            'balitas' => function ($q) {
                $q->where('status', 'aktif')->orderBy('name');
            },
            'users' => function ($q) {
                $q->where('role', 'kader');
            }
        ]);

        // Get statistics
        $totalBalita = $posyandu->balitas()->where('status', 'aktif')->count();
        $totalKader = $posyandu->users()->where('role', 'kader')->count();

        return view('posyandu.show', compact('posyandu', 'totalBalita', 'totalKader'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posyandu $posyandu)
    {
        Gate::authorize('update', $posyandu);

        $user = auth()->user();
        $kelurahans = $this->getAvailableKelurahans($user);
        $kaders = User::where('role', 'kader')
            ->where('kelurahan_id', $posyandu->kelurahan_id)
            ->get();

        return view('posyandu.edit', compact('posyandu', 'kelurahans', 'kaders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Posyandu $posyandu)
    {
        Gate::authorize('update', $posyandu);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:posyandus,code,' . $posyandu->id,
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'address' => 'nullable|string',
            'jadwal_minggu_ke' => 'required|in:1,2,3,4',
            'jadwal_hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jadwal_jam_mulai' => 'required|date_format:H:i',
            'jadwal_jam_selesai' => 'required|date_format:H:i|after:jadwal_jam_mulai',
            'kader_koordinator_id' => 'nullable|exists:users,id',
        ]);

        $posyandu->update($validated);

        return redirect()->route('posyandu.show', $posyandu)
            ->with('success', 'Data posyandu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posyandu $posyandu)
    {
        Gate::authorize('delete', $posyandu);

        $posyandu->delete();

        return redirect()->route('posyandu.index')
            ->with('success', 'Posyandu berhasil dihapus.');
    }

    /**
     * Get available kelurahans based on user role
     */
    private function getAvailableKelurahans($user)
    {
        if ($user->role === 'admin_kota') {
            return Kelurahan::with('kecamatan')->get();
        } elseif ($user->role === 'admin_kecamatan') {
            return Kelurahan::where('kecamatan_id', $user->kecamatan_id)
                ->with('kecamatan')
                ->get();
        } elseif ($user->role === 'admin_kelurahan') {
            return Kelurahan::where('id', $user->kelurahan_id)->get();
        }

        return collect();
    }
}
