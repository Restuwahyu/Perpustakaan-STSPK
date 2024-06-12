@include('member.layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Pemesanan Buku</h4>
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
                        <table id="pemesananBuku_table" class="table table-bordered dt-responsive text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Judul Buku</th>
                                    <th>Tgl. Pemesanan</th>
                                    <th>Tgl. Pengambilan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemesanans as $pemesanan)
                                    <tr>
                                        <td class="text-left">
                                            {{ utf8_decode($pemesanan->bukus->buku_judul) }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($pemesanan->pemesanan_buku_tanggal_pemesanan)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($pemesanan->pemesanan_buku_tanggal_pengambilan)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td>
                                            @if ($pemesanan->pemesanan_buku_status == 0)
                                                <span class="badge badge-warning">Menunggu Konfirmasi Petugas</span>
                                            @elseif($pemesanan->pemesanan_buku_status == 1)
                                                <span class="badge badge-success">Peminjaman Buku Telah Selesai</span>
                                            @elseif($pemesanan->pemesanan_buku_status == 2)
                                                <span class="badge badge-success">Buku Sudah Dapat Diambil</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm detail-button btn-primary" data-toggle="modal"
                                                data-target="#detailModal{{ $pemesanan->pemesanan_buku_id }}">
                                                <i class="fas fa-book-open"></i>
                                            </button>

                                            @if ($pemesanan->pemesanan_buku_status == 0)
                                                <button class="btn btn-sm detail-button btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal{{ $pemesanan->pemesanan_buku_id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="detailModal{{ $pemesanan->pemesanan_buku_id }}"
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
                                                                $pemesanan->bukus->buku_cover_original &&
                                                                    file_exists(storage_path('app/public/images/cover_original/' . basename($pemesanan->bukus->buku_cover_original))))
                                                                <img src="{{ asset($pemesanan->bukus->buku_cover_original) }}"
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
                                                                {{ utf8_decode($pemesanan->bukus->buku_judul) }}
                                                            </div>
                                                            <div class="subtitle">
                                                                @foreach ($pemesanan->bukus->pengarangs as $index => $pengarang)
                                                                    <small class="text-muted" style="font-size: 15px;">
                                                                        <strong>{{ utf8_decode($pengarang->pengarang_nama) }}</strong>
                                                                        ({{ $pengarang->kategori->kategori_nama }})
                                                                        -
                                                                        {{ $pemesanan->bukus->perans[$index]->peran_nama }}
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
                                                                        {{ $pemesanan->bukus->buku_seri ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        No. Panggil:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->eksemplars->eksemplar_no_panggil ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Penerbit:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->bukus->penerbit->penerbit_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Tahun Terbit:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->bukus->buku_tahun_terbit ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Deskripsi Fisik:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->bukus->buku_deskripsi_fisik ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Bahasa:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->bukus->bahasa->bahasa_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        ISBN/ISSN:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->bukus->buku_isbn_issn ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Klasifikasi:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->bukus->klasifikasi->klasifikasi_nama ?? '-' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 fw-700">
                                                                        Edisi:
                                                                    </dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $pemesanan->bukus->buku_edisi ?? '-' }}
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

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $pemesanan->pemesanan_buku_id }}"
                                        tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        iid="deleteModal{{ $pemesanan->pemesanan_buku_id }}">
                                                        Konfirmasi
                                                        Hapus Pemesanan Buku
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div><!-- /.modal-header -->
                                                <div class="modal-body">Apakah Anda yakin ingin membatalkan pemesanan
                                                    buku {{ $pemesanan->bukus->buku_judul }}?</div>
                                                <!-- /.modal-body -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Batal</button>
                                                    <form action="{{ route('pemesananBuku.delete') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="pemesanan_id"
                                                            value="{{ $pemesanan->pemesanan_buku_id }}">
                                                        <button type="submit" class="btn btn-success">Ya</button>
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
        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

@include('member.layouts.footer')
