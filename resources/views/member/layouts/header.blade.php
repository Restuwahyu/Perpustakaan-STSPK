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
                                <a class="dropdown-item" href="{{ route('showGantiPasswordMember') }}">
                                    <i class="fas fa-key"></i><span> Ganti Password</span>
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
                            <a href="{{ route('dashboardMember') }}" class="waves-effect">
                                <i class="fas fa-tachometer-alt"></i><span> Dashboard
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('showPemesananBuku') }}" class="waves-effect">
                                <i class="mdi mdi-book-plus"></i><span> List Pemesanan Buku
                            </a>
                        </li>
                        <li>
                            <input type="hidden" id="member_id" name="member_id"
                                value="{{ session('member')['member_id'] }}">
                            <input type="hidden" id="type" name="type" value="member">
                            <a href="/peminjaman" id="peminjamanDashboardLink" class="waves-effect">
                                <i class="mdi mdi-book"></i>
                                <span>Riwayat Peminjaman</span>
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

            <script>
                const peminjamanDashboardLink = document.getElementById('peminjamanDashboardLink');
                const csrfToken = '{{ csrf_token() }}'; // Inject CSRF token using Blade directive

                peminjamanDashboardLink.addEventListener('click', (event) => {
                    event.preventDefault();

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('showPeminjamanDashboard') }}';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    const hiddenInput = document.getElementById('member_id');
                    const typeInput = document.getElementById('type').cloneNode(true);

                    form.appendChild(hiddenInput.cloneNode(true));
                    form.appendChild(typeInput.cloneNode(true));

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                });
            </script>
