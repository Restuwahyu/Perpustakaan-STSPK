@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Buku</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Buku</li>
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
                                <button type="button" class="btn btn-danger waves-effect waves-light mr-2"
                                    id="delete-selected" data-table-id="buku_table" data-toggle="modal"
                                    data-target="#hapusModalBuku">
                                    Hapus Data Terpilih
                                </button>
                            </div>
                        </div><!-- /.col -->

                        <table id="buku_table" class="table table-bordered dt-responsive"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th><input type="checkbox" name="select_names[]" id="select-all"></th>
                                    <th>Judul</th>
                                    {{-- <th>Klasifikasi</th> --}}
                                    <th>Eksemplar</th>
                                    <th>Pengarang</th>
                                    <th>Tahun Terbit</th>
                                    <th>Penerbit</th>
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

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedBukus') }}" method="POST">
    @csrf
    <div class="modal fade" id="hapusModalBuku" tabindex="-1" role="dialog" aria-labelledby="hapusModalBukuLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalBukuLabel">
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Apakah Anda Yakin Ingin Menghapus Buku Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_buku_ids" id="selected-ids" value="">
                    <input type="hidden" name="allowed" value="1">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

<script type="text/javascript">
    $(document).ready(function() {
        function imageExists(imageUrl) {
            var http = new XMLHttpRequest();
            http.open('HEAD', imageUrl, false);
            http.send();
            return http.status != 404;
        }
        $('#pencarian-pengarang').on('keyup', function() {
            var searchTerm = $(this).val();

            buku_table.search(searchTerm).draw();
        });

        var buku_table = $('#buku_table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: 'buku/json',
                dataSrc: 'data',
            },
            columns: [{
                    name: 'buku_id',
                    data: 'buku_id',
                    render: function(data, type, full, meta) {
                        return '<input type="checkbox" class="checkbox" data-id="' +
                            data + '">';
                    }
                },
                {
                    name: 'buku_judul',
                    data: 'buku_judul',
                    render: function(data, type, row) {
                        const coverImageUrl = row.buku_cover_compressed;

                        let media = '<div class="media clickable-book" display: flex;">';

                        if (!coverImageUrl || !imageExists(coverImageUrl)) {
                            media +=
                                '<img id="previewImage" src="{{ asset('storage/images/preview.png') }}" width="85" height="120" alt="Preview Image" style="border-radius: 5px; margin-right: 10px;">';
                        } else {
                            media += '<img src="' + coverImageUrl +
                                '" width="85" height="120" class="img img-responsive" style="max-height: 120px; border-radius: 5px; margin-right: 10px;">';
                        }

                        media += '<div class="media-body text-left">';
                        media += '<div class="title" style="font-weight: bold;">' +
                            decodeURIComponent(escape(row.buku_judul)).substring(0, 130);
                        if (row.buku_judul.length > 130) {
                            media += '...';
                        }
                        media += '</div>';

                        media +=
                            '<div class="klasifikasi"><small class="text-muted" style="font-size: 13px;">' +
                            row.klasifikasi.klasifikasi_nama + '</small></div>';

                        media +=
                            '<div class="no_panggil"><small class="text-muted" style="font-size: 12px;">';
                        if (row.eksemplars && row.eksemplars.length > 0) {
                            for (const eksemplar of row.eksemplars) {
                                media += decodeURIComponent(escape(eksemplar
                                    .eksemplar_no_eksemplar +
                                    '; '));
                            }
                            media = media.slice(0, -2);
                        }
                        media += '</small></div>';

                        media +=
                            '<div class="pengarang"><small class="text-muted" style="font-size: 12px;">';

                        if (row.pengarangs && row.pengarangs.length > 0) {
                            for (const penulis of row.pengarangs) {
                                media += decodeURIComponent(escape(penulis.pengarang_nama +
                                    '; '));
                            }
                            media = media.slice(0, -2);
                        }

                        media += '</small></div>';
                        media +=
                            '<div class="penerbit"><small class="text-muted" style="font-size: 12px;">';
                        if (row.penerbit.penerbit_nama) {
                            media += decodeURIComponent(escape(row.penerbit
                                .penerbit_nama));
                        }
                        media += '</small></div>';
                        media +=
                            '<div class="tahun_terbit"><small class="text-muted" style="font-size: 12px;">' +
                            row.buku_tahun_terbit + '</small></div>';
                        media +=
                            '<div class="user"><small class="text-muted" style="font-size: 12px;">Dimasukkan oleh: ';
                        if (row.users && row.users.length > 0) {
                            for (const user of row.users) {
                                media += decodeURIComponent(escape(user.user_nama));
                            }
                        }
                        media += '</small></div>';
                        media += '</div></div>';
                        return media;
                    }
                },
                // {
                //     name: 'klasifikasi.klasifikasi_nama',
                //     data: 'klasifikasi.klasifikasi_nama',
                //     visible: false,
                // },
                {
                    name: 'eksemplars.eksemplar_no_eksemplar',
                    data: 'eksemplars.eksemplar_no_eksemplar',
                    visible: false,
                    render: function(data, type, row) {
                        var authorNames = '';
                        for (var i = 0; i < row.eksemplars.length; i++) {
                            if (i > 0) {
                                authorNames += ', ';
                            }
                            authorNames += row.eksemplars[i].eksemplar_no_eksemplar;
                        }
                        return authorNames;
                    }
                },
                {
                    name: 'pengarangs.pengarang_nama',
                    data: 'pengarangs.pengarang_nama',
                    visible: false,
                    render: function(data, type, row) {
                        var authorNames = '';
                        for (var i = 0; i < row.pengarangs.length; i++) {
                            if (i > 0) {
                                authorNames += ', ';
                            }
                            authorNames += row.pengarangs[i].pengarang_nama;
                        }
                        return authorNames;
                    }
                },
                {
                    name: 'buku_tahun_terbit',
                    data: 'buku_tahun_terbit',
                    visible: false,
                },
                {
                    name: 'penerbit.penerbit_nama',
                    data: 'penerbit.penerbit_nama',
                    visible: false,
                }, {
                    name: 'aksi',
                    data: 'aksi',
                    orderable: false,
                    searchable: false,
                    width: '50px',
                    render: function(data, type, full, meta) {
                        return `
                            <form class="text-center" action="{{ route('editBuku') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_buku_edit"
                                    value="${full.buku_id}">
                                <button type="submit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
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

        $('#buku_table').on('click', '.clickable-book', function() {
            var itemId = $(this).data('item-id');
            console.log('item_id:', itemId);
        });

        $(document).on('click', '[data-toggle="modal"][data-target^="#editBukuModal"]', function() {
            var bukuId = $(this).data('buku-id');
            var bukuNama = $(this).data('buku-nama');

            $('#buku_id_edit').val(bukuId);
            $('#buku_nama').val(bukuNama);
        });


        $(document).on('click', '[data-toggle="modal"][data-target="#hapusModalBuku"]', function() {
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
