@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Peminjaman Buku</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Peminjaman Buku</li>
                        </ol>
                    </div>
                </div>

                <div class="col-md-8">
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
        <div class="row" id="inputIdAnggota">
            <div class="col-12 col-md-8 ">
                <div class="card card-primary">
                    <div class="card-body">
                        <form method="POST" action="{{ route('showPinjamBuku') }}" id="myForm">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-3 col-md-4">
                                    <label for="member_kode" class="form-label text-md-end">ID Member</label>
                                </div>
                                <div class="col-6 col-md-5">
                                    <input type="text" class="form-control" id="member_kode" name="member_kode"
                                        placeholder="Masukkan ID Member" autofocus required>
                                    @error('member_kode')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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
    const memberKodeInput = document.getElementById('member_kode');
    const myForm = document.getElementById('myForm');

    const memberKodeSession = '{{ session('memberPeminjaman.member_kode') }}';

    if (memberKodeSession) {
        memberKodeInput.value = memberKodeSession;
        myForm.submit();
    }

    memberKodeInput.addEventListener('input', function() {
        const inputValue = memberKodeInput.value.trim();

        if (inputValue.length === 6) {
            myForm.submit();
        }
    });

    memberKodeInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            myForm.submit();
        }
    });
</script>

@include('layouts.footer')
