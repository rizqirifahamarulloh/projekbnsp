@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Kategori: {{ $category->name }}</h3>
            </div>
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Perbarui
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
