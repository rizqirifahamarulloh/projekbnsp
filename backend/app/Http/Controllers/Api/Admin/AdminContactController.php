<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Traits\ApiResponse;

/**
 * Controller Admin untuk kotak masuk kontak
 * [BNSP: Membuat Kode Program Aplikasi]
 */
class AdminContactController extends Controller
{
    use ApiResponse;

    /** GET /api/admin/contacts — Daftar semua pesan kontak */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            $contacts,
            ContactResource::collection($contacts),
            'Daftar pesan kontak berhasil diambil'
        );
    }

    /** PATCH /api/admin/contacts/{id}/read — Tandai pesan sudah dibaca */
    public function markAsRead(int $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]);

        return $this->successResponse(
            new ContactResource($contact),
            'Pesan berhasil ditandai sudah dibaca'
        );
    }
}
