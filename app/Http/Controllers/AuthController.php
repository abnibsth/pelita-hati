<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on role
            return redirect()->intended($this->getRedirectRoute($user->role));
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Get redirect route based on user role
     */
    private function getRedirectRoute(string $role): string
    {
        return match ($role) {
            'admin_kota' => route('admin-kota.dashboard'),
            'admin_kecamatan' => route('admin-kecamatan.dashboard'),
            'admin_kelurahan' => route('admin-kelurahan.dashboard'),
            'nakes_puskesmas' => route('nakes.dashboard'),
            'kader' => route('kader.dashboard'),
            'orangtua' => route('orangtua.dashboard'),
            default => route('home'),
        };
    }
}
