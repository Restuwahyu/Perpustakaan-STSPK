@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Penerbit</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Penerbit</li>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-danger waves-effect waves-light" id="delete-selected"
                                data-table-id="penerbit_table" data-toggle="modal" data-target="#hapusModalPenerbit">
                                Hapus Data Terpilih
                            </button>

                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#tambahPenerbitModal">
                                <i class="fas fa-user-plus"></i> Tambah Penerbit
                            </button>
                        </div>

                        <table id="penerbit_table" class="table table-bordered dt-responsive text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="select_names[]" id="select-all"></th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

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

<!-- Modal Tambah Penerbit -->
<form action="{{ route('tambahPenerbit') }}" method="POST">
    @csrf
    <div class="modal fade" id="tambahPenerbitModal" role="dialog" aria-labelledby="tambahPenerbitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPenerbitModalLabel">Tambah Penerbit
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Penerbit*
                            @error('penerbit_nama')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="penerbit_nama"
                            class="form-control @error('penerbit_nama') is-invalid @enderror" id="nama"
                            id="nama" value="{{ old('penerbit_nama') }}" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>



                        <button type="submit" class="btn btn-success" id="simpanPenerbitBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-body -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedPenerbits') }}" method="POST">
    @csrf
    <div class="modal fade" id="hapusModalPenerbit" tabindex="-1" role="dialog"
        aria-labelledby="hapusModalPenerbitLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalPenerbitLabel">
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Apakah Anda Yakin Ingin Menghapus Penerbit Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_penerbit_ids" id="selected-ids" value="">


                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

<!-- Modal Edit Penerbit -->
<div class="modal fade" id="editPenerbitModal" role="dialog" aria-labelledby="editPenerbitModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPenerbitModalLabel">
                    Edit Penerbit
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('updatePenerbit') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Penerbit*
                            @error('penerbit_nama')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="penerbit_nama"
                            class="form-control @error('penerbit_nama') is-invalid @enderror" id="penerbit_nama"
                            required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                        <input type="hidden" name="penerbit_id_edit" id="penerbit_id_edit">

                        <button type="submit" class="btn btn-success" id="simpanKotaBtn">Simpan</button>
                    </div>
                    <!-- /.modal-footer -->
                </div><!-- /.modal-body -->
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
    $(document).ready(function() {
        var penerbit_table = $('#penerbit_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'penerbit/json',
            columns: [{
                    name: 'penerbit_id',
                    data: 'penerbit_id',
                    render: function(data, type, full, meta) {
                        return '<input type="checkbox" class="checkbox" data-id="' +
                            data + '">';
                    }
                },
                {
                    name: 'penerbit_nama',
                    data: 'penerbit_nama',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="text-left" data-item-id="${full.penerbit_id}" style="cursor: pointer">
                                ${decodeURIComponent(escape(data))}
                            </div>
                        `;
                    }
                },
                {
                    name: 'aksi',
                    data: 'aksi',
                    orderable: false,
                    searchable: false,
                    width: '50px',
                    render: function(data, type, full, meta) {
                        return `
                            <button id="cek" type="button" class="btn btn-sm btn-warning"
                                data-toggle="modal"
                                data-target="#editPenerbitModal"
                                data-penerbit-id="${full.penerbit_id}"
                                data-penerbit-nama="${full.penerbit_nama}">
                                <i class="fas fa-edit"></i>
                            </button>
                            `;
                    }
                }
            ],
            columnDefs: [{
                targets: 0,
                orderable: false,
                width: '10px',
            }],
            order: [
                [1, 'asc']
            ],
        });

        $('#penerbit_table').on('click', 'div[data-item-id]', function() {
            const itemId = $(this).data("item-id");
            console.log("item_id:", itemId);
        });

        $(document).on('click', '[data-toggle="modal"][data-target^="#editPenerbitModal"]', function() {
            var penerbitId = $(this).data('penerbit-id');
            var penerbitNama = $(this).data('penerbit-nama');

            $('#penerbit_id_edit').val(penerbitId);
            $('#penerbit_nama').val(penerbitNama);
        });

        $(document).on('click', '[data-toggle="modal"][data-target="#hapusModalPenerbit"]', function() {
            var selectedIds = [];
            var tableId = $(this).data('table-id');

            var table = $('#' + tableId).DataTable();

            var selectedRowsData = table.rows({
                selected: true
            }).data().toArray();

            selectedIds = selectedRowsData.map(function(rowData) {
                return rowData[0];
            });

            $('#' + tableId + ' .checkbox:checked').each(function() {
                selectedIds.push($(this).data("id"));
            });

            $('#selected-ids').val(JSON.stringify(selectedIds));

            var dataIds = $('#' + tableId + ' .checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            console.log('Ids yang dipilih di ' + tableId + ':', dataIds);
        });
    });
</script>

@include('layouts.footer')
