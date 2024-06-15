@include('list_buku.header')

<!--<main>-->
<section class="container mt-8">
    <div class="card mt-5 mb-5 p-4">
        <div class="row">
            <div class="col-md-3 d-flex align-items-center justify-content-center">
                @if (
                    $bukuData['buku']->buku_cover_original &&
                        file_exists(storage_path('app/public/images/cover_original/' . basename($bukuData['buku']->buku_cover_original))))
                    <div class="image-container text-center border rounded"
                        style="width: 170px; height: 230px; overflow: hidden;">
                        <img src="{{ asset($bukuData['buku']->buku_cover_original) }}" class="img-fluid" alt="Buku Cover"
                            style=" width: 100%; height: 100%;">
                    </div>
                @else
                    <div class="image-container text-center border rounded"
                        style="width: 170px; height: 230px; overflow: hidden;">
                        <img id="previewImage" src="{{ asset('storage/images/preview.png') }}" class="img-fluid"
                            alt="Default Cover" style="width: 100%; height: 100%;">
                    </div>
                @endif
            </div>

            <div class="col-md-9" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div class="judulPengarangContainer">
                    <h4 style="margin-bottom: 0;">{{ utf8_decode($bukuData['buku']->buku_judul) }}</h4>
                    <form action="{{ route('searchBuku') }}" method="get" id="searchForm">
                        <footer class="blockquote-footer" style="font-size: 15px">
                            @foreach ($bukuData['pengarangs'] as $pengarangData)
                                <a href="#" class="searchLink"
                                    data-keywords="{{ $pengarangData['pengarang']->pengarang_nama }}">
                                    {{ utf8_decode($pengarangData['pengarang']->pengarang_nama) }}
                                    ({{ $pengarangData['pengarang']->kategori->kategori_nama }})
                                </a> - {{ $pengarangData['peran']->peran_nama }}

                                @unless ($loop->last)
                                    ;
                                @endunless
                            @endforeach
                            <input type="hidden" name="keywords" id="keywordsInput">
                        </footer>
                    </form>
                </div>
            </div>
        </div>


        <hr class="mt-4 mb-2">

        <h6 class="mt-3">Ketersediaan</h6>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No. Panggil</th>
                    <th>No. Eksemplar</th>
                    <th>Status Buku</th>
                    <th style="width:50px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bukuData['buku']->eksemplars as $eksemplar)
                    <tr>
                        <td>{{ $eksemplar->eksemplar_no_panggil }}</td>
                        <td> {{ $eksemplar->eksemplar_no_eksemplar }}</td>
                        <td class="text-center">
                            @if ($eksemplar->eksemplar_status == 0)
                                <span class="badge badge-danger">Tidak Tersedia</span>
                            @elseif ($eksemplar->eksemplar_status == 1)
                                <span class="badge badge-success">Tersedia</span>
                            @elseif ($eksemplar->eksemplar_status == 2)
                                <span class="badge badge-danger">Sedang Dipinjam</span>
                            @elseif ($eksemplar->eksemplar_status == 3)
                                <span class="badge badge-warning">Sedang Dipesan</span>
                            @endif
                        </td>
                        <td class="text-left">
                            @if ($eksemplar->eksemplar_status != 1)
                                <div class="pemesananBukuContainer">
                                    <button type="button" class="btn btn-secondary waves-effect waves-light"
                                        data-toggle="modal" data-target="#pemesananModal" disabled>
                                        <i class="fas fa-shopping-basket"></i>
                                    </button>
                                </div>
                            @else
                                <div class="pemesananBukuContainer">
                                    <button type="button" class="btn btn-primary waves-effect waves-light"
                                        data-toggle="modal"
                                        data-target="#pemesananModal-{{ $eksemplar->eksemplar_id }}">
                                        <i class="fas fa-shopping-basket"></i>
                                    </button>
                                </div>
                            @endif

                            <!-- Modal -->
                            <div class="modal" id="pemesananModal-{{ $eksemplar->eksemplar_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="pemesananModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="pemesananModalLabel-{{ $eksemplar->eksemplar_id }}">Formulir
                                                Pemesanan Buku
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="submitForm-{{ $eksemplar->eksemplar_id }}"
                                            action="{{ route('pemesananBuku') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="tanggalPemesanan">Tanggal Pemesanan<span
                                                            class="text-danger"
                                                            id="errorTanggalPemesanan"></span></label>
                                                    <input type="date" class="form-control" id="tanggalPemesanan"
                                                        name="tanggal_pemesanan" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tanggalPengambilan">Tanggal Pengambilan<span
                                                            class="text-danger"
                                                            id="errorTanggalPengambilan"></span></label>
                                                    <input type="date" class="form-control" id="tanggalPengambilan"
                                                        name="tanggal_pengambilan" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Batal</button>
                                                <input type="hidden" name="eksemplar_id" id="eksemplar_id"
                                                    value="{{ $eksemplar->eksemplar_id }}">
                                                <button type="submit" class="btn btn-success" id="pesanBukuBtn">Pesan
                                                    Buku</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="mt-4 mb-2">

        <h6>Informasi Detail</h6>
        <div class="detail mt-2">
            <dl class="row">
                <dt class="col-sm-2 fw-300">Judul Seri</dt>
                <dd class="col-sm-10"> : {{ $bukuData['buku']->buku_seri ?? '-' }}</dd>

                <dt class="col-sm-2 fw-300">No. Panggil</dt>
                <dd class="col-sm-10">
                    : {{ $bukuData['buku']->eksemplar_no_panggil ?? '-' }}</dd>

                <dt class="col-sm-2 fw-300">Penerbit</dt>
                <dd class="col-sm-10">
                    : {{ $bukuData['buku']->penerbit->penerbit_nama ?? '-' }}
                </dd>

                <dt class="col-sm-2 fw-300">Tahun Terbit</dt>
                <dd class="col-sm-10"> : {{ $bukuData['buku']->buku_tahun_terbit ?? '-' }}
                </dd>

                <dt class="col-sm-2 fw-300">Deskripsi Fisik</dt>
                <dd class="col-sm-10"> : {{ $bukuData['buku']->buku_deskripsi_fisik ?? '-' }}
                </dd>

                <dt class="col-sm-2 fw-300">Bahasa</dt>
                <dd class="col-sm-10"> : {{ $bukuData['buku']->bahasa->bahasa_nama ?? '-' }}</dd>

                <dt class="col-sm-2 fw-300">ISBN/ISSN</dt>
                <dd class="col-sm-10"> : {{ $bukuData['buku']->buku_isbn_issn ?? '-' }}</dd>

                <dt class="col-sm-2 fw-300">Kategori</dt>
                <dd class="col-sm-10">
                    <form action="{{ route('searchBuku') }}" method="get" id="searchForm">
                        <a href="#" class="searchLink"
                            data-keywords="{{ $bukuData['buku']->klasifikasi->klasifikasi_nama }}">
                            : {{ $bukuData['buku']->klasifikasi->klasifikasi_nama ?? '-' }}
                        </a>
                        <br>
                        <input type="hidden" name="keywords" id="keywordsInput">
                    </form>
                </dd>

                <dt class="col-sm-2 fw-300">Edisi</dt>
                <dd class="col-sm-10"> : {{ $bukuData['buku']->buku_edisi ?? '-' }}</dd>

                <dt class="col-sm-2 fw-300">Subyek</dt>
                <dd class="col-sm-10">
                    <form action="{{ route('searchBuku') }}" method="get" id="searchForm">
                        @foreach ($bukuData['buku']->subyeks as $subyek)
                            <a href="#" class="searchLink" data-keywords="{{ $subyek->subyek_nama }}">
                                : {{ $subyek->subyek_nama }}
                            </a>
                            <br>
                        @endforeach
                        <input type="hidden" name="keywords" id="keywordsInput">
                    </form>
                </dd>
            </dl>
        </div>
    </div>
