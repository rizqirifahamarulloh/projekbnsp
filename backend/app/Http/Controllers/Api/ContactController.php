<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Traits\ApiResponse;

/**
 * Controller untuk formulir kontak (publik)
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class ContactController extends Controller
{
    use ApiResponse;

    /** POST /api/contact — Kirim pesan kontak */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        return $this->successResponse(
            $contact,
            'Pesan berhasil dikirim. Terima kasih!',
            201
        );
    }
}
