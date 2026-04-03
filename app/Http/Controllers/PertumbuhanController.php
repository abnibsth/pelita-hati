<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\PertumbuhanRecord;
use App\Services\GiziCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PertumbuhanController extends Controller
{
    private GiziCalculator $giziCalculator;

    public function __construct(GiziCalculator $giziCalculator)
    {
        $this->giziCalculator = $giziCalculator;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Balita $balita)
    {
        Gate::authorize('view', $balita);

        $user = Auth::user();

        $records = PertumbuhanRecord::where('balita_id', $balita->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        // Use different view for orangtua
        if ($user->role === 'orangtua') {
            return view('orangtua.anak.pertumbuhan.index', compact('balita', 'records'));
        }

        return view('pertumbuhan.index', compact('balita', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Balita $balita)
    {
        Gate::authorize('update', $balita);

        return view('pertumbuhan.create', compact('balita'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Balita $balita)
    {
        Gate::authorize('update', $balita);

        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'berat_badan' => 'required|numeric|min:0|max:50',
            'tinggi_badan' => 'required|numeric|min:30|max:150',
            'lingkar_kepala' => 'nullable|numeric|min:20|max:80',
            'lingkar_lengan_atas' => 'nullable|numeric|min:5|max:50',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Use service to calculate Z-Scores and status gizi
        $result = $this->giziCalculator->savePertumbuhan(
            $balita,
            $validated['berat_badan'],
            $validated['tinggi_badan'],
            $user,
            $validated['catatan'] ?? null
        );

        // Add optional fields
        if (isset($validated['lingkar_kepala'])) {
            $result->lingkar_kepala = $validated['lingkar_kepala'];
        }
        if (isset($validated['lingkar_lengan_atas'])) {
            $result->lingkar_lengan_atas = $validated['lingkar_lengan_atas'];
        }
        $result->save();

        return redirect()->route($this->getBalitaShowRoute($balita))
            ->with('success', 'Data pertumbuhan berhasil ditambahkan. Status gizi: '.strtoupper($result->status_gizi));
    }

    /**
     * Display the specified resource.
     */
    public function show(PertumbuhanRecord $record)
    {
        $record->load(['balita.posyandu', 'kader']);

        return view('pertumbuhan.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PertumbuhanRecord $record)
    {
        Gate::authorize('update', $record->balita);

        return view('pertumbuhan.edit', compact('record'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PertumbuhanRecord $record)
    {
        Gate::authorize('update', $record->balita);

        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'berat_badan' => 'required|numeric|min:0|max:50',
            'tinggi_badan' => 'required|numeric|min:30|max:150',
            'lingkar_kepala' => 'nullable|numeric|min:20|max:80',
            'lingkar_lengan_atas' => 'nullable|numeric|min:5|max:50',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Recalculate Z-Scores and status gizi
        $result = $this->giziCalculator->calculate(
            $validated['berat_badan'],
            $validated['tinggi_badan'],
            $record->balita->birth_date->diffInMonths(now()),
            $record->balita->gender
        );

        $record->update([
            'tanggal' => $validated['tanggal'],
            'berat_badan' => $validated['berat_badan'],
            'tinggi_badan' => $validated['tinggi_badan'],
            'lingkar_kepala' => $validated['lingkar_kepala'] ?? null,
            'lingkar_lengan_atas' => $validated['lingkar_lengan_atas'] ?? null,
            'z_score_bbu' => $result['z_score_bbu'],
            'z_score_tbu' => $result['z_score_tbu'],
            'z_score_bbtb' => $result['z_score_bbtb'],
            'status_gizi' => $result['status_gizi'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route($this->getBalitaShowRoute($record->balita))
            ->with('success', 'Data pertumbuhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PertumbuhanRecord $record)
    {
        Gate::authorize('update', $record->balita);

        $balita = $record->balita;
        $record->delete();

        return redirect()->route($this->getBalitaShowRoute($balita))
            ->with('success', 'Data pertumbuhan berhasil dihapus.');
    }

    /**
     * Get balita show route based on user role
     */
    private function getBalitaShowRoute($balita)
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin_kota' => 'admin-kota.balita.show',
            'admin_kecamatan' => 'admin-kecamatan.balita.show',
            'admin_kelurahan' => 'admin-kelurahan.balita.show',
            'kader' => 'kader.balita.show',
            'orangtua' => 'orangtua.anak.show',
            default => 'balita.show',
        };
    }
}
