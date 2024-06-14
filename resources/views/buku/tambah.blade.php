@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Tambah Buku</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('showBuku') }}">List Buku</a>
                            </li>
                            <li class="breadcrumb-item active">Tambah Buku</li>
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
                        <form action="{{ route('tambahBuku') }}" method="POST" novalidate
                            enctype='multipart/form-data'>
                            @csrf
                            @method('POST')
                            <form id="tambahBukuSementara">
                                <div class="header">
                                    <h6>Informasi Umum Buku</h6>
                                    <p class="text-muted">Pada bagian ini, masukkan informasi umum buku seperti
                                        judul, deskripsi fisik,
                                        kota terbit, dan tahun terbit.
                                    </p>
                                </div>
                                <div class="row mb-4">
                                    <input type="hidden" name="user_id" class="form-control" id="user_id"
                                        value="{{ session('user')['user_id'] }}" required>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="judul" class="col-form-label mr-auto">Judul Buku*</label>

                                            <input type="text" name="buku_judul" class="form-control" id="judul"
                                                value="{{ old('buku_judul') }}" required>
                                            @error('buku_judul')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="deskripsi_fisik" class="col-form-label mr-auto">
                                                Deskripsi Fisik*
                                            </label>

                                            <input type="text" name="buku_deskripsi_fisik" class="form-control"
                                                id="deskripsi_fisik" value="{{ old('buku_deskripsi_fisik') }}" required>
                                            @error('buku_deskripsi_fisik')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="kota_terbit" class="col-form-label mr-2">
                                                Kota Terbit*
                                            </label>

                                            <input type="text" name="buku_kota_terbit" class="form-control"
                                                id="kota_terbit" value="{{ old('buku_kota_terbit') }}" required>
                                            @error('buku_kota_terbit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tahun_terbit" class="col-form-label mr-2">
                                                Tahun Terbit*
                                            </label>

                                            <input type="text" name="buku_tahun_terbit" class="form-control"
                                                id="tahun_terbit" value="{{ old('buku_tahun_terbit') }}" required>
                                            @error('buku_tahun_terbit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="header">
                                    <h6>Informasi Detail Penerbitan Buku</h6>
                                    <p class="text-muted">Pada bagian ini, masukkan informasi detail penerbitan,
                                        termasuk bahasa buku, klasifikasi, nama penerbit, dan subyek buku.
                                    </p>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group mb-2">
                                                <label for="bahasa" class="col-form-label mr-2">Bahasa*</label>

                                                <button type="button" class="btn btn-sm btn-primary mr-2"
                                                    data-toggle="modal" data-target="#tambahBahasaModal">
                                                    <i class="fas fa-plus"></i> Bahasa
                                                </button>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <select name="buku_bahasa" class="form-control select2" required
                                                    style="width: 100%" id="bahasa" data-placeholder="Pilih Bahasa"
                                                    data-allow-clear="1">
                                                    <option value="" selected disabled>Pilih Bahasa</option>
                                                    @foreach ($buku_bahasa as $bahasa)
                                                        <option value="{{ $bahasa->bahasa_id }}"
                                                            {{ old('buku_bahasa') == $bahasa->bahasa_id ? 'selected' : '' }}>
                                                            {{ $bahasa->bahasa_nama }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <i class="mdi mdi-information ml-2" data-toggle="popover"
                                                    data-placement="top" data-html="true"
                                                    data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                                    data-content="<strong>Bahasa Buku (Wajib)</strong><br>Jika bahasa tidak pada pilihan, Anda dapat menambahkannya dengan menggunakan tombol <strong>(+Bahasa)</strong>"
                                                    data-trigger="hover">
                                                </i>
                                            </div>
                                            @error('buku_bahasa')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group mb-2">
                                                <label for="klasifikasi" class="col-form-label mr-2">
                                                    Klasifikasi*
                                                </label>

                                                <button type="button" class="btn btn-sm btn-primary mr-2"
                                                    data-toggle="modal" data-target="#tambahKlasifikasiModal">
                                                    <i class="fas fa-plus"></i> Klasifikasi
                                                </button>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <select name="buku_klasifikasi" class="form-control select2"
                                                    id="klasifikasi" required style="width: 100%"
                                                    data-placeholder="Pilih Klasifikasi" data-allow-clear="1">
                                                    <option value="" selected disabled>Pilih Klasifikasi</option>
                                                    @foreach ($buku_klasifikasi as $klasifikasi)
                                                        <option value="{{ $klasifikasi->klasifikasi_kode }}"
                                                            {{ old('buku_klasifikasi') == $klasifikasi->klasifikasi_kode ? 'selected' : '' }}>
                                                            {{ $klasifikasi->klasifikasi_kode }} -
                                                            {{ $klasifikasi->klasifikasi_nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <i class="mdi mdi-information ml-2" data-toggle="popover"
                                                    data-placement="top" data-html="true"
                                                    data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                                    data-content="<strong>Klasifikasi Buku (Wajib)</strong><br>Jika klasifikasi tidak pada pilihan, Anda dapat menambahkannya dengan menggunakan tombol <strong>(+Klasifikasi)</strong>"
                                                    data-trigger="hover">
                                                </i>
                                            </div>
                                            @error('buku_klasifikasi')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group mb-2">
                                                <label for="penerbit" class="col-form-label mr-2">Penerbit*</label>

                                                <button type="button" class="btn btn-sm btn-primary"
                                                    data-toggle="modal" data-target="#tambahPenerbitModal">
                                                    <i class="fas fa-plus"></i> Penerbit
                                                </button>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <select name="buku_penerbit" class="form-control penerbit-select"
                                                    value="{{ old('buku_penerbit') }}" required style="width: 100%">

                                                </select>

                                                <i class="mdi mdi-information ml-2" data-toggle="popover"
                                                    data-placement="top" data-html="true"
                                                    data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                                    data-content="<strong>Penerbit Buku (Wajib)</strong><br>Jika penerbit tidak pada pilihan, Anda dapat menambahkannya dengan menggunakan tombol <strong>(+Penerbit)</strong>"
                                                    data-trigger="hover">
                                                </i>
                                            </div>
                                            @error('buku_penerbit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group mb-2">
                                                <label for="subyek" class="col-form-label mr-2">Subyek*</label>

                                                <button type="button" class="btn btn-sm btn-primary"
                                                    data-toggle="modal" data-target="#tambahSubyekModal">
                                                    <i class="fas fa-plus"></i> Subyek
                                                </button>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <select name="subyeks[]" class="form-control select2" id="subyek"
                                                    multiple data-placeholder="Pilih Subyek" data-allow-clear="1"
                                                    required style="width: 100%">
                                                    @foreach ($buku_subyek as $subyek)
                                                        <option value="{{ $subyek->subyek_id }}"
                                                            {{ in_array($subyek->subyek_id, old('subyeks', [])) ? 'selected' : '' }}>
                                                            {{ $subyek->subyek_nama }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <i class="mdi mdi-information ml-2" data-toggle="popover"
                                                    data-placement="top" data-html="true"
                                                    data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                                    data-content="<strong>Subyek Buku (Wajib)</strong><br>Jika subyek tidak pada pilihan, Anda dapat menambahkannya dengan menggunakan tombol <strong>(+Subyek)</strong>"
                                                    data-trigger="hover">
                                                </i>
                                            </div>
                                            @error('subyeks')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="header">
                                    <h6>Informasi Tambahan Buku (Opsional)</h6>
                                    <p class="text-muted">Pada bagian ini, masukkan informasi tambahan seperti
                                        edisi,
                                        seri, dan nomor ISBN/ISSN buku di sini.
                                    </p>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="edisi" class="col-form-label mr-2">Edisi</label>

                                            <input type="text" name="buku_edisi" class="form-control"
                                                id="edisi" value="{{ old('buku_edisi') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="seri" class="col-form-label mr-2">Seri</label>

                                            <input type="text" name="buku_seri" class="form-control"
                                                id="seri" value="{{ old('buku_seri') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="isbn_issn" class="col-form-label mr-2">ISBN/ISSN</label>

                                            <input type="text" name="buku_isbn_issn" class="form-control"
                                                id="isbn_issn" value="{{ old('buku_isbn_issn') }}">
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="header">
                                    <h6>Pengarang dan Peran</h6>
                                    <p class="text-muted">Input pengarang buku dan pilih peran mereka, seperti
                                        penulis
                                        atau editor. Tambahkan pengarang tambahan jika diperlukan.
                                    </p>
                                </div>
                                <div class="row mb-4" id="row_pengarang" style="">
                                    @error('pengarangs')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <div class="col-md-5 col-sm-12 mb-2">
                                        <div class="d-flex mb-2">
                                            <label class="mr-2">Pengarang*</label>

                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#tambahPengarangModal">
                                                <i class="fas fa-plus"></i> Pengarang
                                            </button>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <select name="pengarangs[0][pengarang_id]"
                                                class="form-control pengarang-select" style="width: 100%"
                                                required="required">

                                            </select>
                                            {{-- <select name="pengarangs[0][pengarang_id]" class="form-control select2"
                                                style="width: 100%" id="pengarang-table"
                                                data-placeholder="Pilih Pengarang" data-allow-clear="1" required>
                                                <option value="" selected disabled>Pilih Pengarang</option>
                                                @foreach ($buku_pengarang as $item)
                                                    <option value="{{ $item->pengarang_id }}"
                                                        {{ isset($pengarang['buku_pengarang']) && $pengarang['buku_pengarang'] == $item->pengarang_id ? 'selected' : '' }}>
                                                        {{ $item->pengarang_nama }} -
                                                        {{ $item->kategori->kategori_nama }}
                                                    </option>
                                                @endforeach
                                            </select> --}}
                                            <i class="mdi mdi-information ml-2" data-toggle="popover"
                                                data-placement="top" data-html="true"
                                                data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                                data-content="<strong>Pengarang Buku (Wajib)</strong><br>Jika pengarang tidak pada pilihan, Anda dapat menambahkannya dengan menggunakan tombol <strong>(+Pengarang)</strong>"
                                                data-trigger="hover">
                                            </i>
                                        </div>
                                    </div>

                                    <div class="col-md-5 col-sm-12 mb-2">
                                        <div class="d-flex mb-2">
                                            <label class="mr-2">Peran*</label>

                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#tambahPeranModal">
                                                <i class="fas fa-plus"></i> Peran
                                            </button>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <select name="pengarangs[0][peran_id]" class="form-control select2"
                                                style="width: 100%" id="peran-table" data-placeholder="Pilih Peran"
                                                data-allow-clear="1" required>
                                                <option value="" selected disabled>Pilih Peran</option>
                                                @foreach ($pengarang_peran as $peran)
                                                    <option value="{{ $peran->peran_id }}"
                                                        {{ isset($pengarang['pengarang_peran']) && $pengarang['pengarang_peran'] == $peran->peran_id ? 'selected' : '' }}>
                                                        {{ $peran->peran_nama }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <i class="mdi mdi-information ml-2" data-toggle="popover"
                                                data-placement="top" data-html="true"
                                                data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                                data-content="<strong>Peran Pengarang Buku (Wajib)</strong><br>Jika peran tidak pada pilihan, Anda dapat menambahkannya dengan menggunakan tombol <strong>(+Peran)</strong>"
                                                data-trigger="hover">
                                            </i>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-12">
                                        <div class="form-group"data-toggle="popover" data-placement="top"
                                            data-html="true"
                                            data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                            data-content="<strong>Tambah Pengarang Buku</strong><br>Klik untuk menambah pengarang buku."
                                            data-trigger="hover">

                                            <a name="add-row-pengarang" id="add-row-pengarang"
                                                style="color: #20d4b6; font-size: 24px; display: block; text-align: center; margin-top: 32px;">
                                                <i class="ion ion-md-add-circle"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="header">
                                    <h6>Nomor dan Status Buku</h6>
                                    <p class="text-muted">Berikan nomor panggil, nomor eksemplar, tipe koleksi, dan
                                        status buku.
                                    </p>
                                </div>
                                <div class="row mb-4" id="row_buku">
                                    @error('eksemplars.*.eksemplar_no_panggil')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="mr-2">No. Panggil*</label>

                                            <input type="text" name="eksemplars[0][eksemplar_no_panggil]"
                                                class="form-control buku-no-panggil" required
                                                value="{{ isset($buku['eksemplar_no_panggil']) ? $buku['eksemplar_no_panggil'] : null }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="mr-2">No. Eksemplar*</label>

                                            <input type="text" name="eksemplars[0][eksemplar_no_eksemplar]"
                                                class="form-control buku-no-eksemplar" required
                                                value="{{ isset($buku['eksemplar_no_eksemplar']) ? $buku['eksemplar_no_eksemplar'] : null }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-12">
                                        <div class="form-group">
                                            <label class="mr-2">Tipe Koleksi*</label>

                                            <select name="eksemplars[0][eksemplar_tipe_koleksi]"
                                                class="form-control buku-tipe-koleksi select2" style="width: 100%"
                                                data-placeholder="Pilih Tipe" data-allow-clear="1" required>
                                                <option value="" selected disabled>Pilih Tipe Koleksi
                                                </option>
                                                <option value="Teks"
                                                    {{ isset($buku['eksemplar_tipe_koleksi']) && $buku['eksemplar_tipe_koleksi'] == 'Teks' ? 'selected' : '' }}>
                                                    Teks</option>
                                                <option value="Buku"
                                                    {{ isset($buku['eksemplar_tipe_koleksi']) && $buku['eksemplar_tipe_koleksi'] == 'Buku' ? 'selected' : '' }}>
                                                    Buku</option>
                                                <option value="Majalah"
                                                    {{ isset($buku['eksemplar_tipe_koleksi']) && $buku['eksemplar_tipe_koleksi'] == 'Majalah' ? 'selected' : '' }}>
                                                    Majalah</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-12">
                                        <div class="form-group">
                                            <label class="mr-2">Status Buku*</label>

                                            <select name="eksemplars[0][eksemplar_status]"
                                                class="form-control buku-status select2" style="width: 100%"
                                                data-placeholder="Pilih Status" data-allow-clear="1" required>
                                                <option value="" selected disabled>Pilih Status</option>
                                                <option value="0"
                                                    {{ isset($buku['eksemplar_status']) && $buku['eksemplar_status'] == 0 ? 'selected' : '' }}>
                                                    Tidak Tersedia</option>
                                                <option value="1"
                                                    {{ isset($buku['eksemplar_status']) && $buku['eksemplar_status'] == 1 ? 'selected' : '' }}>
                                                    Tersedia</option>
                                                <option value="2"
                                                    {{ isset($buku['eksemplar_status']) && $buku['eksemplar_status'] == 2 ? 'selected' : '' }}>
                                                    Sedang Dipinjam</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-12">
                                        <div class="form-group" data-toggle="popover" data-placement="top"
                                            data-html="true"
                                            data-template='<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>'
                                            data-content="<strong>Tambah Buku</strong><br>Klik untuk menambahkan buku."
                                            data-trigger="hover">

                                            <a name="add-row-buku" id="add-row-buku"
                                                style="color: #20d4b6; font-size: 24px; display: block; text-align: center; margin-top: 32px;">
                                                <i class="ion ion-md-add-circle"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="header">
                                    <h6>Sampul Buku (Opsional)</h6>
                                    <p class="text-muted">Unggah gambar sampul buku di sini untuk memberikan
                                        representasi visual.
                                    </p>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="photo" class="col-form-label mr-2">Sampul Buku</label>

                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-2 mb-3 mb-md-0">
                                                    <img id="previewImage"
                                                        src="{{ asset('storage/public/images/preview.png') }}"
                                                        alt="Preview Image" class="img-fluid"
                                                        style="width: auto; height: auto; border-radius: 5px;">
                                                </div>

                                                <div class="col-12 col-md-10">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <small class="text-muted">Ukuran Gambar Maksimal 500
                                                                KB</small>
                                                        </div>

                                                        <div class="col-12">
                                                            <input type="file" name="buku_cover" id="photo">
                                                            @error('buku_cover')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <a href="{{ route('showBuku') }}" class="btn btn-danger mr-2"
                                            style="color: white">Batal</a>

                                        <a class="btn btn-success" data-toggle="modal" data-target="#tambahModal"
                                            style="color: white">Tambah</a>
                                    </div>
                                </div>

                                <!-- Tambah Modal -->
                                <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog"
                                    aria-labelledby="tambahModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tambahModalLabel">Konfirmasi
                                                    Tambah
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda Yakin Ingin Menambahkan Buku Ini ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Batal</button>

                                                <input type="hidden" name="buku_penanggung_jawab"
                                                    class="form-control" id="penanggung_jawab"
                                                    value="{{ session('user')['user_nama'] }}" required>

                                                <button type="submit" class="btn btn-success">Ya</button>
                                            </div><!-- /.modal-footer -->
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                            </form>
                        </form>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

<!-- Modal Tambah Role -->
<div class="modal fade" id="tambahRoleModal" tabindex="-1" role="dialog" aria-labelledby="tambahRoleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahRoleModalLabel">Tambah Role
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahRole') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Role
                            @error('role_nama')
                                <span class="text-danger">({{ $message }})</span>
                            @enderror
                        </label>
                        <input type="text" name="role_nama"
                            class="form-control @error('role_nama') is-invalid @enderror" id="nama"
                            id="nama" value="{{ old('role_nama') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                        <input type="hidden" name="allowed" value="1">

                        <button type="submit" class="btn btn-success" id="simpanRoleBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Tambah Pengarang -->
<div class="modal fade" id="tambahPengarangModal" tabindex="-1" role="dialog"
    aria-labelledby="tambahPengarangModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPengarangModalLabel">Tambah Pengarang
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-primary ml-2" data-toggle="modal"
                        data-target="#tambahKategoriModal">
                        <i class="fas fa-plus"></i> Kategori Pengarang
                    </button>
                </div>


                <form action="{{ route('tambahPengarangBuku') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="nama">Nama Pengarang</label>
                        <input type="text" name="pengarang_nama" class="form-control" id="nama"
                            value="{{ old('pengarang_nama') }}"required>
                        @error('pengarang_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kategori Pengarang</label>
                        <select name="pengarang_kategori" class="form-control select2" id="kategori"
                            value="{{ old('pengarang_kategori') }}"required>
                            <option value="" selected="selected" disabled>Pilih
                                Kategori Pengarang
                            </option>
                            @foreach ($pengarang_kategori as $kategori)
                                <option value="{{ $kategori->kategori_id }}">
                                    {{ $kategori->kategori_nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('pengarang_kategori')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger ml-auto mr-2" data-dismiss="modal">Batal
                        </button>

                        <button type="submit" class="btn btn-success"data-toggle="modal" style="color: white">Tambah
                        </button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Tambah Penerbit -->
<div class="modal fade" id="tambahPenerbitModal" tabindex="-1" role="dialog"
    aria-labelledby="tambahPenerbitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPenerbitModalLabel">Tambah Penerbit
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahPenerbitBuku') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Penerbit</label>
                        <input type="text" name="penerbit_nama" class="form-control" id="nama"
                            id="nama" value="{{ old('penerbit_nama') }}" required>
                        @error('penerbit_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="simpanPenerbitBtn">Tambah</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                <form action="{{ route('tambahKategoriPengarang') }}" method="POST">
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

<!-- Modal Tambah Peran -->
<div class="modal fade" id="tambahPeranModal" tabindex="-1" role="dialog" aria-labelledby="tambahPeranModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPeranModalLabel">Tambah Peran
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahPeranPengarang') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Peran</label>
                        <input type="text" name="peran_nama" class="form-control" id="nama"
                            value="{{ old('peran_nama') }}"required>
                        @error('peran_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="simpanPeranBtn">Simpan</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Tambah Klasifikasi -->
<div class="modal fade" id="tambahKlasifikasiModal" tabindex="-1" role="dialog"
    aria-labelledby="tambahKlasifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKlasifikasiModalLabel">Tambah Klasifikasi
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahKlasifikasiBuku') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Kode Klasifikasi</label>
                        <input type="text" name="klasifikasi_kode" class="form-control" id="id"
                            value="{{ old('klasifikasi_kode') }}" required>
                        @error('klasifikasi_kode')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nama Klasifikasi</label>
                        <input type="text" name="klasifikasi_nama" class="form-control" id="nama"
                            value="{{ old('klasifikasi_nama') }}" required>
                        @error('klasifikasi_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="simpanKlasifikasiBtn">Tambah</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Tambah Subyek -->
<div class="modal fade" id="tambahSubyekModal" tabindex="-1" role="dialog"
    aria-labelledby="tambahSubyekModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSubyekModalLabel">Tambah Subyek
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahSubyekBuku') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Subyek</label>
                        <input type="text" name="subyek_nama" class="form-control" id="nama" id="nama"
                            value="{{ old('subyek_nama') }}" required>
                        @error('subyek_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="simpanKlasifikasiBtn">Tambah</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Tambah Bahasa -->
<div class="modal fade" id="tambahBahasaModal" tabindex="-1" role="dialog"
    aria-labelledby="tambahBahasaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBahasaModalLabel">Tambah Bahasa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahBahasaBuku') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Bahasa</label>
                        <input type="text" name="bahasa_nama" class="form-control" id="nama" id="nama"
                            value="{{ old('bahasa_nama') }}" required>
                        @error('bahasa_nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="simpanBahasaBtn">Tambah</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(function() {
        $('[data-toggle="popover"]').popover();
    });

    $(document).ready(function() {
        function saveFormData() {
            var formData = {};
            $('input, select, textarea').each(function() {
                formData[$(this).attr('name')] = $(this).val();
            });

            localStorage.setItem('formData', JSON.stringify(formData));
        }


        $('form').on('input change', 'input, select, textarea', function() {
            saveFormData();
        });

        var bukuIndex = 1;

        $("#add-row-buku").on("click", function() {
            var newRow = $('<div class="row buku-row mb-4"></div>');

            //Mengambil nilai dari baris pertama
            var bukuNoPanggil = $('[name="eksemplars[0][eksemplar_no_panggil]"]').val();
            var bukuNoEksemplar = $('[name="eksemplars[0][eksemplar_no_eksemplar]"]').val();
            var tipeKoleksiValue = $('[name="eksemplars[0][eksemplar_tipe_koleksi]"]').val();
            var statusBukuValue = $('[name="eksemplars[0][eksemplar_status]"]').val();

            var inputNoPanggil =
                '<div class="col-md-3 col-sm-12 mb-2"><input type="text" name="eksemplars[' +
                bukuIndex +
                '][eksemplar_no_panggil]" class="form-control buku-no-panggil" required value="' +
                bukuNoPanggil + '"></div>';

            var inputNoEksemplar =
                '<div class="col-md-3 col-sm-12 mb-2"><input type="text" name="eksemplars[' +
                bukuIndex +
                '][eksemplar_no_eksemplar]" class="form-control buku-no-eksemplar" required value="' +
                bukuNoEksemplar + '"></div>';

            var selectTipeKoleksi =
                '<div class="col-md-2 col-sm-12 mb-2"><select name="eksemplars[' +
                bukuIndex +
                '][eksemplar_tipe_koleksi]" class="form-control buku-tipe-koleksi select2" style="width: 100%" data-placeholder="Pilih Tipe" data-allow-clear="1" required>' +
                '<option value="" selected disabled>Pilih Tipe Koleksi</option>' +
                '<option value="Teks" ' + (tipeKoleksiValue === 'Teks' ? 'selected' : '') +
                '>Teks</option>' +
                '<option value="Buku" ' + (tipeKoleksiValue === 'Buku' ? 'selected' : '') +
                '>Buku</option>' +
                '<option value="Majalah" ' + (tipeKoleksiValue === 'Majalah' ? 'selected' : '') +
                '>Majalah</option>' +
                '</select></div>';

            var selectStatusBuku =
                '<div class="col-md-2 col-sm-12 mb-2"><select name="eksemplars[' +
                bukuIndex +
                '][eksemplar_status]" class="form-control buku-status select2" style="width: 100%" data-placeholder="Pilih Status" data-allow-clear="1" required>' +
                '<option value="" selected disabled>Pilih Status</option>' +
                '<option value="0" ' + (statusBukuValue === '0' ? 'selected' : '') +
                '>Tidak Tersedia</option>' +
                '<option value="1" ' + (statusBukuValue === '1' ? 'selected' : '') +
                '>Tersedia</option>' +
                '<option value="2" ' + (statusBukuValue === '2' ? 'selected' : '') +
                '>Sedang Dipinjam</option>' +
                '</select></div>';

            var removeButton =
                '<div class="col-md-1 col-sm-12 mb-4">' +
                '<div class="form-group" ' +
                'data-toggle="popover" ' +
                'data-placement="top" ' +
                'data-html="true" ' +
                'data-template=\'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>\' ' +
                'data-content="<strong>Hapus Buku</strong><br>Klik untuk menghapus buku ini." ' +
                'data-trigger="hover">' +
                '<a class="remove-row-buku" style="color: #fb4365; font-size: 24px; display: block; text-align: center;"><i class="ion ion-md-remove-circle"></i></a>' +
                '</div>' +
                '</div>';

            newRow.append(inputNoPanggil);
            newRow.append(inputNoEksemplar);
            newRow.append(selectTipeKoleksi);
            newRow.append(selectStatusBuku);
            newRow.append(removeButton);

            $('#row_buku').after(newRow);

            newRow.find('.buku-tipe-koleksi').select2();
            newRow.find('.buku-status').select2();

            newRow.find('[data-toggle="popover"]').popover();

            newRow.find('.remove-row-buku').click(function() {
                var popoverElement = $(this).closest('.form-group');
                popoverElement.popover('dispose');
                $(this).closest('.buku-row').remove();
            });

            saveFormData();
            bukuIndex++;
        });

        var pengarangIndex = 1;
        $("#add-row-pengarang").on("click", function() {
            var newRow = $('<div class="row mb-2"></div>');

            var selectPengarang =
                '<div class="col-md-5 col-sm-12 mb-2"><select name="pengarangs[' +
                pengarangIndex +
                '][pengarang_id]" class="form-control pengarang-select" style="width: 100%" required></select></div>';


            var selectPeran =
                '<div class="col-md-5 col-sm-12 mb-2"><select name="pengarangs[' +
                pengarangIndex +
                '][peran_id]" class="form-control select2 peran-select" style="width: 100%" data-placeholder="Pilih Peran" data-allow-clear="1" required><option value="" selected disabled>Pilih Peran</option>@foreach ($pengarang_peran as $peran)<option value="{{ $peran->peran_id }}">{{ $peran->peran_nama }}</option>@endforeach</select></div></div>';

            var removeButton =
                '<div class="col-md-1 col-sm-12 mb-2"><div class="form-group" ' +
                'data-toggle="popover" ' +
                'data-placement="top" ' +
                'data-html="true" ' +
                'data-template=\'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body bg-light text-dark"></div></div>\' ' +
                'data-content="<strong>Hapus Pengarang</strong><br>Klik untuk menghapus pengarang ini." ' +
                'data-trigger="hover">' +
                '<a class="remove-row-pengarang" style="color: #fb4365; font-size: 24px; display: block; text-align: center;"><i class="ion ion-md-remove-circle"></i></a>' +
                '</div></div>';

            console.log(removeButton);

            newRow.append(selectPengarang);
            newRow.append(selectPeran);
            newRow.append(removeButton);

            $('#row_pengarang').after(newRow);

            newRow.find('.pengarang-select').select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: 'Pilih Pengarang',
                theme: 'bootstrap4',
                ajax: {
                    dataType: 'json',
                    url: '/buku/pengarang',
                    delay: 5,
                    cache: true,
                    data: function(params) {
                        return {
                            search: params.term
                        }
                    },
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    },
                }
            });
            newRow.find('.peran-select').select2({
                theme: 'bootstrap4',
            });

            newRow.find('.remove-row-pengarang').click(function() {
                var popoverElement = $(this).closest('.form-group');
                popoverElement.popover('dispose');
                $(this).closest('.row').remove();
            });

            newRow.find('[data-toggle="popover"]').popover();

            pengarangIndex++;
        });

        $('#tambahModal').on('click', '.btn-success', function() {
            localStorage.removeItem('formData');
        });
    });



    document.addEventListener("DOMContentLoaded", function() {
        const photoInput = document.getElementById("photo");
        const previewImage = document.getElementById("previewImage");

        photoInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });
    });
</script>

@include('layouts.footer')
