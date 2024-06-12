@include('list_buku.header')
<section class="mt-5 container">
    <div class="top">
        <h3 class="with-banner">Buku-Buku Terbaru</h3>
        <a href="{{ route('searchBuku') }}" class="with-banner">
            Lihat Semua Koleksi Buku
        </a>
    </div>

    <div class="grid-container text-center">
        @foreach ($bukuDatas as $buku)
            <form id="submitForm-{{ $buku['buku']->buku_id }}" action="{{ route('showListBukuDetail') }}" method="POST">
                @csrf
                <input type="hidden" name="buku" value="{{ $buku['buku']->buku_id }}">
                <button type="submit" style="border: none; background: none;">
                    <div class="card" style="width: 170px; height: 370px; cursor: pointer;">
                        <div class="media" style="margin-bottom: 10px;">
                            @if (
                                $buku['buku']->buku_cover_compressed &&
                                    file_exists(storage_path('app/public/images/cover_compressed/' . basename($buku['buku']->buku_cover_compressed))))
                                <div class="image-container "
                                    style="border-radius: 5px; width: 170px; height: 230px; overflow: hidden;">
                                    <img src="{{ asset($buku['buku']->buku_cover_compressed) }}"
                                        style="width: 100%; height: 100%; ">
                                    <div class="badge"
                                        style="position: absolute; top: 5px; left: 5px; background-color: #20c997; color: white; padding: 5px;">
                                        Terbaru
                                    </div>
                                </div>
                            @else
                                <div class="image-container text-center"
                                    style="border-radius: 5px; width: 170px; height: 230px; overflow: hidden;">
                                    <img id="previewImage" src="{{ asset('storage/images/preview.png') }}"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                    <div class="badge"
                                        style="position: absolute; top: 5px; left: 5px; background-color: #20c997; color: white; padding: 5px;">
                                        Terbaru
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="pengarangcontainer">
                            <div class="title">
                                {{ utf8_decode(mb_strimwidth($buku['buku']->buku_judul, 0, 50, '...')) }}
                            </div>
                            <div class="subtitle">
                                <small class="text-muted">
                                    @if ($buku['pengarang'] !== null)
                                        {{ utf8_decode(mb_strimwidth($buku['pengarang'], 0, 25, '...')) }}
                                    @endif
                                </small>
                                <br>
                                @if ($buku['stok'] == 0)
                                    <small class="notavailable">
                                        Stok Tidak Tersedia
                                    </small>
                                @else
                                    <small class="available">
                                        Stok Tersedia: {{ $buku['stok'] }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </button>
            </form>
        @endforeach
    </div>
    <hr>
</section>

<section class=" mb-5 container">
    <h3 class="text-secondary text-center text-thin mt-5 mb-4">Pilih kategori yang menarik bagi Anda</h3>
    <div class="lettercontainer">
        <div class="row">
            <div class="col-md-12 text-center">
                @foreach (range('A', 'Z') as $letter)
                    <a href="#" class="letter-link" data-toggle="collapse"
                        data-target="{{ $letter }}">{{ $letter }}</a>
                @endforeach
                <a href="#" class="letter-link" data-toggle="collapse" data-target="lain-lain">Lain-lain</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                @foreach (range('A', 'Z') as $letter)
                    <div id="{{ $letter }}" class="letter-group collapse">
                        <h4>{{ $letter }}</h4>
                        <ul>
                            @foreach ($klasifikasis as $klasifikasi)
                                @if (strtoupper(substr($klasifikasi->klasifikasi_nama, 0, 1)) === $letter)
                                    <li>
                                        <form action="{{ route('searchBuku') }}" method="get" id="searchForm">
                                            <a href="#" class="searchLink"
                                                data-keywords="{{ $klasifikasi->klasifikasi_nama }}">
                                                {{ $klasifikasi->klasifikasi_nama }}
                                            </a>
                                            <input type="hidden" name="keywords" id="keywordsInput">
                                        </form>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                <div id="lain-lain" class="letter-group collapse">
                    <h4>Lain-lain</h4>
                    <ul>
                        @foreach ($klasifikasis as $klasifikasi)
                            @if (!ctype_alpha(substr($klasifikasi->klasifikasi_nama, 0, 1)))
                                <li>
                                    <form action="{{ route('searchBuku') }}" method="get" id="searchForm">
                                        <a href="#" class="searchLink"
                                            data-keywords="{{ $klasifikasi->klasifikasi_nama }}">
                                            {{ $klasifikasi->klasifikasi_nama }}
                                        </a>
                                        <input type="hidden" name="keywords" id="keywordsInput">
                                    </form>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<hr>

{{-- <section>
    <div id="chatbot"></div>
</section> --}}

@include('list_buku.footer')
