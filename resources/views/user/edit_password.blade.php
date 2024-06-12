@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Ganti Password {{ session('user')['user_nama'] }}</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Ganti Password</li>
                        </ol>
                    </div>
                </div>

                <div class="col-md-8">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                            <strong>Berhasil !</strong> {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                            <strong>Gagal !</strong> {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- end page-title -->

        <!-- start top-Contant -->
        <div class="row">
            <div class="col-12 col-sm-8">
                <div class="card">
                    <div class="card-body">

                        <form method="POST" action="{{ route('gantiPassword') }}" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="current_password" class="col-sm-4 col-form-label">Password Lama </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="password" id="current_password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            value="{{ old('current_password') }}" required>

                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="password-toggle"
                                                data-target="#current_password"
                                                style="background-color: white;  border: none; ">
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
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            value="{{ old('new_password') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="password-toggle"
                                                data-target="#new_password"
                                                style="background-color: white;  border: none; ">
                                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('new_password')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-sm-4 col-form-label">Konfirmasi Password
                                    Baru</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            value="{{ old('password_confirmation') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="password-toggle"
                                                data-target="#password_confirmation"
                                                style="background-color: white;  border: none; ">
                                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                    </label>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="{{ route('home') }}" class="btn btn-danger mr-2"
                                    style="color: white">Batal</a>

                                <a class="btn btn-success" data-toggle="modal"
                                    data-target="#gantiPasswordModal{{ session('user')['user_id'] }}"
                                    style="color: white">
                                    Ganti Password
                                </a>
                            </div>

                            <!-- Reset Password Modal -->
                            <div class="modal fade" id="gantiPasswordModal{{ session('user')['user_id'] }}"
                                tabindex="-1" role="dialog" aria-labelledby="gantiPasswordModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="gantiPasswordModalLabel">
                                                Konfirmasi Ganti Password
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda Yakin Ingin Mengubah Password Anda ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Batal</button>

                                            <input type="hidden" name="allowed" value="1">

                                            <input type="hidden" name="id_user_reset"
                                                value="{{ session('user')['user_id'] }}">

                                            <button type="submit" class="btn btn-success">Ya</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

<script>
    // Convert Password menjadi Text
    document.querySelectorAll('[data-toggle="password-toggle"]').forEach(function(element) {
        element.addEventListener('click', function() {
            var targetId = this.getAttribute('data-target');
            var targetInput = document.querySelector(targetId);

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

@include('layouts.footer')
