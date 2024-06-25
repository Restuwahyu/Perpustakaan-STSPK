<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Seminari Tinggi Kentungan</title>
    <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logoicon.ico') }}">

    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Sweet Alert -->
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/sweet-alert2/sweetalert2.css') }}" rel="stylesheet"
        type="text/css">

    {{-- <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css"> --}}

    <link href="{{ asset('storage/style.css') }}" rel="stylesheet" type="text/css">

    <!-- jQuery  -->
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/waves.min.js') }}"></script>
</head>

<body>
    <header class="menu-navbar">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
            <div class="container">
                <a class="navbar-brand" href="{{ route('showListBuku') }}">
                    <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logolight.png') }}"
                        class="logo-lg" alt="" height="50">
                    <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logosm.png') }}"
                        class="logo-sm" alt="" height="50">
                </a>

                <!-- Tombol toggler untuk perangkat seluler -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Elemen pencarian di tengah -->
                <div class="collapse navbar-collapse" style="width: 100%" id="navbarNav">
                    <div class="col-lg-10  col-md-12 mt-4">
                        <div id="search-wraper" class="search">
                            <div class="card border-0 shadow">
                                <div class="card-body" style="background: white; border-radius: 5px; shadow">
                                    <form action="{{ route('searchBuku') }}" method="get" id="search-form">
                                        <input value="{{ old('keywords') }}" type="text" id="search-input"
                                            name="keywords" autocomplete="off"
                                            placeholder="Masukkan kata kunci untuk mencari koleksi..."
                                            class="input-transparent w-100" style="border: none; outline: none">
                                    </form>
                                </div>

                                <div id="advanced-wraper" class="advanced-wraper shadow mt-4 text-left"
                                    style="display: none;">
                                    <div></div>
                                    <p class="label mb-2">
                                        <i class="far fa-times-circle float-right text-danger cursor-pointer"></i>
                                    </p>
                                    <div class="d-flex flex-wrap">
                                        <a data-toggle="modal" data-target="#advanced-modal"
                                            class="btn btn-outline-primary mr-2 mb-2">
                                            Pencarian Spesifik
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <div class="text-center">
                            @if (session()->has('member'))
                                <a href="{{ route('dashboardMember') }}" style="color: white">
                                    <i class="fas fa-shopping-basket fa-2x"></i>
                                </a>
                            @else
                                <div class="btn-group btn-block" role="group" aria-label="Group buttons">
                                    <a href="{{ route('register') }}" class="btn  btn-primary"
                                        style="color: white">Daftar</a>
                                    <a href="{{ route('login') }}" class="btn btn-primary"
                                        style="color: white">Masuk</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Modal Pencarian Spesifik -->
        <div class="modal fade" id="advanced-modal" tabindex="-1" role="dialog" aria-labelledby="advanced-modal-label"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="advanced-modal-label">Pencarian Spesifik</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('searchBuku') }}" method="get" class="row">
                            <!-- Sisi Kiri -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        placeholder="Masukkan judul">
                                </div>
                                <div class="form-group">
                                    <label for="pengarang">Pengarang</label>
                                    <input type="text" class="form-control" id="pengarang" name="pengarang"
                                        placeholder="Masukkan pengarang">
                                </div>
                                <div class="form-group">
                                    <label for="penerbit">Penerbit</label>
                                    <input type="text" class="form-control" id="penerbit" name="penerbit"
                                        placeholder="Masukkan penerbit">
                                </div>
                            </div>
                            <!-- Sisi Kanan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tahun_terbit">Tahun Terbit</label>
                                    <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit"
                                        placeholder="Masukkan tahun terbit">
                                </div>
                                <div class="form-group">
                                    <label for="isbn_issn">ISBN/ISSN</label>
                                    <input type="text" class="form-control" id="isbn_issn" name="isbn_issn"
                                        placeholder="Masukkan ISBN/ISSN">
                                </div>
                                <div class="form-group">
                                    <label for="tipe_koleksi">Tipe Koleksi</label>
                                    <select class="form-control" id="tipe_koleksi" name="tipe_koleksi">
                                        <option value="">Semua Koleksi
                                        </option>
                                        <option value="Teks">Teks</option>
                                        <option value="Buku">Buku</option>
                                        <option value="Majalah">Majalah</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Pojok Kanan Bawah -->
                            <div class="col-12 text-right mt-3">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
