<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;

/**
 * Controller Admin untuk daftar pengguna
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class AdminUserController extends Controller
{
    use ApiResponse;

    /** GET /api/admin/users */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            $users,
            UserResource::collection($users),
            'Daftar pengguna berhasil diambil'
        );
    }
}
