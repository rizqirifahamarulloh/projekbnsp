@extends('admin.layouts.app')

@section('title', 'Manajemen Buku')
@section('page-title', 'Manajemen Buku')
@section('breadcrumb')
    <li class="breadcrumb-item active">Buku</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Buku</h3>
        <div class="card-tools">
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Buku
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="70">Cover</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>{{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}</td>
                    <td>
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="cover"
                                 class="img-fluid" style="width:50px; height:65px; object-fit:cover; border-radius:4px;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center"
                                 style="width:50px; height:65px; border-radius:4px;">
                                <i class="fas fa-image text-white"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ Str::limit($book->title, 30) }}</strong>
                        <br><small class="text-muted">{{ $book->publisher }}, {{ $book->year }}</small>
                    </td>
                    <td><span class="badge badge-info">{{ $book->category->name ?? '-' }}</span></td>
                    <td>{{ $book->author }}</td>
                    <td>Rp {{ number_format($book->price, 0, ',', '.') }}</td>
                    <td>
                        @if($book->stock > 10)
                            <span class="badge badge-success">{{ $book->stock }}</span>
                        @elseif($book->stock > 0)
                            <span class="badge badge-warning">{{ $book->stock }}</span>
                        @else
                            <span class="badge badge-danger">Habis</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger btn-xs btn-delete"
                                data-id="{{ $book->id }}" data-name="{{ $book->title }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $book->id }}"
                              action="{{ route('admin.books.destroy', $book) }}"
                              method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">Belum ada buku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($books->hasPages())
    <div class="card-footer clearfix">
        {{ $books->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        Swal.fire({
            title: 'Hapus Buku?',
            text: `Buku "${name}" akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endpush
