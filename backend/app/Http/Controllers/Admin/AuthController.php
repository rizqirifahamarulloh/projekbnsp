<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller Auth Admin (web — terpisah dari auth API)
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class AuthController extends Controller
{
    /** Tampilkan halaman login admin */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /** Proses login admin */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Pastikan yang login adalah admin
            if (!Auth::user()->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki akses admin.',
                ])->onlyInput('email');
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '! 👋');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /** Logout admin */
    public function logout(Request $request)
    {
        // Hapus semua Sanctum token milik user (agar frontend juga ter-logout)
        if (Auth::user()) {
            Auth::user()->tokens()->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman beranda frontend dengan query param logout
        return redirect(config('app.frontend_url', 'http://localhost:5173') . '?logout=admin');
    }

    /**
     * Auto-login dari React frontend → Admin Panel
     * Menerima Sanctum token, buat web session, redirect ke dashboard
     */
    public function autoLogin(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')->withErrors(['email' => 'Token tidak ditemukan.']);
        }

        // Cari token di tabel personal_access_tokens
        $hashedToken = hash('sha256', $token);
        $accessToken = \Laravel\Sanctum\PersonalAccessToken::where('token', $hashedToken)->first();

        if (!$accessToken) {
            return redirect()->route('login')->withErrors(['email' => 'Token tidak valid.']);
        }

        $user = $accessToken->tokenable;

        // Pastikan user adalah admin
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->withErrors(['email' => 'Akun ini bukan admin.']);
        }

        // Login via web session (tanpa perlu email/password lagi)
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Selamat datang kembali, ' . $user->name . '! 👋');
    }
}
