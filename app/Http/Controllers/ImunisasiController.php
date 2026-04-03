<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\ImunisasiRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ImunisasiController extends Controller
{
    /**
     * Display a listing of the resource for nakes puskesmas.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Only nakes puskesmas can access this
        if ($user->role !== 'nakes_puskesmas') {
            abort(403, 'Unauthorized access');
        }

        $puskesmas = $user->puskesmas;

        // Get all imunisasi records in puskesmas area
        $query = ImunisasiRecord::whereHas('balita.posyandu.kelurahan', function ($q) use ($puskesmas) {
            $q->where('kecamatan_id', $puskesmas->kecamatan_id);
        })
            ->with(['balita.posyandu.kelurahan', 'inputBy'])
            ->orderBy('tanggal_diberikan', 'desc');

        // Filter
        if ($request->has('jenis_imunisasi')) {
            $query->where('jenis_imunisasi', $request->jenis_imunisasi);
        }

        if ($request->has('bulan')) {
            $query->whereMonth('tanggal_diberikan', $request->bulan);
        }

        $records = $query->paginate(20);

        return view('nakes.imunisasi.index', compact('records', 'puskesmas'));
    }

    /**
     * Display a listing of the resource for specific balita.
     */
    public function indexByBalita(Balita $balita)
    {
        $user = Auth::user();

        // Load balita with relationships for policy check
        $balita->load(['posyandu.kelurahan.kecamatan', 'orangtua']);

        if (! Gate::allows('view', $balita)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data imunisasi anak ini.');
        }

        // Load immunization records
        $records = ImunisasiRecord::where('balita_id', $balita->id)
            ->orderBy('tanggal_diberikan', 'desc')
            ->get();

        // Use different view for orangtua
        if ($user->role === 'orangtua') {
            return view('orangtua.anak.imunisasi.index', compact('balita', 'records'));
        }

        return view('imunisasi.index', compact('balita', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Balita $balita)
    {
        Gate::authorize('update', $balita);

        return view('imunisasi.create', compact('balita'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Balita $balita)
    {
        Gate::authorize('update', $balita);

        $validated = $request->validate([
            'jenis_imunisasi' => 'required|in:HB-0,BCG,Polio-1,Polio-2,Polio-3,Polio-4,DPT-HB-1,DPT-HB-2,DPT-HB-3,Campak,Campak-Rubella,JE,PCV,Rotavirus,MR',
            'tanggal_diberikan' => 'required|date|before_or_equal:today',
            'batch_number' => 'nullable|string|max:100',
            'lokasi' => 'required|in:Posyandu,Puskesmas,RS,Lainnya',
            'keterangan' => 'nullable|string',
        ]);

        $validated['input_by'] = Auth::id();

        ImunisasiRecord::create($validated);

        return redirect()->route('imunisasi.index', $balita)
            ->with('success', 'Data imunisasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ImunisasiRecord $record)
    {
        $record->load(['balita.posyandu', 'inputBy']);

        return view('imunisasi.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImunisasiRecord $record)
    {
        Gate::authorize('update', $record->balita);

        return view('imunisasi.edit', compact('record'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImunisasiRecord $record)
    {
        Gate::authorize('update', $record->balita);

        $validated = $request->validate([
            'jenis_imunisasi' => 'required|in:HB-0,BCG,Polio-1,Polio-2,Polio-3,Polio-4,DPT-HB-1,DPT-HB-2,DPT-HB-3,Campak,Campak-Rubella,JE,PCV,Rotavirus,MR',
            'tanggal_diberikan' => 'required|date|before_or_equal:today',
            'batch_number' => 'nullable|string|max:100',
            'lokasi' => 'required|in:Posyandu,Puskesmas,RS,Lainnya',
            'keterangan' => 'nullable|string',
        ]);

        $record->update($validated);

        return redirect()->route('imunisasi.index', $record->balita)
            ->with('success', 'Data imunisasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImunisasiRecord $record)
    {
        Gate::authorize('update', $record->balita);

        $balita = $record->balita;
        $record->delete();

        return redirect()->route('imunisasi.index', $balita)
            ->with('success', 'Data imunisasi berhasil dihapus.');
    }

    /**
     * Display imunisasi schedule/reminder
     */
    public function schedule(Balita $balita)
    {
        Gate::authorize('view', $balita);

        // Define standard immunization schedule
        $schedule = [
            ['jenis' => 'HB-0', 'age' => 0, 'age_label' => 'Saat lahir', 'done' => false],
            ['jenis' => 'BCG', 'age' => 0, 'age_label' => 'Saat lahir', 'done' => false],
            ['jenis' => 'Polio-1', 'age' => 0, 'age_label' => 'Saat lahir', 'done' => false],
            ['jenis' => 'Polio-2', 'age' => 1, 'age_label' => '1 bulan', 'done' => false],
            ['jenis' => 'Polio-3', 'age' => 2, 'age_label' => '2 bulan', 'done' => false],
            ['jenis' => 'Polio-4', 'age' => 3, 'age_label' => '3 bulan', 'done' => false],
            ['jenis' => 'DPT-HB-1', 'age' => 2, 'age_label' => '2 bulan', 'done' => false],
            ['jenis' => 'DPT-HB-2', 'age' => 3, 'age_label' => '3 bulan', 'done' => false],
            ['jenis' => 'DPT-HB-3', 'age' => 4, 'age_label' => '4 bulan', 'done' => false],
            ['jenis' => 'Campak', 'age' => 9, 'age_label' => '9 bulan', 'done' => false],
            ['jenis' => 'Campak-Rubella', 'age' => 18, 'age_label' => '18 bulan', 'done' => false],
        ];

        // Mark done immunizations
        $doneImunisasi = ImunisasiRecord::where('balita_id', $balita->id)
            ->pluck('jenis_imunisasi')
            ->toArray();

        foreach ($schedule as &$item) {
            $item['done'] = in_array($item['jenis'], $doneImunisasi);
            $item['due_date'] = $balita->birth_date->copy()->addMonths($item['age']);
            $item['is_overdue'] = ! $item['done'] && $item['due_date']->isPast();
        }

        return view('imunisasi.schedule', compact('balita', 'schedule'));
    }
}
