<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Posyandu;
use App\Models\Puskesmas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = User::with(['kelurahan.kecamatan', 'kecamatan', 'posyandu', 'puskesmas']);

        // Filter based on user role
        if ($user->role === 'admin_kecamatan') {
            $query->where(function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id)
                    ->orWhereHas('kelurahan', function ($q2) use ($user) {
                        $q2->where('kecamatan_id', $user->kecamatan_id);
                    });
            });
        } elseif ($user->role === 'admin_kelurahan') {
            $query->where('kelurahan_id', $user->kelurahan_id);
        }

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('nik', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->orderBy('name')->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', User::class);

        $user = auth()->user();
        $roles = $this->getAvailableRoles($user);
        $kecamatans = $this->getAvailableKecamatans($user);
        $kelurahans = $this->getAvailableKelurahans($user);
        $posyandus = $this->getAvailablePosyandus($user);
        $puskesmas = $this->getAvailablePuskesmas($user);

        return view('users.create', compact(
            'roles',
            'kecamatans',
            'kelurahans',
            'posyandus',
            'puskesmas'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin_kota,admin_kecamatan,admin_kelurahan,nakes_puskesmas,kader,orangtua',
            'nik' => 'required|string|size:16|unique:users,nik',
            'phone' => 'nullable|string|max:20',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'puskesmas_id' => 'nullable|exists:puskesmas,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        $user->load(['kelurahan.kecamatan', 'kecamatan', 'posyandu', 'puskesmas']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        $authUser = auth()->user();
        $roles = $this->getAvailableRoles($authUser);
        $kecamatans = $this->getAvailableKecamatans($authUser);
        $kelurahans = $this->getAvailableKelurahans($authUser);
        $posyandus = $this->getAvailablePosyandus($authUser);
        $puskesmas = $this->getAvailablePuskesmas($authUser);

        return view('users.edit', compact(
            'user',
            'roles',
            'kecamatans',
            'kelurahans',
            'posyandus',
            'puskesmas'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin_kota,admin_kecamatan,admin_kelurahan,nakes_puskesmas,kader,orangtua',
            'nik' => 'required|string|size:16|unique:users,nik,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'puskesmas_id' => 'nullable|exists:puskesmas,id',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Show profile edit form for authenticated user (especially for orangtua)
     */
    public function editProfile()
    {
        $user = auth()->user();

        return view('orangtua.profile.edit', compact('user'));
    }

    /**
     * Update profile for authenticated user (especially for orangtua)
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', 'min:8'],
            'nik' => 'required|string|size:16|unique:users,nik,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('orangtua.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Get available roles based on auth user role
     */
    private function getAvailableRoles($user): array
    {
        return match ($user->role) {
            'admin_kota' => ['admin_kecamatan', 'admin_kelurahan', 'nakes_puskesmas', 'kader', 'orangtua'],
            'admin_kecamatan' => ['admin_kelurahan', 'nakes_puskesmas', 'kader', 'orangtua'],
            'admin_kelurahan' => ['kader', 'orangtua'],
            default => [],
        };
    }

    /**
     * Get available kecamatans based on user role
     */
    private function getAvailableKecamatans($user)
    {
        if ($user->role === 'admin_kota') {
            return Kecamatan::all();
        } elseif ($user->role === 'admin_kecamatan') {
            return Kecamatan::where('id', $user->kecamatan_id)->get();
        }

        return collect();
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

    /**
     * Get available posyandus based on user role
     */
    private function getAvailablePosyandus($user)
    {
        if ($user->role === 'admin_kota') {
            return Posyandu::with('kelurahan.kecamatan')->get();
        } elseif ($user->role === 'admin_kecamatan') {
            return Posyandu::whereHas('kelurahan', function ($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            })->with('kelurahan.kecamatan')->get();
        } elseif ($user->role === 'admin_kelurahan') {
            return Posyandu::where('kelurahan_id', $user->kelurahan_id)
                ->with('kelurahan.kecamatan')
                ->get();
        } elseif ($user->role === 'kader') {
            return Posyandu::where('id', $user->posyandu_id)->get();
        }

        return collect();
    }

    /**
     * Get available puskesmas based on user role
     */
    private function getAvailablePuskesmas($user)
    {
        if ($user->role === 'admin_kota') {
            return Puskesmas::with('kecamatan')->get();
        } elseif ($user->role === 'admin_kecamatan') {
            return Puskesmas::where('kecamatan_id', $user->kecamatan_id)
                ->with('kecamatan')
                ->get();
        }

        return collect();
    }
}
