@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Bahasa</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Bahasa</li>
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
                            <div class="mb-3 d-flex">
                                <button type="button" class="btn btn-danger waves-effect waves-light mr-3"
                                    id="delete-selected" data-table-id="bahasa_table" data-toggle="modal"
                                    data-target="#hapusModal">
                                    Hapus Data Terpilih
                                </button>

                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#tambahBahasaModal">
                                    <i class="fas fa-user-plus"></i> Tambah Bahasa
                                </button>
                            </div>
                        </div><!-- /.col -->


                        <table id="bahasa_table" class="table table-bordered dt-responsive nowrap text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($bahasas as $bahasa)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" data-id="{{ $bahasa->bahasa_id }}">
                                        </td>
                                        <td class="text-left" style="cursor: pointer"
                                            data-item-id="{{ $bahasa->bahasa_id }}">
                                            {{ $bahasa->bahasa_nama }}
                                        </td>
                                        <td>
                                            <div class="align-items-center">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    data-toggle="modal"
                                                    data-target="#editBahasaModal{{ $bahasa->bahasa_id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Bahasa -->
                                    <div class="modal fade" id="editBahasaModal{{ $bahasa->bahasa_id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editBahasaModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editBahasaModalLabel">
                                                        Edit Bahasa
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('updateBahasa') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nama_edit" class="text-left">Nama
                                                                Bahasa
                                                                @error('bahasa_nama')
                                                                    <span class="text-danger">({{ $message }})</span>
                                                                @enderror
                                                            </label>
                                                            <input type="text" name="bahasa_nama"
                                                                class="form-control @error('bahasa_nama') is-invalid @enderror"
                                                                id="nama_edit" id="nama"
                                                                value="{{ $bahasa->bahasa_nama }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Batal</button>

                                                            <input type="hidden" name="bahasa_id_edit"
                                                                value="{{ $bahasa->bahasa_id }}">

                                                            <button type="submit" class="btn btn-success"
                                                                id="simpanBahasaBtn">Simpan</button>
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

<!-- Modal Tambah Bahasa -->
<form action="{{ route('tambahBahasa') }}" method="POST">
    @csrf
    <div class="modal fade" id="tambahBahasaModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahBahasaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahBahasaModalLabel">Tambah Bahasa
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Bahasa
                            @error('bahasa_nama')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="bahasa_nama"
                            class="form-control @error('bahasa_nama') is-invalid @enderror" id="nama"
                            id="nama" value="{{ old('bahasa_nama') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                        <button type="submit" class="btn btn-success" id="simpanBahasaBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-body -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedBahasas') }}" method="POST">
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
                    Apakah Anda Yakin Ingin Menghapus Bahasa Ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_bahasa_ids" id="selected-ids" value="">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

<script></script>


@include('layouts.footer')
