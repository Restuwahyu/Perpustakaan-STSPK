@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Member Tidak Aktif</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('showMember') }}">List Member Aktif</a>
                            </li>
                            <li class="breadcrumb-item active">List Member Tidak Aktif</li>
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
                        <div class="col-md-12">
                            <div class="mb-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-danger waves-effect waves-light"
                                    id="delete-selected" data-table-id="member_exp_table" data-toggle="modal"
                                    data-target="#hapusModal">
                                    Hapus Data Terpilih
                                </button>
                            </div>
                        </div><!-- /.col -->

                        <form id="memberForm" action="{{ route('showPeminjamanMember') }}" method="POST">
                            @csrf
                            <input type="hidden" id="selectedMemberId" name="member_id">
                        </form>

                        <table id="member_exp_table" class="table table-bordered dt-responsive  text-center"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Email</th>
                                    <th>Tgl. Lahir</th>
                                    <th>No Telp</th>
                                    <th>Tgl. Kedaluwarsa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" data-id="{{ $member->member_id }}">
                                        </td>
                                        <td style="cursor: pointer" data-id="{{ $member->member_id }}">
                                            {{ $member->member_kode }}
                                        </td>
                                        <td class="text-left" style="cursor: pointer"
                                            data-id="{{ $member->member_id }}">
                                            {{ $member->member_nama }}
                                        </td>
                                        <td class="text-left" style="cursor: pointer"
                                            data-id="{{ $member->member_id }}">
                                            {{ $member->member_alamat }}
                                        </td>
                                        <td class="text-left" style="cursor: pointer"
                                            data-id="{{ $member->member_id }}">
                                            {{ $member->member_email }}
                                        </td>
                                        <td style="cursor: pointer" data-id="{{ $member->member_id }}">
                                            {{ \Carbon\Carbon::parse($member->member_tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu Indonesia --}}
                                        </td>
                                        <td style="cursor: pointer" data-id="{{ $member->member_id }}">
                                            {{ $member->member_notelp }}
                                        </td>
                                        <td style="cursor: pointer" data-id="{{ $member->member_id }}">
                                            {{ \Carbon\Carbon::parse($member->member_tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM Y') }}{{-- Konversi Waktu Indonesia --}}
                                        </td>
                                        <td>
                                            <form action="{{ route('statusMember') }}" method="POST">
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
                                                    tabindex="-1" role="dialog" aria-labelledby="aktivasiModalLabel"
                                                    aria-hidden="true">
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
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

<!-- Hapus Modal -->
<form action="{{ route('deleteSelectedDataMembers') }}" method="POST">
    @csrf
    <div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel">
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Apakah Anda Yakin Ingin Menghapus Member Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_delete_ids" id="selected-ids" value="">
                    <input type="hidden" name="cek" value="2">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

@include('layouts.footer')
