@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Edit User {{ $user->user_nama }}</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('showUser') }}">List User</a>
                            </li>
                            <li class="breadcrumb-item active">Edit User</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page-title -->

        <!-- start top-Contant -->
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-2 mb-2">
                            <button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="modal"
                                data-target="#tambahRoleModal">
                                <i class="fas fa-plus"></i> Tambah Role
                            </button>
                        </div>

                        <form action="{{ route('updateUser') }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="nama" class="col-sm-4 col-form-label">Nama*</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" name="user_nama"
                                            class="form-control @error('user_nama') is-invalid @enderror" id="nama"
                                            value="{{ $user->user_nama }}" required>
                                    </div>
                                    @error('user_nama')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label">Email*</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="email" name="user_email"
                                            class="form-control @error('user_email') is-invalid @enderror"
                                            id="email" value="{{ $user->user_email }}" required>
                                    </div>
                                    @error('user_email')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="datepicker" class="col-sm-4 col-form-label">Tanggal Lahir*</label>
                                <div class="col-sm-8">
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="date" name="user_tanggal_lahir"
                                            class="form-control @error('user_tanggal_lahir') is-invalid @enderror"
                                            value="{{ $user->user_tanggal_lahir }}" required />
                                    </div>
                                    @error('user_tanggal_lahir')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nama" class="col-sm-4 col-form-label">Role*
                                    <i class="mdi mdi-information ml-auto" data-toggle="popover"
                                        data-placement="top" data-html="true"
                                        data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                        data-content="<strong>Role User (Wajib Diisi)</strong><br>Jika tidak ada pada pilihan bisa menekan tombol <strong>(+Tambah Role)</strong>."
                                        data-trigger="hover">
                                    </i>  
                                </label>
                                <div class="col-sm-8">
                                    <select name="user_role"
                                        class="form-control @error('user_role') is-invalid @enderror select2"
                                        style="width: 100%" id="role" required>
                                        <option value="" selected disabled>Pilih Role</option>
                                        @foreach ($roles as $role)
                                            @if ($role->role_id == 1 || $role->role_id == 2)
                                                <option value="{{ $role->role_id }}"
                                                    @if ($user->user_role == $role->role_id) selected @endif>
                                                    {{ $role->role_nama }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('user_role')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('showUser') }}" class="btn btn-danger mr-2"
                                        style="color: white">Batal</a>
                                    </a>

                                    <a class="btn btn-success" data-toggle="modal" data-target="#editModal"
                                        style="color: white">
                                        Edit
                                    </a>
                                </div>
                            </div><!-- /.row -->

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Konfirmasi Edit</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div><!-- /.modal-header -->
                                        <div class="modal-body">
                                            Apakah Anda Yakin Ingin Mengubah USER: {{ $user->user_nama }} Ini ?
                                        </div><!-- /.modal-body -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Batal</button>

                                            <input type="hidden" name="id_user_edit" value="{{ $user->user_id }}">

                                            <button type="submit" class="btn btn-success">Ya</button>
                                        </div><!-- /.modal-footer -->
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </form>

                        <!-- Modal Tambah Role -->
                        <div class="modal fade" id="tambahRoleModal" tabindex="-1" role="dialog"
                            aria-labelledby="tambahRoleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tambahRoleModalLabel">Tambah Role
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('tambahRole') }}" method="POST" novalidate>
                                            @csrf
                                            @method('POST')
                                            <div class="form-group">
                                                <label>Nama Role
                                                    @error('role_nama')
                                                        <span class="text-danger">({{ $message }})</span>
                                                    @enderror
                                                </label>
                                                <input type="text" name="role_nama"
                                                    class="form-control @error('role_nama') is-invalid @enderror"
                                                    id="nama" id="nama" value="{{ old('role_nama') }}"
                                                    required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Batal</button>

                                                <input type="hidden" name="allowed" value="1">

                                                <button type="submit" class="btn btn-success"
                                                    id="simpanRoleBtn">Simpan</button>
                                            </div><!-- /.modal-footer -->
                                        </form>
                                    </div><!-- /.modal-body -->
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

@include('layouts.footer')
