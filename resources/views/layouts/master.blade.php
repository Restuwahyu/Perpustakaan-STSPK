@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">

            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block app-datepicker">
                        <input type="text" class="form-control" data-date-format="MM dd, yyyy" readonly="readonly"
                            id="datepicker">
                        <i class="mdi mdi-chevron-down mdi-drop"></i>
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

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title ml-3  mb-4">Statistik Peminjaman Buku</h4>
                        <ul class="list-inline widget-chart mt-4 mb-0 text-center">
                            <li class="list-inline-item" id="toggleChartTotal">
                                <h5>{{ $totalPeminjaman }}</h5>
                                <p class="text-muted">Total Peminjaman</p>
                            </li>
                        </ul>
                        <ul class="list-inline widget-chart mt-4 mb-0 text-center">
                            <li class="list-inline-item" id="toggleChartTotal">
                                <h5 onclick="show5TahunChart()" style="cursor:pointer">{{ $totalPeminjamanLast5Years }}
                                </h5>
                                <p class="text-muted">5 Tahun Terakhir</p>
                            </li>
                            <li class="list-inline-item" id="toggleChartLastMonth">
                                <h5 onclick="showTahunChart()" style="cursor:pointer">{{ $totalPeminjamanLastYear }}
                                </h5>
                                <p class="text-muted">Tahun Terakhir</p>
                            </li>
                            <li class="list-inline-item" id="toggleChartLastWeek">
                                <h5 onclick="showBulanChart()" style="cursor:pointer">{{ $totalPeminjamanLastMonth }}
                                </h5>
                                <p class="text-muted">Bulan Terakhir</p>
                            </li>
                        </ul>

                        <div id="chart-bar-bulan" class="text-center" style="height: 350px;"></div>
                        <div id="chart-bar-tahun" class="text-center" style="height: 350px;"></div>
                        <div id="chart-bar-5tahun" class="text-center" style="height: 350px;"></div>

                        <div class="d-flex justify-content-end">
                            <form action="{{ route('printData') }}" method="POST" id="downloadForm">
                                @csrf
                                <input type="hidden" name="activeChart" id="activeChartInput" value="">
                                <button type="button" class="btn btn-success mr-2" onclick="downloadData()">Unduh
                                    Data</button>
                            </form>

                            <button id="unduhGrafik" class="btn btn-primary">Unduh Grafik</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title ml-3 mb-4">Member Tidak Aktif</h4>
                        <div class="table-responsive">
                            <table id="memberDashboard_table" class="table table-hover mb-0 dt-responsive text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Tgl. Kedaluwarsa</th>
                                        <th scope="col">Status</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($members as $member)
                                        <tr>
                                            <td class="text-left">
                                                {{ $member->member_nama }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($member->member_tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td>
                                                @if ($member->member_status == 1)
                                                    <span class="badge badge-warning">Mendekati Masa Tenggang</span>
                                                @else
                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('statusMemberDashboard') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id_member_status"
                                                        value="{{ $member->member_id }}">
                                                    <a class="btn btn-success" data-toggle="modal"
                                                        data-target="#aktivasiModal{{ $member->member_id }}"
                                                        style="color: white">
                                                        <i class="fas fa-user-clock"></i>
                                                    </a>
                                                    <!-- Aktivasi Modal -->
                                                    <div class="modal fade" id="aktivasiModal{{ $member->member_id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="aktivasiModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="aktivasiModalLabel">
                                                                        Konfirmasi Aktivasi
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-left">
                                                                    Apakah Anda Yakin Ingin
                                                                    Mengaktifkan Member
                                                                    {{ $member->member_nama }}
                                                                    Ini Kembali ?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Batal</button>
                                                                    <input type="hidden" name="id_member_status"
                                                                        value="{{ $member->member_id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-success">Ya</button>
                                                                </div>
                                                            </div><!-- ./modal-content -->
                                                        </div><!-- ./modal-dialog -->
                                                    </div><!-- ./modal -->
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end row -->
        </div><!-- container-fluid -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title ml-3 mb-4">Pemesanan Buku</h4>
                        <table id="pemesananBuku_table" class="table table-bordered dt-responsive text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Judul Buku</th>
                                    <th>Tgl. Pemesanan</th>
                                    <th>Tgl. Pengambilan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanBukus as $pesanBuku)
                                    <tr>
                                        <td class="text-left">
                                            {{ $pesanBuku->members->member_nama }}
                                        </td>
                                        <td class="text-left">
                                            {{ utf8_decode($pesanBuku->bukus->buku_judul) }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($pesanBuku->pemesanan_buku_tanggal_pemesanan)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($pesanBuku->pemesanan_buku_tanggal_pengambilan)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td>
                                            @if ($pesanBuku->pemesanan_buku_status == 0)
                                                <span class="badge badge-warning">Menunggu Konfirmasi Petugas</span>
                                            @elseif($pesanBuku->pemesanan_buku_status == 1)
                                                <span class="badge badge-success">Sudah Dikonfirmasi</span>
                                            @elseif($pesanBuku->pemesanan_buku_status == 2)
                                                <span class="badge badge-primary">Menunggu Pengambilan Buku</span>
                                            @endif
                                        </td>
                                        <td>

                                            @if ($pesanBuku->pemesanan_buku_status == 0)
                                                <form action="{{ route('verifikasi') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="pemesanan_id"
                                                        value="{{ $pesanBuku->pemesanan_buku_id }}">

                                                    <button type="submit"
                                                        class="btn btn-sm detail-button btn-primary">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($pesanBuku->pemesanan_buku_status == 1)
                                                <button class="btn btn-sm detail-button btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal{{ $pesanBuku->pemesanan_buku_id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif

                                            @if ($pesanBuku->pemesanan_buku_status == 2)
                                                <form action="{{ route('pemesananBuku.selesai') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="pemesanan_id"
                                                        value="{{ $pesanBuku->pemesanan_buku_id }}">
                                                    <input type="hidden" name="user_id"
                                                        value="{{ session('user')['user_id'] }}">

                                                    <button type="submit"
                                                        class="btn btn-sm detail-button btn-danger">
                                                        Selesai Transaksi
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $pesanBuku->pemesanan_buku_id }}"
                                        tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        iid="deleteModal{{ $pesanBuku->pemesanan_buku_id }}">
                                                        Konfirmasi
                                                        Hapus Pemesanan Buku
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div><!-- /.modal-header -->
                                                <div class="modal-body">Apakah Anda yakin ingin membatalkan pemesanan
                                                    buku {{ $pesanBuku->bukus->buku_judul }}?</div>
                                                <!-- /.modal-body -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Batal</button>
                                                    <form action="{{ route('pemesananBuku.delete') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="pemesanan_id"
                                                            value="{{ $pesanBuku->pemesanan_buku_id }}">
                                                        <input type="hidden" name="role" value="user">
                                                        <button type="submit" class="btn btn-primary">Ya</button>
                                                    </form>
                                                </div><!-- /.modal-footer -->
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title ml-3 mb-4">Pengembalian Buku</h4>
                        <table id="pengembalianBukuDashboard_table"
                            class="table table-bordered dt-responsive text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>No. Eksemplar</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengembalian_buku as $buku)
                                    <tr>
                                        <td style="cursor: pointer" data-toggle="modal"
                                            data-target="#memberModal{{ $buku->member->member_id }}">
                                            {{ $buku->member->member_nama }}
                                        </td>
                                        <td style="cursor: pointer" data-toggle="modal"
                                            data-target="#memberModal{{ $buku->member->member_id }}">
                                            {{ $buku->eksemplar->eksemplar_no_eksemplar }}
                                        </td>
                                        <td class="text-left" style="cursor: pointer" data-toggle="modal"
                                            data-target="#memberModal{{ $buku->member->member_id }}">
                                            {{ utf8_decode($buku->eksemplar->buku->buku_judul) }}
                                        </td>
                                        <td style="cursor: pointer" data-toggle="modal"
                                            data-target="#memberModal{{ $buku->member->member_id }}">
                                            {{ \Carbon\Carbon::parse($buku->peminjaman_tgl_pinjam)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td style="cursor: pointer" data-toggle="modal"
                                            data-target="#memberModal{{ $buku->member->member_id }}">
                                            {{ \Carbon\Carbon::parse($buku->peminjaman_tgl_kembali)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td style="cursor: pointer" data-toggle="modal"
                                            data-target="#memberModal{{ $buku->member->member_id }}">
                                            @if ($buku->status_kembali == 'Harus Dikembalikan Hari Ini')
                                                <span class="badge badge-danger">{{ $buku->status_kembali }}</span>
                                            @else
                                                <span class="badge badge-warning">
                                                    {!! str_replace('Habis dalam', "\nHabis dalam", $buku->status_kembali) !!}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                @if ($buku->daysRemaining <= 1)
                                                    <form action="{{ route('sentEmailRemainder') }}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-sm detail-button btn-warning mr-2">
                                                            <input type="hidden" name="peminjaman_id"
                                                                value="{{ $buku->peminjaman_id }}">
                                                            <input type="hidden" name="member_id"
                                                                value="{{ $buku->member->member_id }}">
                                                            <i class="ion ion-md-mail"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <button class="btn btn-sm detail-button btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#detailModal{{ $buku->peminjaman_id }}">
                                                    <i class="ion ion-ios-book"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="memberModal{{ $buku->member->member_id }}"
                                        tabindex="-1" role="dialog" aria-labelledby="memberModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="memberModalLabel">Detail Member</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4 fw-700">Nama</dt>
                                                        <dd class="col-sm-8">{{ $buku->member->member_nama }}</dd>

                                                        <dt class="col-sm-4 fw-700">Alamat</dt>
                                                        <dd class="col-sm-8">{{ $buku->member->member_alamat }}</dd>

                                                        <dt class="col-sm-4 fw-700">Email</dt>
                                                        <dd class="col-sm-8">{{ $buku->member->member_email }}</dd>

                                                        <dt class="col-sm-4 fw-700">No. Telp</dt>
                                                        <dd class="col-sm-8">{{ $buku->member->member_notelp }}</dd>

                                                        <dt class="col-sm-4 fw-700">Tgl. Kedaluwarsa</dt>
                                                        <dd class="col-sm-8">
                                                            {{ \Carbon\Carbon::parse($buku->member->member_tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM Y') }}
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal -->
                                    <div class="modal fade" id="detailModal{{ $buku->peminjaman_id }}"
                                        tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailModalLabel">Detail Buku</h5>
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
                                                                $buku->eksemplar->buku->buku_cover_original &&
                                                                    file_exists(storage_path('app/public/images/cover_original/' . basename($buku->eksemplar->buku->buku_cover_original))))
                                                                <img src="{{ asset($buku->eksemplar->buku->buku_cover_original) }}"
                                                                    class="img img-responsive"
                                                                    style="width:230px; height: 350px; border-radius: 5px;">
                                                            @else
                                                                <img id="previewImage"
                                                                    src="{{ asset('storage/images/preview.png') }}"
                                                                    alt="Preview Image" class=" img img-responsive"
                                                                    style="width:230px; height: 350px; border-radius: 5px;">
                                                            @endif
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="title"
                                                                style="font-weight: bold; font-size: 20px">
                                                                {{ utf8_decode($buku->eksemplar->buku->buku_judul) }}
                                                            </div>
                                                            <div class="subtitle">
                                                                @foreach ($buku->eksemplar->buku->pengarangs as $index => $pengarang)
                                                                    <small class="text-muted"
                                                                        style="font-size: 15px;">
                                                                        <strong>{{ $pengarang->pengarang_nama }}</strong>
                                                                        ({{ $pengarang->kategori->kategori_nama }})
                                                                        -
                                                                        {{ $buku->eksemplar->buku->perans[$index]->peran_nama }}
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
                                                                        {{ $buku->eksemplar->buku->buku_seri ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        No. Panggil:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->eksemplar_no_panggil ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Penerbit:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->buku->penerbit->penerbit_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Tahun Terbit:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->buku->buku_tahun_terbit ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Deskripsi Fisik:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->buku->buku_deskripsi_fisik ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Bahasa:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->buku->bahasa->bahasa_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        ISBN/ISSN:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->buku->buku_isbn_issn ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Klasifikasi:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->buku->klasifikasi->klasifikasi_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Edisi:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $buku->eksemplar->buku->buku_edisi ?? '-' }}
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
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->

    </div><!-- content -->

    <!--Morris Chart-->
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/raphael/raphael.min.js') }}"></script>

    <script>
        function showBulanChart() {
            $('#chart-bar-5tahun').hide();
            $('#chart-bar-tahun').hide();
            $('#chart-bar-bulan').show();
        }

        function showTahunChart() {
            $('#chart-bar-5tahun').hide();
            $('#chart-bar-tahun').show();
            $('#chart-bar-bulan').hide();
        }

        function show5TahunChart() {
            $('#chart-bar-5tahun').show();
            $('#chart-bar-tahun').hide();
            $('#chart-bar-bulan').hide();
        }

        function downloadData() {
            var activeChart;

            if ($('#chart-bar-5tahun').is(':visible')) {
                activeChart = 'chart-bar-5tahun';
            } else if ($('#chart-bar-tahun').is(':visible')) {
                activeChart = 'chart-bar-tahun';
            } else if ($('#chart-bar-bulan').is(':visible')) {
                activeChart = 'chart-bar-bulan';
            }

            $('#activeChartInput').val(activeChart);

            $('#downloadForm').submit();
        }


        var morrisCharts = {};

        $(document).ready(function() {
            $('#chart-bar-5tahun').hide();
            $('#chart-bar-tahun').show();
            $('#chart-bar-bulan').hide();

            $('#unduhGrafik').on('click', function() {
                if ($('#chart-bar-5tahun').is(':visible')) {
                    downloadChart('chart-bar-5tahun');
                } else if ($('#chart-bar-tahun').is(':visible')) {
                    downloadChart('chart-bar-tahun');
                } else if ($('#chart-bar-bulan').is(':visible')) {
                    downloadChart('chart-bar-bulan');
                }
            });

            var morrisCharts = {};

            //Chart dalam 5 Tahun
            var peminjamanLast5Years = <?php echo json_encode($peminjamanLast5Years); ?>;

            var chartDataLast5Years = [];

            for (var year in peminjamanLast5Years) {
                if (peminjamanLast5Years.hasOwnProperty(year)) {
                    chartDataLast5Years.push({
                        year: year,
                        Total: peminjamanLast5Years[year].total
                    });
                }
            }

            morrisCharts['chart-bar-5tahun'] = Morris.Bar({
                element: 'chart-bar-5tahun',
                data: chartDataLast5Years,
                xkey: 'year',
                ykeys: ['Total'],
                labels: ['Total Peminjaman Buku'],
                barColors: ["#23cbe0"],
                hideHover: 'auto',
                stacked: true,
                gridLineColor: '#eef0f2',
                barSizeRatio: 0.4,
            });

            //Chart dalam 1 Tahun
            var peminjamanLastYear = <?php echo json_encode($peminjamanLastYear); ?>;
            var chartDataLastYear = [];

            for (var month in peminjamanLastYear) {
                if (peminjamanLastYear.hasOwnProperty(month)) {
                    chartDataLastYear.push({
                        month: month,
                        Total: peminjamanLastYear[month].total
                    });
                }
            }
            morrisCharts['chart-bar-tahun'] = Morris.Bar({
                element: 'chart-bar-tahun',
                data: chartDataLastYear,
                xkey: 'month',
                ykeys: ['Total'],
                labels: ['Total Peminjaman Buku'],
                xLabels: 'month',
                barColors: ['#23cbe0'],
                hideHover: 'auto',
                gridLineColor: '#eef0f2',
                resize: true,
                barSizeRatio: 0.4,
            });

            //Chart dalam 1 Bulan
            var peminjamanLastMonth = <?php echo json_encode($peminjamanLastMonth); ?>;

            var chartDataLastMonth = [];

            for (var week in peminjamanLastMonth) {
                if (peminjamanLastMonth.hasOwnProperty(week)) {
                    chartDataLastMonth.push({
                        week: week,
                        Total: peminjamanLastMonth[week].total
                    });
                }
            }

            morrisCharts['chart-bar-bulan'] = Morris.Bar({
                element: 'chart-bar-bulan',
                data: chartDataLastMonth,
                xkey: 'week',
                ykeys: ['Total'],
                labels: ['Total Peminjaman Buku'],
                xLabels: 'week',
                barColors: ['#23cbe0'],
                hideHover: 'auto',
                gridLineColor: '#eef0f2',
                resize: true,
                barSizeRatio: 0.4,
            });

            function downloadChart(chartId) {
                html2canvas(document.getElementById(chartId), {
                    scale: 2,
                    logging: false,
                }).then(function(canvas) {
                    var dataUrl = canvas.toDataURL();

                    var chartIdParts = chartId.split('-');
                    var chartType = chartIdParts[2];
                    var fileName;

                    if (chartType === '5tahun') {
                        var currentYear = new Date().getFullYear();
                        fileName = 'Chart_5 Tahun Terakhir.png';
                    } else if (chartType === 'tahun') {
                        var currentYear = new Date().getFullYear();
                        fileName = 'Chart_' + currentYear + '.png';
                    } else if (chartType === 'bulan') {
                        var monthsInIndonesian = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];

                        var currentMonth = new Date().getMonth() + 1;
                        var currentYear = new Date().getFullYear();

                        var monthName = monthsInIndonesian[currentMonth - 1];
                        fileName = 'Chart_' + monthName + '_' + currentYear + '.png';
                    }

                    var a = document.createElement('a');
                    a.href = dataUrl;
                    a.download = fileName;
                    a.click();
                });
            }

        });
    </script>

    @include('layouts.footer')
