@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Riwayat Buku Keluar</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Riwayat Buku Keluar</li>
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
                        <table id="riwayatBuku_table" class="table table-bordered dt-responsive text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>No. Eksemplar</th>
                                    <th>ID Anggota</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayatBukus as $riwayatBuku)
                                    <tr>
                                        <td>{{ $riwayatBuku->eksemplar->eksemplar_no_eksemplar }}</td>
                                        <td>{{ $riwayatBuku->member->member_kode }}
                                            <br>
                                            ({{ $riwayatBuku->member->member_nama }})
                                        </td>
                                        <td class="text-left">
                                            {{ utf8_decode($riwayatBuku->eksemplar->buku->buku_judul) }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($riwayatBuku->peminjaman_tgl_pinjam)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($riwayatBuku->peminjaman_tgl_kembali)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td>
                                            @if ($riwayatBuku->peminjaman_status == 0)
                                                <span class="badge badge-success">Sudah Dikembalikan</span>
                                            @elseif($riwayatBuku->peminjaman_status == 1)
                                                <span class="badge badge-danger">Belum Dikembalikan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm detail-button btn-primary" data-toggle="modal"
                                                data-target="#detailModal{{ $riwayatBuku->peminjaman_id }}">
                                                Detail Buku
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="detailModal{{ $riwayatBuku->peminjaman_id }}"
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
                                                                $riwayatBuku->eksemplar->buku->buku_cover_original &&
                                                                    file_exists(public_path('storage/images/' . basename($riwayatBuku->eksemplar->buku->buku_cover_original))))
                                                                <img src="{{ asset($riwayatBuku->eksemplar->buku->buku_cover_original) }}"
                                                                    class="img img-responsive"
                                                                    style="width:230px; height: 350px; border-radius: 5px;">
                                                            @else
                                                                <img id="previewImage" src="/storage/images/preview.png"
                                                                    alt="Preview Image" class=" img img-responsive"
                                                                    style="width:230px; height: 350px; border-radius: 5px;">
                                                            @endif
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="title"
                                                                style="font-weight: bold; font-size: 20px">
                                                                {{ utf8_decode($riwayatBuku->eksemplar->buku->buku_judul) }}
                                                            </div>
                                                            <div class="subtitle">
                                                                @foreach ($riwayatBuku->eksemplar->buku->pengarangs as $index => $pengarang)
                                                                    <small class="text-muted" style="font-size: 15px;">
                                                                        <strong>{{ utf8_decode($pengarang->pengarang_nama) }}</strong>
                                                                        ({{ $pengarang->kategori->kategori_nama }})
                                                                        -
                                                                        {{ $riwayatBuku->eksemplar->buku->perans[$index]->peran_nama }}
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
                                                                        {{ $riwayatBuku->eksemplar->buku->buku_seri ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        No. Panggil:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->eksemplar_no_panggil ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Penerbit:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->buku->penerbit->penerbit_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Tahun Terbit:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->buku->buku_tahun_terbit ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Deskripsi Fisik:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->buku->buku_deskripsi_fisik ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Bahasa:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->buku->bahasa->bahasa_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        ISBN/ISSN:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->buku->buku_isbn_issn ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Klasifikasi:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->buku->klasifikasi->klasifikasi_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Edisi:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $riwayatBuku->eksemplar->buku->buku_edisi ?? '-' }}
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
        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

@include('layouts.footer')
