@extends('admin.layouts.app')

@section('title', 'Daftar Pengguna')
@section('page-title', 'Daftar Pengguna')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna Terdaftar</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="60">Avatar</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar"
                                 class="img-circle" style="width:35px; height:35px; object-fit:cover;">
                        @else
                            <div class="bg-secondary img-circle d-flex align-items-center justify-content-center"
                                 style="width:35px; height:35px;">
                                <i class="fas fa-user text-white" style="font-size:0.8rem;"></i>
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge badge-danger"><i class="fas fa-shield-alt mr-1"></i>Admin</span>
                        @else
                            <span class="badge badge-primary"><i class="fas fa-user mr-1"></i>User</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">Belum ada pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer clearfix">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
