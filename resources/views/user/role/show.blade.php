@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Role User</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Role User</li>
                        </ol>
                    </div>
                </div>

                <div class="col-md-12">
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="mb-3 d-flex ">
                                <button type="button" class="btn btn-danger waves-effect waves-light mr-3"
                                    id="delete-selected" data-table-id="role_table" data-toggle="modal"
                                    data-target="#hapusModal">
                                    Hapus Data Terpilih
                                </button>

                                <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                    data-target="#tambahRoleModal">
                                    <i class="fas fa-user-plus"></i> Tambah Role
                                </button>
                            </div>
                        </div><!-- /.col -->


                        <table id="role_table" class="table table-bordered dt-responsive"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" data-id="{{ $role->role_id }}">
                                        </td>
                                        <td class="text-left" style="cursor: pointer"
                                            data-item-id="{{ $role->role_id }}">
                                            {{ $role->role_nama }}
                                        </td>
                                        <td class="text-center">
                                            <div class="align-items-center">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    data-toggle="modal"
                                                    data-target="#editRoleModal{{ $role->role_id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Role -->

                                    <div class="modal fade error-modal" id="editRoleModal{{ $role->role_id }}"
                                        tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editRoleModalLabel">
                                                        Edit Role
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('updateUserRole') }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label class="text-left">Nama Role
                                                                @error('role_nama')
                                                                    <span class="text-danger">({{ $message }})</span>
                                                                @enderror
                                                            </label>
                                                            <input type="text" name="role_nama"
                                                                class="form-control @error('role_nama') is-invalid @enderror"
                                                                id="role_nama" value="{{ $role->role_nama }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Batal</button>

                                                            <input type="hidden" name="allowed" value="1">

                                                            <input type="hidden" name="role_id_edit"
                                                                value="{{ $role->role_id }}">

                                                            <button type="submit" class="btn btn-success"
                                                                id="simpanRoleBtn">Simpan</button>
                                                        </div><!-- /.modal-footer -->
                                                    </form>
                                                </div><!-- /.modal-body -->
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

<!-- Modal Tambah Role -->
<form action="{{ route('tambahUserRole') }}" method="POST">
    @csrf
    <div class="modal fade error-modal" id="tambahRoleModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahRoleModalLabel">Tambah Role
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Role
                            @error('role_nama')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="role_nama"
                            class="form-control @error('role_nama') is-invalid @enderror" id="nama"
                            id="nama" value="{{ old('role_nama') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                        <input type="hidden" name="allowed" value="1">

                        <button type="submit" class="btn btn-success" id="simpanRoleBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-body -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedUserRoles') }}" method="POST">
    @csrf
    <div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel">
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Apakah Anda Yakin Ingin Menghapus Role User Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_role_ids" id="selected-ids" value="">
                    <input type="hidden" name="allowed" value="2">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

@include('layouts.footer')
