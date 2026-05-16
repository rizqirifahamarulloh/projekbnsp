<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk autentikasi pengguna (register, login, logout)
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class AuthController extends Controller
{
    use ApiResponse;

    /** POST /api/register */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Buat token untuk akses API
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->successResponse([
            'user'  => new UserResource($user),
            'token' => $token,
        ], 'Registrasi berhasil', 201);
    }

    /** POST /api/login */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Email atau password salah.', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->successResponse([
            'user'  => new UserResource($user),
            'token' => $token,
        ], 'Login berhasil');
    }

    /** POST /api/logout */
    public function logout(Request $request)
    {
        // Hapus token saat ini
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil');
    }

    /** GET /api/user */
    public function user(Request $request)
    {
        return $this->successResponse(
            new UserResource($request->user()),
            'Data user berhasil diambil'
        );
    }
}
