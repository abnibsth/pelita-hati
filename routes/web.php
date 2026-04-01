<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\PertumbuhanController;
use App\Http\Controllers\ImunisasiController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
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

    // Admin Kota Routes
    Route::middleware('role:admin_kota')->prefix('admin-kota')->name('admin-kota.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminKota'])->name('dashboard');
    });

    // Admin Kecamatan Routes
    Route::middleware('role:admin_kecamatan')->prefix('admin-kecamatan')->name('admin-kecamatan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminKecamatan'])->name('dashboard');
    });

    // Admin Kelurahan Routes
    Route::middleware('role:admin_kelurahan')->prefix('admin-kelurahan')->name('admin-kelurahan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminKelurahan'])->name('dashboard');
    });

    // Nakes Puskesmas Routes
    Route::middleware('role:nakes_puskesmas')->prefix('nakes')->name('nakes.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'nakes'])->name('dashboard');
    });

    // Kader Routes
    Route::middleware('role:kader')->prefix('kader')->name('kader.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kader'])->name('dashboard');
    });

    // Orangtua Routes
    Route::middleware('role:orangtua')->prefix('orangtua')->name('orangtua.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'orangtua'])->name('dashboard');
    });

    // Balita Routes (accessible by all authenticated users based on policy)
    Route::resource('balita', BalitaController::class);
    
    // Pertumbuhan Routes (nested under balita)
    Route::resource('balita.pertumbuhan', PertumbuhanController::class)->except(['index', 'show']);
    Route::get('balita/{balita}/pertumbuhan', [PertumbuhanController::class, 'index'])->name('pertumbuhan.index');
    Route::get('pertumbuhan/{record}', [PertumbuhanController::class, 'show'])->name('pertumbuhan.show');

    // Imunisasi Routes
    Route::resource('balita.imunisasi', ImunisasiController::class)->except(['show']);
    Route::get('imunisasi/{record}', [ImunisasiController::class, 'show'])->name('imunisasi.show');
    Route::get('balita/{balita}/imunisasi/schedule', [ImunisasiController::class, 'schedule'])->name('imunisasi.schedule');

    // Posyandu Routes
    Route::resource('posyandu', PosyanduController::class);

    // User Management Routes
    Route::resource('users', UserController::class);
});
