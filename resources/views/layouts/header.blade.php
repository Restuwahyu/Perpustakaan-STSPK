<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Seminari Tinggi Kentungan</title>
    <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logoicon.ico') }}">

    <!-- TEMPLATE Zegva -->
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet">

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/morris/morris.css') }}">

    <!-- DataTables -->
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/select.dataTables.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link type="text/css" href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/dataTables.checkboxes.css') }}"
        rel="stylesheet" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/datatables/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />


    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/metismenu.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/icons.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/css/style.css') }}" rel="stylesheet"
        type="text/css">

    <!-- Select2 -->
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    {{-- <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/select2/select2.bootstrap4.min.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/select2/select2.bootstrap4.css') }}" rel="stylesheet" />


    <!-- jQuery  -->
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/js/waves.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('Zegva/Zegva_v1.0.0/Admin/plugins/select2/script.js') }}"></script>

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="{{ route('showListBuku') }}" class="logo">
                    <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logolight.png') }}"
                        class="logo-lg" alt="" height="50">
                    <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logosm.png') }}"
                        class="logo-sm" alt="" height="30">
                </a>
            </div>

            <nav class="navbar-custom">
                <ul class="navbar-right list-inline float-right mb-0">
                    <!-- full screen -->
                    <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                        <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                            <i class="fas fa-expand noti-icon"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list list-inline-item">
                        <div class="dropdown notification-list nav-pro-img">
                            <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/horizontal/assets/images/users/user-1.png') }}"
                                    alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                                <!-- item-->
                                <a class="dropdown-item" href="{{ route('showGantiPassword') }}">
                                    <i class="fas fa-key"></i><span> Ganti Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" data-toggle="modal" data-target="#logoutModal"><i
                                        class="mdi mdi-power text-danger"></i>
                                    Logout</a>
                            </div>
                        </div>
                    </li>

                </ul>

                <ul class="list-inline menu-left mb-0">
                    <li class="float-left">
                        <button class="button-menu-mobile open-left waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                </ul>

            </nav>

        </div>
        <!-- Top Bar End -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu" id="side-menu">
                        <li class="menu-title">Menu</li>
                        <li>
                            <a href="{{ route('home') }}" class="waves-effect">
                                <i class="fas fa-tachometer-alt"></i><span> Dashboard
                            </a>
                        </li>

                        @if (Auth::user()->user_role == 1)
                            <li>
                                <a href="#" class="waves-effect"><i class="fas fa-user"></i><span> User <span
                                            class="float-right menu-arrow"><i
                                                class="mdi mdi-chevron-right"></i></span>
                                    </span>
                                </a>
                                <ul class="submenu">
                                    <li>
                                        <a href="{{ route('showUser') }}">List User</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('tambahUser') }}">Tambah User</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('showUserRole') }}">List Role User</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <li>
                            <a href="#" class="waves-effect"><i class="fas fa-users"></i><span> Member <span
                                        class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                                </span>
                            </a>
                            <ul class="submenu">
                                <li>
                                    <a href="{{ route('showMember') }}">List Member Aktif</a>
                                </li>

                                <li>
                                    <a href="{{ route('showExpMember') }}">List Member Tidak
                                        Aktif</a>
                                </li>

                                <li>
                                    <a href="{{ route('tambahMember') }}">Tambah Member</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('showPenerbit') }}" class="waves-effect">
                                <i class="mdi mdi-book-open-variant"></i><span>Penerbit
                            </a>
                        </li>

                        <li>
                            <a href="#" class="waves-effect"><i class="mdi mdi-book-open"></i><span> Pengarang
                                    <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                                </span>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="{{ route('showPengarang') }}">List Pengarang</a>
                                </li>

                                <li>
                                    <a href="{{ route('showKategori') }}">List Kategori</a>
                                </li>

                                <li>
                                    <a href="{{ route('showPeran') }}">List Peran</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="waves-effect"><i class="mdi mdi-book"></i><span> Buku <span
                                        class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                                </span>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="{{ route('showBuku') }}">List Buku</a>
                                </li>

                                <li>
                                    <a href="{{ route('tambahBuku') }}">Tambah Buku</a>
                                </li>

                                <li>
                                    <a href="{{ route('showRiwayatBuku') }}">List Riwayat Buku Keluar</a>
                                </li>

                                <li>
                                    <a href="{{ route('showEksemplar') }}">List Eksemplar</a>
                                </li>

                                <li>
                                    <a href="{{ route('showKlasifikasi') }}">List Klasifikasi</a>
                                </li>

                                <li>
                                    <a href="{{ route('showSubyek') }}">List Subyek</a>
                                </li>

                                <li>
                                    <a href="{{ route('showBahasa') }}">List Bahasa</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('showPinjamBuku') }}" class="waves-effect">
                                <i class="mdi mdi-book-plus"></i><span> Peminjaman Buku
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('showPengembalianBuku') }}" class="waves-effect">
                                <i class="mdi mdi-book-remove"></i><span> Pengembalian Buku
                            </a>
                        </li>
                    </ul>

                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
