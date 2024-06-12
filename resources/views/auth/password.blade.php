<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Seminari Tinggi Kentungan</title>
    <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logoicon.ico') }}">

    <!-- TEMPLATE Zegva -->
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/metismenu.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/style.css') }}" rel="stylesheet" type="text/css">

</head>

<body>
    <div class="accountbg"></div>
    <div class="wrapper-page">

        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-pages shadow-none mt-4">
                        <div class="card-body">
                            <div class="text-center mt-0 mb-3">
                                <a href="{{ route('showListBuku') }}" class="logo">
                                    <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logodark.png') }}"
                                        class="logo-lg" alt="" height="80">
                                    <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logosm.png') }}"
                                        class="logo-sm" alt="" height="80" style="display: none">
                                </a>
                            </div>



                            <form method="POST" action="{{ route('gantiPasswords') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group row">
                                    <div class="col-12">
                                        @if (session('success'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close"></button>
                                                <strong>Berhasil !</strong> {{ session('success') }}
                                            </div>
                                        @elseif(session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close"></button>
                                                <strong>Gagal !</strong> {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>

                                    <label for="current_password" class="col-sm-4 col-form-label">Password Lama</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="password" id="current_password" name="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                style="border-right: none;" data-password-target
                                                value="{{ old('current_password', session('current_password')) }}"
                                                required>

                                            <div class="input-group-append">
                                                <span class="input-group-text" data-toggle="password-toggle"
                                                    style="background-color: white; border-left: none;">
                                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('current_password')
                                            <span class="text-danger">({{ $message }})</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="new_password" class="col-sm-4 col-form-label">Password Baru</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="password" id="new_password" name="new_password"
                                                data-password-target
                                                class="form-control @error('new_password') is-invalid @enderror"
                                                value="{{ old('new_password', session('new_password')) }}" required>
                                        </div>
                                        @error('new_password')
                                            <span class="text-danger">({{ $message }})</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-sm-4 col-form-label">Konfirmasi
                                        Password
                                        Baru</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="password" id="password_confirmation"
                                                name="password_confirmation" data-password-target
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                value="{{ old('password_confirmation', session('password_confirmation')) }}"
                                                required>
                                        </div>
                                        @error('password_confirmation')
                                            <span class="text-danger">({{ $message }})</span>
                                        @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <input type="hidden" name="id_user_reset" value="{{ session('login_id') }}">
                                    <input type="hidden" name="cek_modal" value="1">
                                    <button type="submit" class="btn btn-success">Ganti Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <!-- end top-Contant -->
        </div>
    </div>
</body>

<!-- jQuery  -->
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/metismenu.min.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/waves.min.js') }}"></script>

<script>
    $(".alert").delay(5000).fadeOut(400);

    var passwordToggles = document.querySelector('[data-toggle="password-toggle"]');

    passwordToggles.addEventListener('click', function() {
        // Mendapatkan target input password
        var targetInputs = document.querySelectorAll('[data-password-target]');

        // Mengubah tipe input dari password ke text atau sebaliknya untuk semua target input
        targetInputs.forEach(function(targetInput) {
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                passwordToggles.innerHTML =
                    '<i class="fa fa-eye" aria-hidden="true" data-toggle="password-toggle"></i>';
            } else {
                targetInput.type = 'password';
                passwordToggles.innerHTML =
                    '<i class="fa fa-eye-slash" aria-hidden="true" data-toggle="password-toggle"></i>';
            }
        });
    });
</script>

</body>

</html>
