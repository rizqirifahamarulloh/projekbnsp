<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

/**
 * Controller Admin Kotak Masuk Kontak (web)
 * [BNSP: Membuat Antarmuka Pengguna]
 */
class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function markAsRead(int $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Pesan berhasil ditandai sudah dibaca!');
    }
}
