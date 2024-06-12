@include('layouts.header')

<!-- Start content -->
<div class="content">

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center ">
                <div class="col-md-8">
                    <div class="page-title-box">
                        <h4 class="page-title">Pengembalian Buku</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Pengembalian Buku</li>
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
            <div class="col-12 col-md-8 ">
                <div class="card card-primary">
                    <div class="card-body">
                        <form method="POST" action="{{ route('pengembalianBuku') }}" id="myForm">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-3 col-md-4">
                                    <label for="eksemplar_kode" class="form-label text-md-end">Kode Buku</label>
                                </div>
                                <div class="col-6 col-md-5">
                                    <input type="text" class="form-control" id="eksemplar_kode" name="eksemplar_kode"
                                        placeholder="Masukkan Kode Buku" value="{{ old('eksemplar_kode') }}" autofocus
                                        required>
                                    @error('eksemplar_kode')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->

        @if (isset($riwayatBukus) && $riwayatBukus->isNotEmpty())
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="pengembalianBuku_table" class="table table-bordered dt-responsive text-center"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No. Eksemplar</th>
                                        <th>ID Anggota</th>
                                        <th>Judul Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayatBukus as $riwayatBuku)
                                        <tr>
                                            <td>{{ $riwayatBuku->eksemplar->eksemplar_no_eksemplar }}</td>
                                            <td>{{ $riwayatBuku->member->member_kode }}
                                                - {{ $riwayatBuku->member->member_nama }}
                                            </td>
                                            <td class="text-left">
                                                {{ utf8_decode($riwayatBuku->eksemplar->buku->buku_judul) }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($riwayatBuku->peminjaman_tgl_pinjam)->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($riwayatBuku->peminjaman_tgl_kembali)->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td>
                                                @if ($riwayatBuku->peminjaman_status == 0)
                                                    <span class="badge badge-success">Sudah Dikembalikan</span>
                                                @elseif($riwayatBuku->peminjaman_status == 1)
                                                    <span class="badge badge-danger">Belum Dikembalikan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        @endif

        <!-- end top-Contant -->

    </div>
    <!-- container-fluid -->

</div>
<!-- content -->

<script>
    const memberKodeInput = document.getElementById('eksemplar_kode');
    const myForm = document.getElementById('myForm');

    memberKodeInput.addEventListener('input', function() {
        const inputValue = memberKodeInput.value.trim();

        if (inputValue.length === 13) {
            event.preventDefault();
            console.log(inputValue);
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
