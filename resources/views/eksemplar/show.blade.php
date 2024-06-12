@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Eksemplar</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Eksemplar</li>
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
                                {{-- <button type="button" class="btn btn-danger waves-effect waves-light mr-3"
                                    id="delete-selected" data-table-id="eksemplar_table" data-toggle="modal"
                                    data-target="#hapusModalEksemplar">
                                    Hapus Data Terpilih
                                </button> --}}

                                <button type="button" class="btn btn-success waves-effect waves-light ml-2"
                                    id="delete-selected" data-table-id="eksemplar_table" data-toggle="modal"
                                    data-target="#cetakModalEksemplar">
                                    <i class="fas fa-print"></i>
                                    Download Eksemplar
                                </button>
                            </div>
                        </div><!-- /.col -->


                        <table id="eksemplar_table" class="table table-bordered dt-responsive nowrap text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Kode</th>
                                    <th>Nomor Panggil</th>
                                    <th>Nomor Eksemplar</th>
                                    <th>Tipe Koleksi</th>
                                    <th>Status</th>
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

<!-- Cetak Modal -->
<form action="{{ route('printSelectedDataEksemplars') }}" method="POST" id="cetak-form">
    @csrf
    <div class="modal fade" id="cetakModalEksemplar" tabindex="-1" role="dialog"
        aria-labelledby="cetakModalEksemplarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cetakModalEksemplarLabel">
                        Konfirmasi Cetak
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Apakah Anda Yakin Ingin Mencetak Barcode Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <input type="hidden" name="selected_cetak_ids" id="selected-cetak-ids" value="">
                    <button type="submit" class="btn btn-success" id="cetak-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

<script type="text/javascript">
    $(document).ready(function() {
        var eksemplar_table = $('#eksemplar_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'eksemplar/json',
            columns: [{
                    name: 'eksemplar_id',
                    data: 'eksemplar_id',
                    render: function(data, type, full, meta) {
                        return '<input type="checkbox" class="checkbox" data-id="' +
                            data + '">';
                    }
                },
                {
                    name: 'eksemplar_kode',
                    data: 'eksemplar_kode',
                    width: '50px',
                    render: function(data, type, full, meta) {
                        return `
                            <div data-item-id="${full.eksemplar_id}" >
                                ${data}
                            </div>
                        `;
                    }
                },
                {
                    name: 'eksemplar_no_panggil',
                    data: 'eksemplar_no_panggil',
                    render: function(data, type, full, meta) {
                        return `
                        <div data-item-id="${full.eksemplar_id}" >
                            ${data}
                        </div>
                    `;
                    }
                },
                {
                    name: 'eksemplar_no_eksemplar',
                    data: 'eksemplar_no_eksemplar',
                    render: function(data, type, full, meta) {
                        return `
                        <div data-item-id="${full.eksemplar_id}" >
                            ${data}
                        </div>
                    `;
                    }
                },
                {
                    name: 'eksemplar_tipe_koleksi',
                    data: 'eksemplar_tipe_koleksi',
                    width: '80px',
                    render: function(data, type, full, meta) {
                        return `
                        <div data-item-id="${full.eksemplar_id}" >
                            ${data}
                        </div>
                    `;
                    }
                },
                {
                    name: 'eksemplar_status',
                    data: 'eksemplar_status',
                    orderable: false,
                    searchable: false,
                    width: '50px',
                    render: function(data, type, full, meta) {
                        var statusLabel = '';
                        if (data == 0) {
                            statusLabel =
                                '<span class="badge badge-danger">Tidak Tersedia</span>';
                        } else if (data == 1) {
                            statusLabel = '<span class="badge badge-success">Tersedia</span>';
                        } else if (data == 2) {
                            statusLabel =
                                '<span class="badge badge-danger">Sedang Dipinjam</span>';
                        } else if (data == 3) {
                            statusLabel =
                                '<span class="badge badge-warning">Sedang Dipesan</span>';
                        }

                        return statusLabel;
                    }
                },
            ],
            columnDefs: [{
                targets: 0,
                orderable: false,
                width: '10px',
            }],
            order: [
                [3, 'asc']
            ],
        });


        $('#eksemplar_table').on('click', 'div[data-item-id]', function() {
            const itemId = $(this).data("item-id");
            console.log("item_id:", itemId);
        });

        $(document).on('click', '[data-toggle="modal"][data-target^="#editEksemplarModal"]', function() {
            var eksemplarId = $(this).data('eksemplar-id');

            $('#eksemplar_id_edit').val(eksemplarId);
        });

        $(document).on('click', '[data-toggle="modal"][data-target="#hapusModalEksemplar"]', function() {
            var selectedIds = [];
            var tableId = $(this).data('table-id');

            var table = $('#' + tableId).DataTable();

            var selectedRowsData = table.rows({
                selected: true
            }).data().toArray();

            selectedIds = selectedRowsData.map(function(rowData) {
                return rowData[0];
            });

            $('#selected-ids').val(JSON.stringify(selectedIds));

            var dataIds = $('#' + tableId + ' .checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            console.log('Ids yang dipilih di ' + tableId + ':', dataIds);
        });

        $(document).on('click', '[data-toggle="modal"][data-target="#cetakModalEksemplar"]', function() {
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

            $('#selected-cetak-ids').val(JSON.stringify(selectedIds));

            var dataIds = $('#' + tableId + ' .checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            console.log('Ids yang dipilih di ' + tableId + ':', dataIds);
        });

        document.getElementById("cetak-selected-form").addEventListener("click", function() {
            $('#cetakModalEksemplar').modal('hide');
        });
    });
</script>

@include('layouts.footer')
