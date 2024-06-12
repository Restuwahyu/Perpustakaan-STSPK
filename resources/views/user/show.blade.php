@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List User</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List User</li>
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
                            <div class="mb-3 d-flex justify-content-between ">
                                <button type="button" class="btn btn-danger waves-effect waves-light"
                                    id="delete-selected" data-table-id="user_table" data-toggle="modal"
                                    data-target="#hapusModal">
                                    Hapus Data Terpilih
                                </button>
                            </div>
                        </div><!-- /.col -->


                        <table id="user_table" class="table table-bordered dt-responsive text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="select_names[]" id="select-all"></th>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" data-id="{{ $user->user_id }}">
                                        </td>
                                        <td style="cursor: pointer" data-item-id="{{ $user->user_id }}">
                                            {{ $user->user_kode }}</td>
                                        <td>
                                            {{ $user->user_nama }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($user->user_tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td class="text-left">{{ $user->user_email }}</td>
                                        <td class="text-left">{{ $user->role->role_nama }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <form action="{{ route('editUser') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id_user_edit"
                                                        value="{{ $user->user_id }}">
                                                    <button type="submit" class="btn btn-sm btn-warning mr-2">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('resetDefaultPasswordUser') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <a class="btn btn-sm btn-primary" data-toggle="modal"
                                                        data-target="#resetPasswordModal{{ $user->user_id }}">
                                                        <i class="fas fa-key" style="color: white"></i>
                                                    </a>

                                                    <!-- Reset Password Modal -->
                                                    <div class="modal fade" id="resetPasswordModal{{ $user->user_id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="resetPasswordModalLabel">
                                                                        Konfirmasi Reset Password
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-left">
                                                                    Apakah Anda Yakin Ingin Reset Password USER:
                                                                    {{ $user->user_nama }} Ini ?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Batal</button>
                                                                    <input type="hidden" name="id_user_reset"
                                                                        value="{{ $user->user_id }}">
                                                                    <button type="submit" class="btn btn-success"
                                                                        id="sa-success">Ya</button>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
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

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedDataUsers') }}" method="POST">
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
                    Apakah Anda Yakin Ingin Menghapus User Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_data_ids" id="selected-ids" value="">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>


@include('layouts.footer')
