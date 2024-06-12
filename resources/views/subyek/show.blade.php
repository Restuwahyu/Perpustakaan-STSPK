@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Subyek</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Subyek</li>
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
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-danger waves-effect waves-light"
                                id="delete-selected" data-table-id="subyek_table" data-toggle="modal"
                                data-target="#hapusModal">
                                Hapus Data Terpilih
                            </button>

                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#tambahSubyekModal">
                                <i class="fas fa-user-plus"></i> Tambah Subyek
                            </button>
                        </div><!-- /.col -->


                        <table id="subyek_table" class="table table-bordered dt-responsive nowrap text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="selected_names[]" id="select-all"></th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($subyeks as $subyek)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" data-id="{{ $subyek->subyek_id }}">
                                        </td>
                                        <td class="text-left" style="cursor: pointer"
                                            data-item-id="{{ $subyek->subyek_id }}">
                                            {{ $subyek->subyek_nama }}</td>
                                        <td>
                                            <div class="align-items-center">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    data-toggle="modal"
                                                    data-target="#editSubyekModal{{ $subyek->subyek_id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Subyek -->
                                    <div class="modal fade" id="editSubyekModal{{ $subyek->subyek_id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editSubyekModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editSubyekModalLabel">
                                                        Edit Subyek
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('updateSubyek') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nama_edit" class="text-left">Nama
                                                                Subyek
                                                                @error('subyek_nama')
                                                                    <span class="text-danger">({{ $message }})</span>
                                                                @enderror
                                                            </label>
                                                            <input type="text" name="subyek_nama"
                                                                class="form-control @error('subyek_nama') is-invalid @enderror"
                                                                id="nama_edit" id="nama"
                                                                value="{{ $subyek->subyek_nama }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Batal</button>

                                                            <input type="hidden" name="subyek_id_edit"
                                                                value="{{ $subyek->subyek_id }}">

                                                            <button type="submit" class="btn btn-success"
                                                                id="simpanSubyekBtn">Simpan</button>
                                                        </div>
                                                        <!-- /.modal-footer -->
                                                    </div><!-- /.modal-body -->
                                                </form>
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

<!-- Modal Tambah Subyek -->
<form action="{{ route('tambahSubyek') }}" method="POST">
    @csrf
    <div class="modal fade" id="tambahSubyekModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahSubyekModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSubyekModalLabel">Tambah Subyek
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Subyek
                            @error('subyek_nama')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="subyek_nama"
                            class="form-control @error('subyek_nama') is-invalid @enderror" id="nama"
                            id="nama" value="{{ old('subyek_nama') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                        <button type="submit" class="btn btn-success" id="simpanSubyekBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-body -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedSubyeks') }}" method="POST">
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
                    Apakah Anda Yakin Ingin Menghapus Subyek Ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_subyek_ids" id="selected-ids" value="">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

@include('layouts.footer')
