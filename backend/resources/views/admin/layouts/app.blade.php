<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — BookWise Admin</title>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- AdminLTE 3.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- ═══ NAVBAR ═══ --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link text-muted">
                    <i class="fas fa-user-shield mr-1"></i> {{ Auth::user()->name }}
                </span>
            </li>
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-danger" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <i class="fas fa-book-open ml-3 mr-2" style="font-size:1.4rem;"></i>
            <span class="brand-text font-weight-bold">BookWise</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x text-light"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block text-light">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}"
                           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.books.index') }}"
                           class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Buku</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}"
                           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Pesanan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}"
                           class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>Kotak Masuk</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- ═══ CONTENT ═══ --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
            {{-- Flash messages dihandle via SweetAlert2 di bawah --}}

                @yield('content')
            </div>
        </section>
    </div>

    {{-- ═══ FOOTER ═══ --}}
    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} BookWise BNSP.</strong> All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ═══ SweetAlert2 Toast untuk Flash Messages ═══ --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
        didOpen: (toast) => { toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer; }
    });

    @if(session('success'))
        Toast.fire({ icon: 'success', title: @json(session('success')) });
    @endif

    @if(session('error'))
        Toast.fire({ icon: 'error', title: @json(session('error')) });
    @endif

    @if(session('info'))
        Toast.fire({ icon: 'info', title: @json(session('info')) });
    @endif

    // Konfirmasi hapus data — semua form dengan class .form-delete
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>

@stack('scripts')
</body>
</html>
