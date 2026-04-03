<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Only kader can access this - check if user has posyandu
        if (!$user->posyandu) {
            abort(403, 'Unauthorized access.');
        }

        $posyandu = $user->posyandu;

        // Get all active balitas in this posyandu
        $balitas = Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        // Get kehadiran summary for current month
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
            ->get()
            ->map(function ($item) {
                $item->persentase = $item->total > 0 
                    ? ($item->total_hadir / $item->total) * 100 
                    : 0;
                return $item;
            });

        // Calculate statistics
        $totalBalita = $balitas->count();
        
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

        // Count frequency of posyandu this month (unique dates)
        $frekuensiPosyandu = Kehadiran::whereHas('balita', function ($q) use ($posyandu) {
                $q->where('posyandu_id', $posyandu->id);
            })
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->distinct('tanggal')
            ->count('tanggal');

        return view('kehadiran.index', compact(
            'balitas',
            'kehadiranSummary',
            'totalBalita',
            'avgKehadiran',
            'frekuensiPosyandu'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only kader can access this - check if user has posyandu
        if (!$user->posyandu) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'posyandu_id' => 'required|exists:posyandus,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'hadir' => 'nullable|array',
        ]);

        $posyandu = $user->posyandu;
        $tanggal = $validated['tanggal'];
        $hadirData = $validated['hadir'] ?? [];

        // Get all active balitas for this posyandu
        $allBalitas = Balita::where('posyandu_id', $posyandu->id)
            ->where('status', 'aktif')
            ->pluck('id');

        // Delete existing kehadiran for this date
        Kehadiran::where('tanggal', $tanggal)
            ->whereHas('balita', function ($q) use ($posyandu) {
                $q->where('posyandu_id', $posyandu->id);
            })
            ->delete();

        // Create new kehadiran records
        $kehadiranRecords = [];
        foreach ($allBalitas as $balitaId) {
            $kehadiranRecords[] = [
                'balita_id' => $balitaId,
                'posyandu_id' => $posyandu->id,
                'tanggal' => $tanggal,
                'hadir' => isset($hadirData[$balitaId]) ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Kehadiran::insert($kehadiranRecords);

        return redirect()->route('kader.kehadiran.index')
            ->with('success', 'Kehadiran berhasil dicatat.');
    }
}
