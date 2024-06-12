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
                <div class="col-lg-5">
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

                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="form-group">
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

                                        <label for="email">E-mail
                                            @error('email')
                                                <span class="text-danger">({{ $message }})</span>
                                            @enderror
                                        </label>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Masukkan Email Anda" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-12">
                                        <label for="password">Password
                                            @error('password')
                                                <span class="text-danger">({{ $message }})</span>
                                            @enderror
                                        </label>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan Password Anda" value="{{ old('password') }}"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-12">
                                        <div class="checkbox checkbox-primary">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1"> Ingat saya
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-3">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-block waves-effect waves-light"
                                            type="submit">Masuk</button>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-4">
                                    <div class="col-12">
                                        <div class="float-left">
                                            <a href="{{ route('forgot') }}" class="text-muted"><i
                                                    class="fa fa-lock mr-1"></i> Lupa kata sandi Anda?</a>
                                        </div>
                                        <div class="text-right">
                                            <a href="{{ route('register') }}" class="text-muted">Daftar</a>
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

    document.getElementById("customCheck1").addEventListener("change", function() {
        if (this.checked) {
            // Jika dicentang, simpan email ke Local Storage
            var email = document.getElementById("email").value;
            localStorage.setItem("rememberedEmail", email);
        } else {
            // Jika tidak dicentang, hapus email dari Local Storage
            localStorage.removeItem("rememberedEmail");
        }
    });

    // Cek apakah ada email yang harus diingat saat halaman dimuat
    var rememberedEmail = localStorage.getItem("rememberedEmail");
    if (rememberedEmail) {
        document.getElementById("email").value = rememberedEmail;
    }

    document.querySelectorAll('[data-toggle="password-toggle"]').forEach(function(element) {
        element.addEventListener('click', function() {
            // Mendapatkan target input password
            var targetId = this.getAttribute('data-target');
            var targetInput = document.querySelector(targetId);

            // Mengubah tipe input dari password ke text atau sebaliknya
            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                this.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
            } else {
                targetInput.type = 'password';
                this.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
            }
        });
    });
</script>

</body>

</html>
