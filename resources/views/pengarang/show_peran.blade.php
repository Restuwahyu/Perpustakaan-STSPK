@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Peran Pengarang ({{ $pengarang->pengarang_nama }} -
                            {{ $pengarang->kategori->kategori_nama }})</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('showPengarang') }}">List Pengarang</a>
                            </li>
                            <li class="breadcrumb-item active">List Peran Pengarang</li>
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


                            <table id="pengarangPeran_table" class="table table-bordered dt-responsive text-center"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Buku</th>
                                        <th>Peran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td class="text-left">
                                                @foreach ($data->bukus as $buku)
                                                    {{ utf8_decode($buku->buku_judul) }}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($data->perans as $peran)
                                                    {{ $peran->peran_nama }}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                <button class="btn btn-sm detail-button btn-primary" data-toggle="modal"
                                                    data-target="#detailModal{{ $data->buku_pengarang_peran_id }}">
                                                    Detail Buku
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal -->

                                        @foreach ($data->bukus as $buku)
                                            <div class="modal fade" id="detailModal{{ $data->buku_pengarang_peran_id }}"
                                                tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detailModalLabel">Detail
                                                                Buku</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <div class="row align-items-center mb-3">
                                                                <div class="col-12">

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-4" style="text-align: center;">
                                                                    @if (
                                                                        $buku->buku_cover_original &&
                                                                            file_exists(storage_path('app/public/images/cover_original/' . basename($buku->buku_cover_original))))
                                                                        <img src="{{ asset($buku->buku_cover_original) }}"
                                                                            class="img img-responsive"
                                                                            style="width:230px; height: 350px; border-radius: 5px;">
                                                                    @else
                                                                        <img id="previewImage"
                                                                            src="{{ asset('storage/images/preview.png') }}"
                                                                            alt="Preview Image"
                                                                            class=" img img-responsive"
                                                                            style="width:230px; height: 350px; border-radius: 5px;">
                                                                    @endif
                                                                </div>
                                                                <div class="col-8">
                                                                    <div class="title"
                                                                        style="font-weight: bold; font-size: 20px">
                                                                        {{ utf8_decode($buku->buku_judul) }}
                                                                    </div>
                                                                    <div class="subtitle">
                                                                        @foreach ($buku->pengarangs as $index => $pengarang)
                                                                            <small class="text-muted"
                                                                                style="font-size: 15px;">
                                                                                {{ utf8_decode($pengarang->pengarang_nama) }}
                                                                                ({{ $pengarang->kategori->kategori_nama }})
                                                                                -
                                                                                {{ $buku->perans[$index]->peran_nama }}
                                                                                @if (!$loop->last)
                                                                                    ;
                                                                                @endif

                                                                            </small>
                                                                        @endforeach
                                                                    </div>

                                                                    <h6 class="mt-3">Informasi Detail</h6>
                                                                    <div class="detail mt-2 align-items-center">
                                                                        <dl class="row">
                                                                            <dt class="col-sm-4 fw-700">
                                                                                Judul Seri:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->buku_seri ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                No. Panggil:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $data->eksemplar_no_panggil ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                Penerbit:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->penerbit->penerbit_nama ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                Tahun Terbit:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->buku_tahun_terbit ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                Deskripsi Fisik:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->buku_deskripsi_fisik ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                Bahasa:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->bahasa->bahasa_nama ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                ISBN/ISSN:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->buku_isbn_issn ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                Klasifikasi:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->klasifikasi->klasifikasi_nama ?? '-' }}
                                                                            </dd>

                                                                            <dt class="col-sm-4 fw-700">
                                                                                Edisi:
                                                                            </dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $buku->buku_edisi ?? '-' }}
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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

    <script type="text/javascript">
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

            $('#selected-ids').val(JSON.stringify(selectedIds));

            var dataIds = $('#' + tableId + ' .checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            console.log('Ids yang dipilih di ' + tableId + ':', dataIds);
        });
    </script>


    @include('layouts.footer')
