<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Puskesmas;
use App\Models\Rujukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NakesRujukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $puskesmas = $user->puskesmas;

        $rujukan = Rujukan::where('puskesmas_id', $puskesmas->id)
            ->with(['balita.posyandu.kelurahan', 'nakes'])
            ->orderBy('tanggal_rujuk', 'desc')
            ->paginate(15);

        return view('nakes.rujukan.index', compact('rujukan', 'puskesmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $puskesmas = $user->puskesmas;

        // Get all balitas in puskesmas area
        $balitas = Balita::whereHas('posyandu.kelurahan', function ($q) use ($puskesmas) {
            $q->where('kecamatan_id', $puskesmas->kecamatan_id);
        })
            ->where('status', 'aktif')
            ->with(['posyandu.kelurahan'])
            ->orderBy('name')
            ->get();

        return view('nakes.rujukan.create', compact('balitas', 'puskesmas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $puskesmas = $user->puskesmas;

        $validated = $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'tanggal_rujuk' => 'required|date|before_or_equal:today',
            'jenis_keluhan' => 'required|string',
            'status_gizi' => 'required|in:normal,kurang,lebih,gizi_buruk,stunting',
            'tindak_lanjut' => 'nullable|string',
            'status' => 'required|in:dirujuk,diteruskan',
        ]);

        $validated['puskesmas_id'] = $puskesmas->id;
        $validated['nakes_id'] = $user->id;

        Rujukan::create($validated);

        return redirect()->route('nakes.rujukan.index')
            ->with('success', 'Rujukan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rujukan $rujukan)
    {
        $user = Auth::user();
        $puskesmas = $user->puskesmas;

        // Authorize
        if ($rujukan->puskesmas_id !== $puskesmas->id) {
            abort(403, 'Unauthorized');
        }

        $rujukan->load(['balita.posyandu.kelurahan', 'nakes']);

        return view('nakes.rujukan.show', compact('rujukan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rujukan $rujukan)
    {
        $user = Auth::user();
        $puskesmas = $user->puskesmas;

        // Authorize
        if ($rujukan->puskesmas_id !== $puskesmas->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:dirujuk,diteruskan,selesai',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $rujukan->update($validated);

        return redirect()->route('nakes.rujukan.show', $rujukan)
            ->with('success', 'Status rujukan berhasil diupdate.');
    }
}
