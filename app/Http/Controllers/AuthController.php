<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form for Orang Tua
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show registration form for Orang Tua
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request for Orang Tua
     */
    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // removed confirmed requirement as instructed in plan
        ]);

        $user = User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'orangtua',
            'is_active' => true,
        ]);

        // Auto-link existing Balita records to this new Orangtua account based on NIK
        \App\Models\Balita::where('mother_nik', $user->nik)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->intended($this->getRedirectRoute('orangtua'))->with('success', 'Pendaftaran berhasil. Selamat datang di SiPosyandu!');
    }

    /**
     * Show login form for Petugas/Admin
     */
    public function showAdminLogin()
    {
        return view('auth.admin-login');
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
            $user = Auth::user();

            // Strict portal checks
            if ($request->is('login') && $user->role !== 'orangtua') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun Petugas tidak bisa masuk di sini. Silakan masuk melalui Portal Petugas.',
                ]);
            }

            if ($request->is('petugas/login') && $user->role === 'orangtua') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun Orang Tua tidak bisa masuk di sini. Silakan masuk melalui Portal Orang Tua.',
                ]);
            }

            $request->session()->regenerate();

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
        $role = Auth::user()?->role;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role && $role !== 'orangtua') {
            return redirect()->route('admin.login');
        }

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

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        }

        throw ValidationException::withMessages([
            'email' => 'Email tidak ditemukan dalam sistem kami.',
        ]);
    }

    /**
     * Show reset password form
     */
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        throw ValidationException::withMessages([
            'email' => 'Token reset password tidak valid atau sudah kadaluarsa.',
        ]);
    }
}
