@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Klasifikasi</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Klasifikasi</li>
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
                            <button type="button" class="btn btn-danger waves-effect waves-light" id="delete-selected"
                                data-table-id="klasifikasi_table" data-toggle="modal" data-target="#hapusModal">
                                Hapus Data Terpilih
                            </button>

                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#tambahKlasifikasiModal">
                                <i class="fas fa-user-plus"></i> Tambah Klasifikasi
                            </button>
                        </div><!-- /.col -->


                        <table id="klasifikasi_table" class="table table-bordered dt-responsive nowrap text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($klasifikasis as $klasifikasi)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox"
                                                data-id="{{ $klasifikasi->klasifikasi_id }}">
                                        </td>
                                        <td>{{ $klasifikasi->klasifikasi_kode }}</td>
                                        <td class="text-left" style="cursor: pointer"
                                            data-item-id="{{ $klasifikasi->klasifikasi_id }}">
                                            {{ $klasifikasi->klasifikasi_nama }}
                                        </td>
                                        <td>
                                            <div class="align-items-center">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    data-toggle="modal"
                                                    data-target="#editKlasifikasiModal{{ $klasifikasi->klasifikasi_id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Klasifikasi -->
                                    <div class="modal fade" id="editKlasifikasiModal{{ $klasifikasi->klasifikasi_id }}"
                                        tabindex="-1" role="dialog" aria-labelledby="editKlasifikasiModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editKlasifikasiModalLabel">
                                                        Edit Klasifikasi
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('updateKlasifikasi') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="id_edit" class="text-left">Kode Klasifikasi
                                                                @error('klasifikasi_kode')
                                                                    <span class="text-danger">({{ $message }})</span>
                                                                @enderror
                                                            </label>
                                                            <input type="text" name="klasifikasi_kode"
                                                                class="form-control @error('klasifikasi_kode') is-invalid @enderror"
                                                                id="id_edit"
                                                                value="{{ $klasifikasi->klasifikasi_kode }}" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="nama_edit" class="text-left">Nama
                                                                Klasifikasi
                                                                @error('klasifikasi_nama')
                                                                    <span class="text-danger">({{ $message }})</span>
                                                                @enderror
                                                            </label>
                                                            <input type="text" name="klasifikasi_nama"
                                                                class="form-control @error('klasifikasi_nama') is-invalid @enderror"
                                                                id="nama_edit" id="nama"
                                                                value="{{ $klasifikasi->klasifikasi_nama }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Batal</button>

                                                            <input type="hidden" name="klasifikasi_id_edit"
                                                                value="{{ $klasifikasi->klasifikasi_id }}">

                                                            <button type="submit" class="btn btn-success"
                                                                id="simpanKlasifikasiBtn">Simpan</button>
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

<!-- Modal Tambah Klasifikasi -->
<form action="{{ route('tambahKlasifikasi') }}" method="POST">
    @csrf
    <div class="modal fade" id="tambahKlasifikasiModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahKlasifikasiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKlasifikasiModalLabel">Tambah Klasifikasi
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Klasifikasi
                            @error('klasifikasi_kode')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="klasifikasi_kode"
                            class="form-control @error('klasifikasi_kode') is-invalid @enderror" id="kode"
                            value="{{ old('klasifikasi_kode') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Klasifikasi
                            @error('klasifikasi_nama')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="klasifikasi_nama"
                            class="form-control @error('klasifikasi_nama') is-invalid @enderror"
                            id="nama"value="{{ old('klasifikasi_nama') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>



                        <button type="submit" class="btn btn-success" id="simpanKlasifikasiBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-body -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedKlasifikasis') }}" method="POST">
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
                    Apakah Anda Yakin Ingin Menghapus Klasifikasi Ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_klasifikasi_ids" id="selected-ids" value="">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

@include('layouts.footer')
