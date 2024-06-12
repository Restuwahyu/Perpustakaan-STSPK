@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Peminjaman Buku</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Peminjaman Buku</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page-title -->

        <!-- start top-Contant -->
        <div class="row">
            <div class="col-12">
                <div class="card" id="kontenSetelahInput">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table width="100%" cellpadding="5" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td colspan="5">
                                                <form method="POST" action="{{ route('selesaiTransaksi') }}">
                                                    @csrf
                                                    <a class="btn btn-danger"data-toggle="modal"
                                                        data-target="#selesaiTransaksiModal"
                                                        style="color: white">Selesai Transaksi
                                                    </a>
                                                    <div class="modal fade" id="selesaiTransaksiModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="selesaiTransaksiModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="selesaiTransaksiModalLabel">
                                                                        Konfirmasi Selesai Transaksi
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div><!-- /.modal-header -->
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin Ingin Menyelesaikan Transaksi?
                                                                </div><!-- /.modal-body -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Batal</button>

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
                                            <td class="alterCell" width="15%">
                                                <b>Nama Anggota:</b>
                                            </td>
                                            <td class="alterCell2" width="30%">
                                                {{ $member->member_nama }}
                                            </td>
                                            <td class="alterCell" width="15%">
                                                <strong>ID Anggota:</strong>
                                            </td>
                                            <td class="alterCell2" width="30%">
                                                {{ $member->member_kode }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="alterCell" width="15%">
                                                <strong>Alamat Anggota:</strong>
                                            </td>
                                            <td class="alterCell2" width="30%">
                                                {{ $member->member_alamat }}
                                            </td>
                                            <td class="alterCell" width="15%">
                                                <strong>Email Anggota:</strong>
                                            </td>
                                            <td class="alterCell2" width="30%">
                                                {{ $member->member_email }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="alterCell" width="15%">
                                                <strong>Tanggal Registrasi:</strong>
                                            </td>
                                            <td class="alterCell2" width="30%">
                                                {{ \Carbon\Carbon::parse($member->member_tanggal_registrasi)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                            </td>
                                            <td class="alterCell" width="15%">
                                                <strong>Berlaku Hingga:</strong>
                                            </td>
                                            <td class="alterCell2" width="30%">
                                                {{ \Carbon\Carbon::parse($member->member_tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.card (kontenSetelahInput) -->

                <div class="col-12">
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

                <!-- Card Navigation -->
                <div class="card text-center">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="peminjamanTab" data-toggle="tab"
                                    href="#peminjamanContent">
                                    Peminjaman
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pinjamanSaatIniTab" data-toggle="tab"
                                    href="#pinjamanSaatIniContent">
                                    Peminjaman Saat Ini
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
                    <div class="card-body ">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="peminjamanContent"
                                @if (session('activeTab') == 'peminjamanTab') class="show active" @endif>
                                <div class="row" id="inputIdBuku">
                                    <div class="col-md-12 mb-4">
                                        <form method="POST" action="{{ route('tambahPeminjamanSementara') }}"
                                            id="tambahBukuSementaraForm">
                                            @csrf
                                            <input type="hidden" name="user_id" class="form-control" id="user_id"
                                                value="{{ session('user')['user_id'] }}" required>

                                            <input type="hidden" name="member_kode"
                                                value="{{ $member->member_kode }}">
                                            <input type="hidden" name="member_id" value="{{ $member->member_id }}">

                                            <div class="form-row align-items-center" id="scan_barcode"
                                                style="display: flex">
                                                <div class="col-4">
                                                    <label for="barcode" class="form-label text-left">
                                                        Scan Barcode Buku
                                                    </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" class="form-control" name="eksemplar_kode"
                                                        id="barcode" placeholder="Masukkan Scan Barcode" autofocus>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                                @if (!empty($loanBooksTemp))
                                    <table id="peminjaman_table"
                                        class="table table-bordered dt-responsive text-center"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No. Eksemplar</th>
                                                <th>Judul</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($loanBooksTemp as $loanBookTemp)
                                                <tr>
                                                    <td>
                                                        {{ $loanBookTemp['peminjaman_eksemplar']->eksemplar_no_eksemplar }}
                                                    </td>
                                                    <td class="text-left">
                                                        {{ utf8_decode($loanBookTemp['peminjaman_eksemplar']->buku->buku_judul) }}
                                                    </td>
                                                    <td>
                                                        {{ $loanBookTemp['peminjaman_tgl_pinjam']->locale('id')->isoFormat('D MMMM Y') }}
                                                    </td>
                                                    <td>
                                                        {{ $loanBookTemp['peminjaman_tgl_kembali']->locale('id')->isoFormat('D MMMM Y') }}
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('deletePeminjamanSementara') }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id_peminjaman_delete"
                                                                value="{{ $loanBookTemp['id'] }}">
                                                            <input type="hidden" name="member_kode"
                                                                value="{{ $loanBookTemp['peminjaman_member']->member_kode }}">
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                style="color: white">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div><!-- /.tab-pane -->
                            <div class="tab-pane fade" id="pinjamanSaatIniContent">
                                @if (!empty($peminjamans_id))
                                    <table id="peminjamanSaatIni_table"
                                        class="table table-bordered dt-responsive text-center"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No. Eksemplar</th>
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
                                                        <td>{{ $peminjaman->eksemplar->eksemplar_no_eksemplar }}</td>
                                                        <td class="text-left">
                                                            {{ utf8_decode($peminjaman->eksemplar->buku->buku_judul) }}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($peminjaman->peminjaman_tgl_kembali)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu --}}
                                                        <td>
                                                            <div
                                                                class="d-flex justify-content-center align-items-center">
                                                                <form action="{{ route('updatePinjamBuku') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <a class="btn btn-sm btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#kembaliModal{{ $peminjaman->peminjaman_id }}"
                                                                        style="color: white">
                                                                        Kembali
                                                                    </a>
                                                                    <!-- Kembali Modal -->
                                                                    <div class="modal fade text-left"
                                                                        id="kembaliModal{{ $peminjaman->peminjaman_id }}"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="kembaliModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="kembaliModalLabel">
                                                                                        Konfirmasi Kembali Peminjaman
                                                                                        Buku
                                                                                    </h5>
                                                                                    <button type="button"
                                                                                        class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close">
                                                                                        <span
                                                                                            aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <!-- /.modal-header -->
                                                                                <div class="modal-body"
                                                                                    style="white-space: normal; overflow: auto; max-height: 300px;">
                                                                                    Apakah Anda Yakin Ingin
                                                                                    Mengembalikan Buku
                                                                                    {{ utf8_decode($peminjaman->eksemplar->buku->buku_judul) }}
                                                                                    Ini ?
                                                                                </div><!-- /.modal-body -->
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger"
                                                                                        data-dismiss="modal">Batal
                                                                                    </button>
                                                                                    <input type="hidden"
                                                                                        name="id_peminjaman_edit"
                                                                                        value="{{ $peminjaman->peminjaman_id }}">
                                                                                    <button type="submit"
                                                                                        class="btn btn-success">Ya
                                                                                    </button>
                                                                                </div><!-- /.modal-footer -->
                                                                            </div><!-- /.modal-content -->
                                                                        </div><!-- /.modal-dialog -->
                                                                    </div><!-- /.modal -->
                                                                </form>
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
                                @if (!empty($peminjamans_riwayat))
                                    <table id="peminjamanRiwayat_table"
                                        class="table table-bordered dt-responsive text-center"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No. Eksemplar</th>
                                                <th>Judul</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($peminjamans_riwayat as $peminjaman)
                                                <tr>
                                                    <td>{{ $peminjaman->eksemplar->eksemplar_no_eksemplar }}</td>
                                                    <td class="text-left">
                                                        {{ utf8_decode($peminjaman->eksemplar->buku->buku_judul) }}
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
                                                            Detail Buku
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Modal -->
                                                <div class="modal fade"
                                                    id="detailModal{{ $peminjaman->peminjaman_id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="detailModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="detailModalLabel">Detail
                                                                    Buku</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
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
                                                                            $peminjaman->eksemplar->buku->buku_cover_original &&
                                                                                file_exists(storage_path(
                                                                                        'app/public/images/cover_original/' . basename($peminjaman->eksemplar->buku->buku_cover_original))))
                                                                            <img src="{{ asset($peminjaman->eksemplar->buku->buku_cover_original) }}"
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
                                                                            {{ utf8_decode($peminjaman->eksemplar->buku->buku_judul) }}
                                                                        </div>
                                                                        <div class="subtitle">
                                                                            @foreach ($peminjaman->eksemplar->buku->pengarangs as $index => $pengarang)
                                                                                <small class="text-muted"
                                                                                    style="font-size: 15px;">
                                                                                    {{ $pengarang->pengarang_nama }}
                                                                                    ({{ $pengarang->kategori->kategori_nama }})
                                                                                    -
                                                                                    {{ $peminjaman->eksemplar->buku->perans[$index]->peran_nama }}
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
                                                                                    {{ $peminjaman->eksemplar->buku->buku_seri ?? '-' }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    No. Panggil:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->eksemplar_no_panggil }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    Penerbit:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->buku->penerbit->penerbit_nama ?? '-' }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    Tahun Terbit:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->buku->buku_tahun_terbit ?? '-' }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    Deskripsi Fisik:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->buku->buku_deskripsi_fisik ?? '-' }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    Bahasa:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->buku->bahasa->bahasa_nama ?? '-' }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    ISBN/ISSN:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->buku->buku_isbn_issn ?? '-' }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    Klasifikasi:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->buku->klasifikasi->klasifikasi_nama ?? '-' }}
                                                                                </dd>

                                                                                <dt class="col-sm-4 fw-700">
                                                                                    Edisi:
                                                                                </dt>
                                                                                <dd class="col-sm-8">
                                                                                    {{ $peminjaman->eksemplar->buku->buku_edisi ?? '-' }}
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
                                @else
                                    <p>Tidak Ada Riwayat Peminjaman Buku</p>
                                @endif
                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                    </div><!-- /.card-body -->


                </div><!-- /.col -->
            </div><!-- /.row -->
            <!-- end top-Contant -->

        </div>
        <!-- container-fluid -->

    </div>
    <!-- content -->

    <script>
        const bukuBarcodeInput = document.getElementById('barcode');
        const tambahBukuSementaraForm = document.getElementById('tambahBukuSementaraForm');

        bukuBarcodeInput.addEventListener('input', function(event) {
            const inputValue = bukuBarcodeInput.value.trim();
            console.log(inputValue.length);
            if (inputValue.length === 13) {
                event.preventDefault();
                tambahBukuSementaraForm.submit();
            }
        });

        bukuBarcodeInput.addEventListener('keydown', function(event) {
            console.log(bukuBarcodeInput.value);

            if (event.key === 'Enter') {
                event.preventDefault();
                tambahBukuSementaraForm.submit();
            }
        });
    </script>

    @include('layouts.footer')
