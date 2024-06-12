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
                <div class="col-lg-10">
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

                            <form action="{{ route('register') }}" method="POST" novalidate>
                                @csrf
                                @method('POST')
                                <div class="row">
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
                                    <!-- Kolom 1 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama">Nama*
                                                @error('member_nama')
                                                    <span class="text-danger">({{ $message }})</span>
                                                @enderror
                                            </label>
                                            <input type="nama" id="nama" name="member_nama"
                                                class="form-control @error('member_nama') is-invalid @enderror"
                                                placeholder="Masukkan Nama Anda" value="{{ old('member_nama') }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="member_tanggal_lahir">Tanggal Lahir*
                                                @error('member_tanggal_lahir')
                                                    <span class="text-danger">({{ $message }})</span>
                                                @enderror
                                            </label>
                                            <input type="date" name="member_tanggal_lahir"
                                                class="form-control @error('member_tanggal_lahir') is-invalid @enderror"
                                                value="{{ old('member_tanggal_lahir') }}"
                                                data-date-format:="dd/mm/yyyy" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="member_notelp">Nomor Telepon*
                                                @error('member_notelp')
                                                    <span class="text-danger">({{ $message }})</span>
                                                @enderror
                                            </label>
                                            <input type="text" name="member_notelp"
                                                class="form-control @error('member_notelp') is-invalid @enderror"
                                                placeholder="Masukkan Nomor Telepon Anda" pattern="[0-9]+"
                                                id="notelp" value="{{ old('member_notelp') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="member_alamat">Alamat*
                                                @error('member_alamat')
                                                    <span class="text-danger">({{ $message }})</span>
                                                @enderror
                                            </label>
                                            <textarea type="text" name="member_alamat" class="form-control @error('member_alamat') is-invalid @enderror"
                                                placeholder="Masukkan Alamat Anda" id="alamat" @error('member_alamat') is-invalid @enderror>{{ old('member_alamat') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Kolom 2 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="member_email">E-mail*
                                                @error('member_email')
                                                    <span class="text-danger">({{ $message }})</span>
                                                @enderror
                                            </label>
                                            <input type="email" id="email" name="member_email"
                                                class="form-control @error('member_email') is-invalid @enderror"
                                                placeholder="Masukkan Email Anda" value="{{ old('member_email') }}"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="member_password">Password*
                                                @error('member_password')
                                                    <span class="text-danger">({{ $message }})</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <input type="password" id="password" name="member_password"
                                                    class="form-control @error('member_password') is-invalid @enderror"
                                                    placeholder="Masukkan Password Anda"
                                                    value="{{ old('member_password') }}" required>

                                                <div class="input-group-append">
                                                    <span class="input-group-text"
                                                        data-toggle="password-toggle"data-target="password"
                                                        style="background-color: white; border-left: none;">
                                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirmation_password">Konfirmasi Password*
                                                @error('confirmation_password')
                                                    <span class="text-danger">({{ $message }})</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <input type="password" id="confirmation_password"
                                                    name="confirmation_password"
                                                    class="form-control @error('confirmation_password') is-invalid @enderror"
                                                    placeholder="Konfirmasi Password Anda"
                                                    value="{{ old('confirmation_password') }}" required>

                                                <div class="input-group-append">
                                                    <span class="input-group-text"
                                                        data-toggle="password-toggle"data-target="confirmation_password"
                                                        style="background-color: white; border-left: none;">
                                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-3">
                                    <div class="col-12">
                                        <input type="hidden" name="register" value="1">
                                        <button class="btn btn-primary btn-block waves-effect waves-light"
                                            type="submit">Daftar</button>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-4">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <a class="text-muted">Sudah Punya Akun?</a>
                                            <a href="{{ route('login') }}">Masuk</a>
                                        </div>
                                    </div>
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

    var passwordToggles = document.querySelectorAll('[data-toggle="password-toggle"]');

    passwordToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            var targetId = this.getAttribute('data-target');
            var targetInput = document.getElementById(targetId);

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                this.innerHTML =
                    '<i class="fa fa-eye" aria-hidden="true" data-toggle="password-toggle" data-target="' +
                    targetId + '"></i>';
            } else if (targetInput.type === 'confirmation-password') {
                targetInput.type = 'text';
                this.innerHTML =
                    '<i class="fa fa-eye" aria-hidden="true" data-toggle="password-toggle" data-target="' +
                    targetId + '"></i>';
            } else {
                targetInput.type = 'password';
                this.innerHTML =
                    '<i class="fa fa-eye-slash" aria-hidden="true" data-toggle="password-toggle" data-target="' +
                    targetId + '"></i>';
            }
        });
    });
</script>

</body>

</html>
