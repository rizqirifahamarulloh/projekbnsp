@extends('admin.layouts.app')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.books.index') }}">Buku</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Buku</h3>
            </div>
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id"
                                class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required>
                        @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="author">Penulis <span class="text-danger">*</span></label>
                                <input type="text" name="author" id="author"
                                       class="form-control @error('author') is-invalid @enderror"
                                       value="{{ old('author') }}" required>
                                @error('author') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="publisher">Penerbit <span class="text-danger">*</span></label>
                                <input type="text" name="publisher" id="publisher"
                                       class="form-control @error('publisher') is-invalid @enderror"
                                       value="{{ old('publisher') }}" required>
                                @error('publisher') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="year">Tahun Terbit <span class="text-danger">*</span></label>
                                <input type="number" name="year" id="year"
                                       class="form-control @error('year') is-invalid @enderror"
                                       value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') }}" required>
                                @error('year') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" min="0" required>
                                @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" id="stock"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', 0) }}" min="0" required>
                                @error('stock') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cover_image">Cover Buku</label>
                        <div class="custom-file">
                            <input type="file" name="cover_image" id="cover_image"
                                   class="custom-file-input @error('cover_image') is-invalid @enderror"
                                   accept="image/*" onchange="previewImage(event)">
                            <label class="custom-file-label" for="cover_image">Pilih gambar...</label>
                            @error('cover_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <img id="cover-preview" class="mt-2 d-none" style="max-height:200px; border-radius:8px;">
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  required>{{ old('description') }}</textarea>
                        @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview cover image sebelum upload
function previewImage(event) {
    const preview = document.getElementById('cover-preview');
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
        event.target.nextElementSibling.textContent = file.name;
    }
}
</script>
@endpush
