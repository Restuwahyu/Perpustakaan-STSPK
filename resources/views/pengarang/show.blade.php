@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Pengarang</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Pengarang</li>
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
                                data-table-id="pengarang_table" data-toggle="modal" data-target="#hapusModalPengarang">
                                Hapus Data Terpilih
                            </button>

                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#tambahPengarangModal">
                                <i class="fas fa-user-plus"></i> Tambah Pengarang
                            </button>
                        </div><!-- /.col -->

                        <form id="pengarangForm" action="{{ route('showPeranPengarang') }}" method="POST">
                            @csrf
                            <input type="hidden" id="selectedPengarangId" name="pengarang_id">
                        </form>

                        <table id="pengarang_table" class="table table-bordered dt-responsive nowrap text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="select_names[]" id="select-all">
                                    </th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
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

<!-- Modal Tambah Pengarang -->
<div class="modal fade" id="tambahPengarangModal" role="dialog" aria-labelledby="tambahPengarangModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPengarangModalLabel">Tambah Pengarang
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">

                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-primary ml-2" data-toggle="modal"
                            data-target="#tambahKategoriModal">
                            <i class="fas fa-plus"></i> Kategori Pengarang
                        </button>
                    </div>

                    <form action="{{ route('tambahPengarang') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama Pengarang*
                                @error('pengarang_nama')
                                    <span class="text-danger">({{ $message }})</span>
                                @enderror
                            </label>
                            <input type="text" name="pengarang_nama"
                                class="form-control @error('pengarang_nama') is-invalid @enderror" id="nama"
                                id="nama" value="{{ old('pengarang_nama') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="kategori">Pengarang Kategori*
                                @error('pengarang_kategori')
                                    <span class="text-danger">({{ $message }})</span>
                                @enderror
                            </label>
                            <select name="pengarang_kategori"
                                class="form-control @error('pengarang_kategori') is-invalid @enderror select2"
                                id="kategori" required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->kategori_id }}"
                                        {{ old('pengarang_kategori') == $kategori->kategori_id ? 'selected' : '' }}>
                                        {{ $kategori->kategori_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                            <button type="submit" class="btn btn-success" id="simpanPengarangBtn">Simpan</button>
                        </div><!-- /.modal-footer -->
                    </form>
                </div><!-- /.modal-body -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedPengarangs') }}" method="POST">
    @csrf
    <div class="modal fade" id="hapusModalPengarang" tabindex="-1" role="dialog"
        aria-labelledby="hapusModalPengarangLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalPengarangLabel">
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Apakah Anda Yakin Ingin Menghapus Pengarang Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_pengarang_ids" id="selected-ids" value="">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>


<!-- Modal Edit Pengarang -->
<div class="modal fade" id="editPengarangModal" tabindex="-1" role="dialog"
    aria-labelledby="editPengarangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPengarangModalLabel">Edit Pengarang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('updatePengarang') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-primary ml-2" data-toggle="modal"
                            data-target="#tambahKategoriModal">
                            <i class="fas fa-plus"></i> Pengarang Kategori
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="pengarang_nama">Nama Pengarang</label>
                        <input type="text" name="pengarang_nama" class="form-control" id="pengarang_nama"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="pengarang_kategori">Pengarang Kategori</label>
                        <select name="pengarang_kategori" class="form-control select2" id="pengarang_kategori"
                            required>
                            <option value="" selected disabled>Pilih Kategori</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="pengarang_id_edit" id="pengarang_id_edit" value="">

                    <button type="submit" class="btn btn-success" id="simpanKotaBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah Kategori -->
<div class="modal fade" id="tambahKategoriModal" tabindex="-1" role="dialog"
    aria-labelledby="tambahKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKategoriModalLabel">Tambah Kategori
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahKategoriPengarangg') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="kategori_nama" class="form-control" id="nama"
                            value="{{ old('kategori_nama') }}" required>
                        @error('kategori_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                        <button type="submit" class="btn btn-success" id="simpanKategoriBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    $(document).ready(function() {
        var pengarang_table = $('#pengarang_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'pengarang/json',
            columns: [{
                    name: 'pengarang_id',
                    data: 'pengarang_id',
                    render: function(data, type, full, meta) {
                        return '<input type="checkbox" class="checkbox" data-id="' +
                            data + '">';
                    }
                },
                {
                    name: 'pengarang_nama',
                    data: 'pengarang_nama',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="text-left" data-item-id="${full.pengarang_id}" style="cursor: pointer">
                                ${data}
                            </div>
                        `;
                    }
                },
                {
                    name: 'kategori.kategori_nama',
                    data: 'kategori.kategori_nama',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="text-left" data-item-id="${full.pengarang_id}" style="cursor: pointer">
                                ${data}
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
                                data-target="#editPengarangModal"
                                data-pengarang-id="${full.pengarang_id}"
                                data-pengarang-nama="${full.pengarang_nama}"
                                data-pengarang-kategori="${full.pengarang_kategori}">
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

        $('#pengarang_table').on('click', 'div[data-item-id]', function() {
            const itemId = $(this).data("item-id");
            console.log("item_id:", itemId);

            // Isi nilai pengarang_id dengan itemId
            $('#selectedPengarangId').val(itemId);

            // Submit form pengarangForm
            $('#pengarangForm').submit();
        });

        $(document).on('click', '[data-toggle="modal"][data-target^="#editPengarangModal"]', function() {
            var pengarangId = $(this).data('pengarang-id');
            var pengarangNama = $(this).data('pengarang-nama');
            var pengarangKategori = $(this).data('pengarang-kategori');
            var kategoris = @json($kategoris);

            $('#pengarang_id_edit').val(pengarangId);
            $('#pengarang_nama').val(pengarangNama);

            var selectKategori = $('#pengarang_kategori');
            selectKategori.empty();

            kategoris.forEach(function(kategori) {
                var option = $('<option>').val(kategori.kategori_id).text(kategori
                    .kategori_nama);
                selectKategori.append(option);
            });
            selectKategori.val(pengarangKategori);
            selectKategori.trigger('change');
        });

        $(document).on('click', '[data-toggle="modal"][data-target="#hapusModalPengarang"]', function() {
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
