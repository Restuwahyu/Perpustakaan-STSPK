@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Data Peminjaman Buku</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home', ['allowed' => 1]) }}">Dashboard</a></li>
                        </li>
                        <li class="breadcrumb-item active">Tambah Data Peminjaman Buku</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card" id="kontenSetelahInput">
            <div class="card-body">
                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12 col-md-12"></div>
                        <div class="col-sm-12 col-md-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table width="100%" class="s-member__account" cellpadding="5" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td colspan="5">
                                            <form method="POST" action="{{ route('selesaiTransaksi') }}">
                                                @csrf
                                                <a class="btn btn-sm btn-danger"data-toggle="modal"
                                                    data-target="#selesaiTransaksiModal">Selesai Transaksi
                                                </a>
                                                <!-- Selesai Transaksi Modal -->
                                                <div class="modal fade" id="selesaiTransaksiModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="selesaiTransaksiModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="selesaiTransaksiModalLabel">
                                                                    Selesai Transaksi
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div><!-- /.modal-header -->
                                                            <div class="modal-body">
                                                                Apakah Anda Yakin ingin Menyelesaikan Transaksi ?
                                                            </div><!-- /.modal-body -->
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Batal</button>

                                                                <input type="hidden" name="allowed" value="1">

                                                                <button type="submit"
                                                                    class="btn btn-success">Ya</button>
                                                            </div><!-- /.modal-footer -->
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div><!-- /.modal -->
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="alterCell" width="15%"><strong>Nama Anggota:</strong></td>
                                        <td class="alterCell2" width="30%">
                                            {{ $user->user_nama }}
                                        </td>
                                        <td class="alterCell" width="15%"><strong>ID Anggota:</strong></td>
                                        <td class="alterCell2" width="30%">
                                            {{ $user->user_kode }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="alterCell" width="15%"><strong>Alamat Anggota:</strong></td>
                                        <td class="alterCell2" width="30%">
                                            {{ $user->user_alamat ?? '-' }}
                                        </td>
                                        <td class="alterCell" width="15%"><strong>Email Anggota:</strong></td>
                                        <td class="alterCell2" width="30%">
                                            {{ $user->user_email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="alterCell" width="15%"><strong>Tanggal Registrasi:</strong>
                                        </td>
                                        <td class="alterCell2" width="30%">
                                            {{ \Carbon\Carbon::parse($user->user_tanggal_registrasi)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                        </td>
                                        <td class="alterCell" width="15%"><strong>Berlaku Hingga:</strong></td>
                                        <td class="alterCell2" width="30%">
                                            @if ($user->user_tanggal_kedaluwarsa == null)
                                                {{ $user->user_tanggal_kedaluwarsa ?? '-' }}
                                            @else
                                                {{ \Carbon\Carbon::parse($user->user_tanggal_kedaluwarsa ?? '-')->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Navigation -->
        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="peminjamanTab" data-toggle="tab" href="#peminjamanContent">
                            Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pinjamanSaatIniTab" data-toggle="tab" href="#pinjamanSaatIniContent">
                            Pinjaman Saat Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="riwayatPeminjamanTab" data-toggle="tab"
                            href="#riwayatPeminjamanContent">
                            Riwayat Peminjaman
                        </a>
                    </li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <!-- Konten Tab "Peminjaman" -->
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="peminjamanContent"
                        @if (session('activeTab') == 'peminjamanTab') class="show active" @endif>
                        <!-- Isi konten untuk tab "Peminjaman" -->
                        <div class="row" id="inputIdBuku">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-body ">
                                        <form method="POST"
                                            action="{{ route('tambahPeminjamanSementara', ['allowed' => 1]) }}">
                                            @csrf
                                            <input type="hidden" name="user_kode" value="{{ $user->user_kode }}">
                                            <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="peminjaman_buku" class="form-label">Kode Buku</label>
                                                </div>
                                                <div class="col-5">
                                                    <select name="peminjaman_buku" class="form-control"
                                                        id="peminjaman_buku" required>
                                                        <option value="" selected disabled>Pilih Buku
                                                        </option>
                                                        @foreach ($bukus->get() as $buku)
                                                            <option value="{{ $buku->buku_id }}">
                                                                {{ $buku->buku_kode }} - {{ $buku->buku_judul }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <input type="hidden" name="allowed" value="1">
                                                    <button type="submit" class="btn btn-primary ml-2">Submit
                                                    </button>
                                                </div>
                                            </div><!-- /.form-row -->
                                        </form>
                                    </div><!-- /.card-body -->
                                </div><!-- /.card -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        @if (!empty($selectedBooks))
                            <!-- Tampilkan data buku yang telah dipilih -->
                            <table class="table table-bordered table-hover" id="tabelPeminjaman">
                                <thead>
                                    <tr>
                                        <th>Kode Buku</th>
                                        <th>Judul</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedBooks as $selectedBook)
                                        <tr>
                                            <td>{{ $selectedBook['peminjaman_buku']->buku_kode }}</td>
                                            <td>{{ $selectedBook['peminjaman_buku']->buku_judul }}</td>
                                            <td>
                                                {{ $selectedBook['peminjaman_tgl_pinjam']->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td>
                                                {{ $selectedBook['peminjaman_tgl_kembali']->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td>
                                                <form
                                                    action="{{ route('deletePeminjamanSementara', ['allowed' => 1]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="#deleteModal{{ $selectedBook['id'] }}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteModal{{ $selectedBook['id'] }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel">
                                                                        Konfirmasi Delete
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <!-- /.modal-header -->
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin
                                                                    menghapus data
                                                                    {{ $selectedBook['peminjaman_buku']->buku_kode }}
                                                                    ini ?
                                                                </div><!-- /.modal-body -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Batal
                                                                    </button>
                                                                    <input type="hidden" name="id_peminjaman_delete"
                                                                        value="{{ $selectedBook['id'] }}">

                                                                    <input type="hidden" name="user_kode"
                                                                        value="{{ $selectedBook['peminjaman_user']->user_kode }}">

                                                                    <input type="hidden" name="allowed"
                                                                        value="1">

                                                                    <button type="submit"
                                                                        class="btn btn-success">Ya</button>
                                                                </div><!-- /.modal-footer -->
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Tidak Ada Data Peminjaman</p>
                        @endif
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane fade" id="pinjamanSaatIniContent"
                        @if (session('activeTab') == 'pinjamanSaatIniTab') class="show active" @endif>
                        <!-- Isi konten untuk tab "Pinjaman Saat Ini" -->
                        @if (!$peminjamans_id->isEmpty())

                            <table class="table table-bordered table-hover table-striped dataTable dtr-inline">
                                <thead>
                                    <tr>
                                        <th>Kode Buku</th>
                                        <th>Judul</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($peminjamans_id as $peminjaman)
                                        @if ($peminjaman->peminjaman_status != 0)
                                            <tr>
                                                <td>{{ $peminjaman->buku->buku_kode }}</td>
                                                <td>{{ $peminjaman->buku->buku_judul }}
                                                    @if ($peminjaman->peminjaman_status == 2)
                                                        -
                                                        <span class="badge badge-success"> Diperpanjang</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_kembali)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <form
                                                            action="{{ route('updatePinjamBuku', ['allowed' => 1]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <a class="btn btn-sm btn-primary" data-toggle="modal"
                                                                data-target="#kembaliModal{{ $peminjaman->peminjaman_id }}">
                                                                Kembali
                                                            </a>
                                                            <!-- Kembali Modal -->
                                                            <div class="modal fade"
                                                                id="kembaliModal{{ $peminjaman->peminjaman_id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="kembaliModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="kembaliModalLabel">
                                                                                Konfirmasi Kembali Peminjaman Buku
                                                                            </h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <!-- /.modal-header -->
                                                                        <div class="modal-body">
                                                                            Apakah Anda Yakin ingin
                                                                            Mengembalikan Buku
                                                                            {{ $peminjaman->buku->buku_kode }}
                                                                            ini ?
                                                                        </div><!-- /.modal-body -->
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-danger"
                                                                                data-dismiss="modal">Batal
                                                                            </button>
                                                                            <input type="hidden"
                                                                                name="id_peminjaman_edit"
                                                                                value="{{ $peminjaman->peminjaman_id }}">

                                                                            <input type="hidden" name="action"
                                                                                value="kembali">
                                                                            <input type="hidden" name="allowed"
                                                                                value="1">

                                                                            <button type="submit"
                                                                                class="btn btn-success">Ya
                                                                            </button>
                                                                        </div><!-- /.modal-footer -->
                                                                    </div><!-- /.modal-content -->
                                                                </div><!-- /.modal-dialog -->
                                                            </div><!-- /.modal -->
                                                        </form>

                                                        @if ($peminjaman->peminjaman_status == 1)
                                                            <form
                                                                action="{{ route('updatePinjamBuku', ['allowed' => 1]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <a class="btn btn-sm btn-success" data-toggle="modal"
                                                                    data-target="#perpanjanganModal{{ $peminjaman->peminjaman_id }}">
                                                                    Perpanjangan
                                                                </a>
                                                                <!-- Perpanjangan Modal -->
                                                                <div class="modal fade"
                                                                    id="perpanjanganModal{{ $peminjaman->peminjaman_id }}"
                                                                    tabindex="-1" role="dialog"
                                                                    aria-labelledby="perpanjanganModalLabel"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="perpanjanganModalLabel">
                                                                                    Konfirmasi Perpanjangan Peminjaman
                                                                                    Buku
                                                                                </h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span
                                                                                        aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <!-- /.modal-header -->
                                                                            <div class="modal-body">
                                                                                Apakah Anda Yakin ingin
                                                                                Memperpanjang Buku
                                                                                {{ $peminjaman->buku->buku_kode }}
                                                                                ini ?
                                                                            </div><!-- /.modal-body -->
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-danger"
                                                                                    data-dismiss="modal">Batal
                                                                                </button>
                                                                                <input type="hidden"
                                                                                    name="id_peminjaman_edit"
                                                                                    value="{{ $peminjaman->peminjaman_id }}">

                                                                                <input type="hidden" name="action"
                                                                                    value="perpanjangan">

                                                                                <input type="hidden" name="allowed"
                                                                                    value="1">

                                                                                <button type="submit"
                                                                                    class="btn btn-success">Ya
                                                                                </button>
                                                                            </div><!-- /.modal-footer -->
                                                                        </div><!-- /.modal-content -->
                                                                    </div><!-- /.modal-dialog -->
                                                                </div><!-- /.modal -->
                                                            </form>
                                                        @endif
                                                    </div><!-- /.d-flex -->
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Tidak Ada Peminjaman Buku Saat Ini</p>
                        @endif
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane fade" id="riwayatPeminjamanContent">
                        <!-- Isi konten untuk tab "Riwayat Peminjaman" -->
                        @if (!$peminjamans_riwayat->isEmpty())
                            <!--  Search Peminjaman Riwayat -->
                            {{-- <form action="{{ route('searchPeminjamanBuku', ['allowed' => 1]) }}" method="GET">
                                @csrf
                                <input type="hidden" name="allowed" value="1">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari Peminjaman Buku">

                                    <input type="hidden" name="id_peminjaman_user" value="{{ $user->user_kode }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form> --}}

                            <table class="table table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                    <tr>
                                        <th>Kode Buku</th>
                                        <th>Judul</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($peminjamans_riwayat as $peminjaman)
                                        <tr>
                                            <td>{{ $peminjaman->buku->buku_kode }}</td>
                                            <td>{{ $peminjaman->buku->buku_judul }}
                                                - <span class="badge badge-primary"> Kembali</span>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_kembali)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                            <td>
                                                <button class="btn btn-sm detail-button btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#detailModal{{ $peminjaman->peminjaman_id }}">
                                                    Detail Peminjaman Buku
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div class="modal fade" id="detailModal{{ $peminjaman->peminjaman_id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="detailModalLabel">Detail
                                                            Peminjaman Buku</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <dl class="row">
                                                            <dt class="col-sm-3">Kode Buku:</dt>
                                                            <dd class="col-sm-9">{{ $peminjaman->buku->buku_kode }}
                                                            </dd>

                                                            <dt class="col-sm-3">Judul Buku:</dt>
                                                            <dd class="col-sm-9">{{ $peminjaman->buku->buku_judul }}
                                                            </dd>

                                                            <dt class="col-sm-3">Nama Pengarang:</dt>
                                                            <dd class="col-sm-9">
                                                                {{ $peminjaman->buku->pengarang->pengarang_nama }}</dd>

                                                            <dt class="col-sm-3">Nama Penerbit:</dt>
                                                            <dd class="col-sm-9">
                                                                {{ $peminjaman->buku->penerbit->penerbit_nama }}</dd>

                                                            <dt class="col-sm-3">Tahun Terbit Buku:</dt>
                                                            <dd class="col-sm-9">
                                                                {{ $peminjaman->buku->buku_tahun_terbit }}</dd>

                                                            <dt class="col-sm-3">ISBN/ISSN Buku:</dt>
                                                            <dd class="col-sm-9">
                                                                {{ $peminjaman->buku->buku_isbn_issn }}</dd>

                                                            <dt class="col-sm-3">Tanggal Pinjam:</dt>
                                                            <dd class="col-sm-9">
                                                                {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->locale('id')->isoFormat('D MMMM Y') }}
                                                            </dd>

                                                            <dt class="col-sm-3">Tanggal Kembali:</dt>
                                                            <dd class="col-sm-9">
                                                                {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_kembali)->locale('id')->isoFormat('D MMMM Y') }}
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
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <div class="row">
                                <div class="col-auto mr-auto">
                                    <p>Showing {{ $peminjamans_riwayat->firstItem() }} to
                                        {{ $peminjamans_riwayat->lastItem() }} of
                                        {{ $peminjamans_riwayat->total() }} entries</p>
                                </div>
                                <div class="col-auto"> {{ $peminjamans_riwayat->appends(['allowed' => 1])->links() }}
                                </div>
                            </div><!-- /.row --> --}}
                        @else
                            <p>Tidak Ada Riwayat Peminjaman Buku</p>
                        @endif
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div><!-- /.card -->
    </section><!-- /.Main Content -->
</div><!-- /.content-wrapper -->

@include('layouts.footer')
