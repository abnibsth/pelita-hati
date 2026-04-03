<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImunisasiController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\PertumbuhanController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Forgot password routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Home redirect based on role
    Route::get('/', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin_kota' => redirect()->route('admin-kota.dashboard'),
            'admin_kecamatan' => redirect()->route('admin-kecamatan.dashboard'),
            'admin_kelurahan' => redirect()->route('admin-kelurahan.dashboard'),
            'nakes_puskesmas' => redirect()->route('nakes.dashboard'),
            'kader' => redirect()->route('kader.dashboard'),
            'orangtua' => redirect()->route('orangtua.dashboard'),
            default => redirect()->route('login'),
        };
    })->name('home');

    // =========================================================================
    // ADMIN KOTA (Dinkes Jakarta) - Full Access to ALL Data
    // =========================================================================
    Route::middleware('role:admin_kota')->prefix('admin-kota')->name('admin-kota.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminKota'])->name('dashboard');
        
        // Data Wilayah (Full CRUD)
        Route::resource('kecamatan', KecamatanController::class);
        Route::resource('kelurahan', KelurahanController::class);
        Route::resource('posyandu', PosyanduController::class);
        
        // User Management (all roles below admin_kota)
        Route::resource('users', UserController::class);
        
        // Data Balita (View All Jakarta)
        Route::get('/balita', [BalitaController::class, 'index'])->name('balita.index');
        Route::get('/balita/create', [BalitaController::class, 'create'])->name('balita.create');
        Route::post('/balita', [BalitaController::class, 'store'])->name('balita.store');
        Route::get('/balita/{balita}', [BalitaController::class, 'show'])->name('balita.show');
        Route::get('/balita/{balita}/pertumbuhan', [PertumbuhanController::class, 'index'])->name('pertumbuhan.index');
        Route::get('/pertumbuhan/{record}', [PertumbuhanController::class, 'show'])->name('pertumbuhan.show');
        Route::get('/imunisasi/{record}', [ImunisasiController::class, 'show'])->name('imunisasi.show');
        
        // Reports & Analytics
        Route::get('/reports/skdn', [DashboardController::class, 'adminKota'])->name('reports.skdn');
        Route::get('/reports/stunting', [DashboardController::class, 'adminKota'])->name('reports.stunting');
    });

    // =========================================================================
    // ADMIN KECAMATAN - Access to Their Kecamatan Only
    // =========================================================================
    Route::middleware('role:admin_kecamatan')->prefix('admin-kecamatan')->name('admin-kecamatan.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminKecamatan'])->name('dashboard');
        
        // Data Wilayah (CRUD for kelurahan in their kecamatan)
        Route::resource('kelurahan', KelurahanController::class);
        Route::resource('posyandu', PosyanduController::class);
        
        // User Management (kader, admin_kelurahan, nakes in their area)
        Route::resource('users', UserController::class);
        
        // Data Balita (View all in their kecamatan)
        Route::get('/balita', [BalitaController::class, 'index'])->name('balita.index');
        Route::get('/balita/create', [BalitaController::class, 'create'])->name('balita.create');
        Route::post('/balita', [BalitaController::class, 'store'])->name('balita.store');
        Route::get('/balita/{balita}', [BalitaController::class, 'show'])->name('balita.show');
        Route::get('/balita/{balita}/pertumbuhan', [PertumbuhanController::class, 'index'])->name('pertumbuhan.index');
        Route::get('/pertumbuhan/{record}', [PertumbuhanController::class, 'show'])->name('pertumbuhan.show');
        Route::get('/imunisasi/{record}', [ImunisasiController::class, 'show'])->name('imunisasi.show');
        
        // Reports
        Route::get('/reports/skdn', [DashboardController::class, 'adminKecamatan'])->name('reports.skdn');
        Route::get('/reports/stunting', [DashboardController::class, 'adminKecamatan'])->name('reports.stunting');
    });

    // =========================================================================
    // ADMIN KELURAHAN - Access to Their Kelurahan Only
    // =========================================================================
    Route::middleware('role:admin_kelurahan')->prefix('admin-kelurahan')->name('admin-kelurahan.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminKelurahan'])->name('dashboard');
        
        // View Data Wilayah (read-only for their kelurahan)
        Route::get('/kelurahan', [KelurahanController::class, 'index'])->name('kelurahan.index');
        Route::get('/kelurahan/{kelurahan}', [KelurahanController::class, 'show'])->name('kelurahan.show');
        Route::get('/posyandu', [PosyanduController::class, 'index'])->name('posyandu.index');
        Route::get('/posyandu/{posyandu}', [PosyanduController::class, 'show'])->name('posyandu.show');
        
        // User Management (kader in their kelurahan)
        Route::resource('users', UserController::class);
        
        // Data Balita (View all in their kelurahan)
        Route::get('/balita', [BalitaController::class, 'index'])->name('balita.index');
        Route::get('/balita/create', [BalitaController::class, 'create'])->name('balita.create');
        Route::post('/balita', [BalitaController::class, 'store'])->name('balita.store');
        Route::get('/balita/{balita}', [BalitaController::class, 'show'])->name('balita.show');
        Route::get('/balita/{balita}/pertumbuhan', [PertumbuhanController::class, 'index'])->name('pertumbuhan.index');
        Route::get('/pertumbuhan/{record}', [PertumbuhanController::class, 'show'])->name('pertumbuhan.show');
        Route::get('/imunisasi/{record}', [ImunisasiController::class, 'show'])->name('imunisasi.show');
        
        // Reports
        Route::get('/reports/skdn', [DashboardController::class, 'adminKelurahan'])->name('reports.skdn');
    });

    // =========================================================================
    // NAKES PUSKESMAS - Health Data Access
    // =========================================================================
    Route::middleware('role:nakes_puskesmas')->prefix('nakes')->name('nakes.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'nakes'])->name('dashboard');
        
        // Rujukan Management (placeholder for now)
        Route::get('/rujukan', function() { return view('dashboards.nakes'); })->name('rujukan.index');
        
        // Imunisasi Input (from puskesmas)
        Route::get('/imunisasi', [ImunisasiController::class, 'index'])->name('imunisasi.index');
        Route::get('/imunisasi/create', [ImunisasiController::class, 'create'])->name('imunisasi.create');
        Route::post('/imunisasi', [ImunisasiController::class, 'store'])->name('imunisasi.store');
        Route::get('/imunisasi/{record}', [ImunisasiController::class, 'show'])->name('imunisasi.show');
        Route::get('/imunisasi/{record}/edit', [ImunisasiController::class, 'edit'])->name('imunisasi.edit');
        Route::put('/imunisasi/{record}', [ImunisasiController::class, 'update'])->name('imunisasi.update');
        
        // Data Balita (View in their puskesmas area for health monitoring)
        Route::get('/balita', [BalitaController::class, 'index'])->name('balita.index');
        Route::get('/balita/{balita}', [BalitaController::class, 'show'])->name('balita.show');
        Route::get('/balita/{balita}/pertumbuhan', [PertumbuhanController::class, 'index'])->name('pertumbuhan.index');
        
        // Reports
        Route::get('/reports/gizi-buruk', [DashboardController::class, 'nakes'])->name('reports.gizi-buruk');
        Route::get('/reports/imunisasi', [ImunisasiController::class, 'index'])->name('reports.imunisasi');
    });

    // =========================================================================
    // KADER POSYANDU - Access to Their Posyandu Only
    // =========================================================================
    Route::middleware('role:kader')->prefix('kader')->name('kader.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'kader'])->name('dashboard');
        
        // Posyandu Profile (their posyandu only)
        Route::get('/posyandu', [PosyanduController::class, 'showMyPosyandu'])->name('posyandu.show');
        Route::get('/posyandu/edit', [PosyanduController::class, 'editMyPosyandu'])->name('posyandu.edit');
        Route::put('/posyandu', [PosyanduController::class, 'updateMyPosyandu'])->name('posyandu.update');
        
        // Data Balita (CRUD for their posyandu)
        Route::get('/balita', [BalitaController::class, 'index'])->name('balita.index');
        Route::get('/balita/create', [BalitaController::class, 'create'])->name('balita.create');
        Route::post('/balita', [BalitaController::class, 'store'])->name('balita.store');
        Route::get('/balita/{balita}', [BalitaController::class, 'show'])->name('balita.show');
        Route::get('/balita/{balita}/edit', [BalitaController::class, 'edit'])->name('balita.edit');
        Route::put('/balita/{balita}', [BalitaController::class, 'update'])->name('balita.update');
        Route::delete('/balita/{balita}', [BalitaController::class, 'destroy'])->name('balita.destroy');
        
        // Pertumbuhan Records
        Route::get('/balita/{balita}/pertumbuhan', [PertumbuhanController::class, 'index'])->name('pertumbuhan.index');
        Route::get('/balita/{balita}/pertumbuhan/create', [PertumbuhanController::class, 'create'])->name('pertumbuhan.create');
        Route::post('/balita/{balita}/pertumbuhan', [PertumbuhanController::class, 'store'])->name('pertumbuhan.store');
        Route::get('/pertumbuhan/{record}', [PertumbuhanController::class, 'show'])->name('pertumbuhan.show');
        Route::get('/pertumbuhan/{record}/edit', [PertumbuhanController::class, 'edit'])->name('pertumbuhan.edit');
        Route::put('/pertumbuhan/{record}', [PertumbuhanController::class, 'update'])->name('pertumbuhan.update');
        Route::delete('/pertumbuhan/{record}', [PertumbuhanController::class, 'destroy'])->name('pertumbuhan.destroy');
        
        // Imunisasi Records
        Route::get('/balita/{balita}/imunisasi', [ImunisasiController::class, 'index'])->name('imunisasi.index');
        Route::get('/balita/{balita}/imunisasi/create', [ImunisasiController::class, 'create'])->name('imunisasi.create');
        Route::post('/balita/{balita}/imunisasi', [ImunisasiController::class, 'store'])->name('imunisasi.store');
        Route::get('/imunisasi/{record}', [ImunisasiController::class, 'show'])->name('imunisasi.show');

        // Kehadiran (Attendance)
        Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
        Route::post('/kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');

        // Reports
        Route::get('/reports/skdn', [DashboardController::class, 'kader'])->name('reports.skdn');
    });

    // =========================================================================
    // ORANGTUA - Access to Their Own Children Only
    // =========================================================================
    Route::middleware('role:orangtua')->prefix('orangtua')->name('orangtua.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'orangtua'])->name('dashboard');
        
        // View Their Children
        Route::get('/anak', [BalitaController::class, 'index'])->name('anak.index');
        Route::get('/anak/{balita}', [BalitaController::class, 'show'])->name('anak.show');
        Route::get('/anak/{balita}/pertumbuhan', [PertumbuhanController::class, 'index'])->name('anak.pertumbuhan.index');
        Route::get('/anak/{balita}/imunisasi', [ImunisasiController::class, 'index'])->name('anak.imunisasi.index');
        
        // Profile
        Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    });

    // =========================================================================
    // GLOBAL ROUTES (Accessible by all authenticated users with proper policies)
    // =========================================================================
    // Note: These are fallback routes. Role-specific routes above should be preferred.
    Route::resource('balita', BalitaController::class)->except(['index', 'show', 'destroy']);
    Route::resource('balita.pertumbuhan', PertumbuhanController::class)->except(['index', 'show']);
    Route::resource('balita.imunisasi', ImunisasiController::class)->except(['show']);
    Route::resource('posyandu', PosyanduController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
});
