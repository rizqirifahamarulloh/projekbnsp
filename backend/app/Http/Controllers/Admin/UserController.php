<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

/**
 * Controller Admin Daftar Pengguna (web)
 * [BNSP: Membuat Antarmuka Pengguna]
 */
class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }
}
