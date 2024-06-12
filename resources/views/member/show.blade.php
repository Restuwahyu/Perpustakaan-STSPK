@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">List Member Aktif</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List Member Aktif</li>
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
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-danger waves-effect waves-light" id="delete-selected"
                                data-table-id="member_table" data-toggle="modal" data-target="#hapusModal">
                                Hapus Data Terpilih
                            </button>
                            <button type="button" class="btn btn-success waves-effect waves-light" id="delete-selected"
                                data-table-id="member_table" data-toggle="modal" data-target="#cetakModal">
                                <i class="fas fa-print"></i>
                                Download Member
                            </button>
                        </div><!-- /.col -->


                        <form id="memberForm" action="{{ route('showPeminjamanMember') }}" method="POST">
                            @csrf
                            <input type="hidden" id="selectedMemberId" name="member_id">
                        </form>

                        <table id="member_table" class="table table-bordered dt-responsive text-center"
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
                                            {{ \Carbon\Carbon::parse($member->member_tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td style="cursor: pointer" data-id="{{ $member->member_id }}">
                                            {{ $member->member_notelp }}
                                        </td>
                                        <td style="cursor: pointer" data-id="{{ $member->member_id }}">
                                            {{ \Carbon\Carbon::parse($member->member_tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <form action="{{ route('editMember') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id_member_edit"
                                                        value="{{ $member->member_id }}">
                                                    <button type="submit" class="btn btn-sm btn-warning  mr-2">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('resetDefaultPasswordMember') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <a class="btn btn-sm btn-primary" data-toggle="modal"
                                                        data-target="#resetPasswordModal{{ $member->member_id }}">
                                                        <i class="fas fa-key" style="color: white"></i>
                                                    </a>

                                                    <!-- Reset Password Modal -->
                                                    <div class="modal fade"
                                                        id="resetPasswordModal{{ $member->member_id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="resetPasswordModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="resetPasswordModalLabel">
                                                                        Konfirmasi Reset Password
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-left">
                                                                    Apakah Anda Yakin Ingin Reset Password MEMBER:
                                                                    {{ $member->member_nama }} Ini ?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Batal</button>
                                                                    <input type="hidden" name="id_member_reset"
                                                                        value="{{ $member->member_id }}">
                                                                    <button type="submit" class="btn btn-success"
                                                                        id="sa-success">Ya</button>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </form>
                                            </div>
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
                    <input type="hidden" name="cek" value="1">

                    <button type="submit" class="btn btn-success" id="delete-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

<!-- Cetak Modal -->
<form action="{{ route('printSelectedDataMembers') }}" method="POST">
    @csrf
    <div class="modal fade" id="cetakModal" tabindex="-1" role="dialog" aria-labelledby="cetakModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cetakModalLabel">
                        Konfirmasi Cetak
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Apakah Anda Yakin Ingin Mencetak Member Ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    <input type="hidden" name="selected_cetak_ids" id="selected-cetak-ids" value="">

                    <button type="submit" class="btn btn-success" id="cetak-selected-form">Ya</button>
                </div>
            </div><!-- ./modal-content -->
        </div><!-- ./modal-dialog -->
    </div><!-- ./modal -->
</form>

<script>
    $(document).ready(function() {
        $('#cetak-selected-form').on('click', function() {
            $('#cetakModal').modal('hide');
        });
    });
</script>


@include('layouts.footer')
