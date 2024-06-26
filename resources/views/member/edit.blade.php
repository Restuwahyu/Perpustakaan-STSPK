@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Edit Member {{ $member->member_nama }}</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('showMember') }}">List Member Aktif</a>
                            </li>
                            <li class="breadcrumb-item active">Edit Member</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page-title -->

        <!-- start top-Contant -->
        <div class="row">
            <div class="col-12 col-sm-8">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('updateMember') }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="nama" class="col-sm-4 col-form-label">Nama* </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" name="member_nama"
                                            class="form-control @error('member_nama') is-invalid @enderror"
                                            id="nama" value="{{ $member->member_nama }}" required>
                                    </div>
                                    @error('member_nama')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label">Email* </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="email" name="member_email"
                                            class="form-control @error('member_email') is-invalid @enderror"
                                            id="email" value="{{ $member->member_email }}" required>
                                    </div>
                                    @error('member_email')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nama" class="col-sm-4 col-form-label">Tanggal Lahir* </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="date" name="member_tanggal_lahir"
                                            class="form-control @error('member_tanggal_lahir') is-invalid @enderror"
                                            value="{{ $member->member_tanggal_lahir }}" data-date-format:="dd/mm/yyyy"
                                            required />
                                    </div>
                                    @error('member_tanggal_lahir')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="notelp" class="col-sm-4 col-form-label">Nomor Telepon*</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" name="member_notelp"
                                            class="form-control @error('member_notelp') is-invalid @enderror"
                                            pattern="[0-9]+" id="notelp" value="{{ $member->member_notelp }}"
                                            @error('member_notelp') is-invalid @enderror>
                                    </div>
                                    @error('member_notelp')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="alamat" class="col-sm-4 col-form-label">Alamat* </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <textarea type="text" name="member_alamat" class="form-control @error('member_alamat') is-invalid @enderror"
                                            id="alamat" @error('member_alamat') is-invalid @enderror>{{ $member->member_alamat }}</textarea>
                                    </div>
                                    @error('member_alamat')
                                        <span class="text-danger">({{ $message }})</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('showMember') }}" class="btn btn-danger mr-2"
                                        style="color: white">Batal</a>

                                    <a class="btn btn-success" data-toggle="modal" data-target="#editModal"
                                        style="color: white">
                                        Edit
                                    </a>
                                </div>
                            </div><!-- /.row -->

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Konfirmasi Edit</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div><!-- /.modal-header -->
                                        <div class="modal-body">
                                            Apakah Anda Yakin Mengubah MEMBER:{{ $member->member_nama }} Ini ?
                                        </div><!-- /.modal-body -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Batal</button>

                                            <input type="hidden" name="id_member_edit"
                                                value="{{ $member->member_id }}">

                                            <button type="submit" class="btn btn-success">Ya</button>
                                        </div><!-- /.modal-footer -->
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
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

<script>
    // Menghilangkan Simbol Otomatis pada Inputan Nomor Telepon
    document.addEventListener('DOMContentLoaded', function() {
        var notelpInput = document.getElementById("notelp");

        notelpInput.addEventListener("input", function() {
            var cleanedValue = this.value.replace(/\D/g, "");
            this.value = cleanedValue;
        });
    });
</script>

@include('layouts.footer')
