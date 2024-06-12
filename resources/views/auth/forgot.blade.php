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
                <div class="col-lg-6">
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

                            <form action="{{ route('forgot') }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="col-12 text-center mt-4">
                                    <h4>Lupa Kata Sandi Anda?</h4>
                                </div>

                                <div class="form-group text-center">
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

                                        <label for="member_email">Silakan masukkan alamat email Anda untuk menerima
                                            email verifikasi.
                                            @error('member_email')
                                                <span class="text-danger">({{ $message }})</span>
                                            @enderror
                                        </label>
                                        <input type="email" id="email" name="member_email"
                                            class="form-control @error('member_email') is-invalid @enderror"
                                            placeholder="Masukkan Email Anda" value="{{ old('member_email') }}"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <div class="col-12 text-right">
                                        <a href="{{ route('login') }}" class="btn btn-danger waves-effect waves-light">
                                            Batal
                                        </a>

                                        <div style="display: inline-block;">
                                            <input type="hidden" name="register" value="1">
                                            <button class="btn btn-primary waves-effect waves-light"
                                                type="submit">Kirim</button>
                                        </div>
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

    // Dapatkan tombol mata
    var passwordToggles = document.querySelectorAll('[data-toggle="password-toggle"]');

    // Tambahkan event listener untuk mendengarkan klik pada tombol mata
    passwordToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            // Dapatkan target input password
            var targetId = this.getAttribute('data-target');
            var targetInput = document.getElementById(targetId);

            // Ubah tipe input dari password ke text atau sebaliknya
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                this.innerHTML =
                    '<i class="fa fa-eye-slash" aria-hidden="true" data-toggle="password-toggle" data-target="' +
                    targetId + '"></i>';
            } else if (targetInput.type === 'confirmation-password') {
                targetInput.type = 'text';
                this.innerHTML =
                    '<i class="fa fa-eye" aria-hidden="true" data-toggle="password-toggle" data-target="' +
                    targetId + '"></i>';
            } else {
                targetInput.type = 'password';
                this.innerHTML =
                    '<i class="fa fa-eye" aria-hidden="true" data-toggle="password-toggle" data-target="' +
                    targetId + '"></i>';
            }
        });
    });
</script>

</body>

</html>