</section>

<hr>

<script>
    $(document).ready(function() {
        $('#submitForm-{{ $eksemplar->eksemplar_id }}').on('submit', function(event) {
            console.log('Form submitted'); // Debugging


        });
    });

    document.getElementById("pesanBukuBtn").addEventListener("click", function() {
        event.preventDefault();

        const form = document.getElementById("submitForm-{{ $eksemplar->eksemplar_id }}");
        const tanggalPemesanan = document.getElementById("tanggalPemesanan");
        const tanggalPengambilan = document.getElementById("tanggalPengambilan");

        const errorTanggalPemesanan = document.getElementById("errorTanggalPemesanan");
        const errorTanggalPengambilan = document.getElementById("errorTanggalPengambilan");

        let isValid = true;

        errorTanggalPemesanan.textContent = "";
        errorTanggalPengambilan.textContent = "";

        if (!tanggalPemesanan.value) {
            errorTanggalPemesanan.textContent = " (Tanggal Pemesanan harus diisi)";
            isValid = false;
        }

        if (!tanggalPengambilan.value) {
            errorTanggalPengambilan.textContent = " (Tanggal Pengambilan harus diisi)";
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success mr-2",
                cancelButton: "btn btn-danger",
            },
            buttonsStyling: false,
        });

        @if (!session()->has('member'))
            swalWithBootstrapButtons.fire({
                title: "Peringatan!",
                text: "Anda harus login terlebih dahulu untuk melakukan pemesanan buku.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Login",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.value) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        @else

            const eksemplarId = document.getElementById("eksemplar_id").value;
            console.log(eksemplarId);

            document.getElementById("submitForm-" + eksemplarId).submit();
        @endif
    });
</script>
@include('list_buku.footer')
