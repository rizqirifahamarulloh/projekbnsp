<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — BookWise Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h1 class="h4"><i class="fas fa-book-open mr-1"></i> <b>BookWise</b> Admin</h1>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Masuk ke panel administrasi</p>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email"
                           value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-0 text-muted text-center" style="font-size:0.8rem;">
                Demo: admin@bookwise.test / password
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
