@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')
@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori</h3>
        <div class="card-tools">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Kategori
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Jumlah Buku</th>
                    <th>Deskripsi</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td><span class="badge badge-info">{{ $category->books_count }}</span></td>
                    <td>{{ Str::limit($category->description, 50) }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger btn-xs btn-delete"
                                data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $category->id }}"
                              action="{{ route('admin.categories.destroy', $category) }}"
                              method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="card-footer clearfix">
        {{ $categories->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Konfirmasi hapus dengan SweetAlert2
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        Swal.fire({
            title: 'Hapus Kategori?',
            text: `Kategori "${name}" akan dihapus secara permanen.`,
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
