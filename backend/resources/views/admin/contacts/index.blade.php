@extends('admin.layouts.app')

@section('title', 'Kotak Masuk Kontak')
@section('page-title', 'Kotak Masuk Kontak')
@section('breadcrumb')
    <li class="breadcrumb-item active">Kontak</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-envelope mr-1"></i> Pesan Masuk</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Pengirim</th>
                    <th>Subjek</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr class="{{ !$contact->is_read ? 'font-weight-bold' : '' }}">
                    <td>{{ $loop->iteration + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                    <td>
                        {{ $contact->name }}
                        <br><small class="text-muted">{{ $contact->email }}</small>
                    </td>
                    <td>{{ $contact->subject }}</td>
                    <td>{{ Str::limit($contact->message, 60) }}</td>
                    <td>
                        @if($contact->is_read)
                            <span class="badge badge-secondary"><i class="fas fa-check mr-1"></i>Dibaca</span>
                        @else
                            <span class="badge badge-warning"><i class="fas fa-envelope mr-1"></i>Baru</span>
                        @endif
                    </td>
                    <td>{{ $contact->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        {{-- Tombol untuk membaca pesan lengkap --}}
                        <button type="button" class="btn btn-info btn-xs btn-view-message"
                                data-name="{{ $contact->name }}"
                                data-email="{{ $contact->email }}"
                                data-subject="{{ $contact->subject }}"
                                data-message="{{ $contact->message }}"
                                data-date="{{ $contact->created_at->format('d M Y, H:i') }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if(!$contact->is_read)
                        <form action="{{ route('admin.contacts.markAsRead', $contact->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success btn-xs" title="Tandai Dibaca">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">Belum ada pesan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contacts->hasPages())
    <div class="card-footer clearfix">
        {{ $contacts->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Tampilkan pesan lengkap dalam modal SweetAlert2
document.querySelectorAll('.btn-view-message').forEach(btn => {
    btn.addEventListener('click', function() {
        Swal.fire({
            title: this.dataset.subject,
            html: `
                <div class="text-left">
                    <p><strong>Dari:</strong> ${this.dataset.name} (${this.dataset.email})</p>
                    <p><strong>Tanggal:</strong> ${this.dataset.date}</p>
                    <hr>
                    <p>${this.dataset.message}</p>
                </div>
            `,
            confirmButtonText: 'Tutup',
            width: '600px',
        });
    });
});
</script>
@endpush
