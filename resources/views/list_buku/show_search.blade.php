@include('list_buku.header')

<section class="mt-5 mb-5 container">
    <div class="row">
        <div class="col-md-12">
            @if ($paginatedBukus->total() > 0)
                <p>
                    <b>{{ $paginatedBukus->firstItem() }}-{{ $paginatedBukus->lastItem() }}</b>
                    dari <b>{{ $paginatedBukus->total() }}</b> hasil pencarian buku
                    @if ($searchKeyword != null)
                        <b>dengan kata kunci "{{ utf8_decode($searchKeyword) }}"</b>
                    @endif
                </p>
            @else
                <p>Tidak ada hasil yang cocok dengan kata kunci <b>"{{ $searchKeyword }}"</b></p>
            @endif
        </div>
    </div>
    <div class="grid-container text-center mb-3">
        @foreach ($bukuDatas as $buku)
            <form id="submitForm-{{ $buku['buku']->buku_id }}" action="{{ route('showListBukuDetail') }}" method="POST">
                @csrf
                <input type="hidden" name="buku" value="{{ $buku['buku']->buku_id }}">
                <button type="submit" style="border: none; background: none;">
                    <div class="card" style="width: 170px; height: 370px; cursor: pointer;" data-toggle="modal"
                        data-target="#confirmationModal-{{ $buku['buku']->buku_id }}">
                        <div class="media" style="margin-bottom: 10px;">
                            @if (
                                $buku['buku']->buku_cover_compressed &&
                                    file_exists(storage_path('app/public/images/cover_compressed/' . basename($buku['buku']->buku_cover_compressed))))
                                <div class="image-container "
                                    style="border-radius: 5px; width: 170px; height: 230px; overflow: hidden;">
                                    <img src="{{ asset($buku['buku']->buku_cover_compressed) }}"
                                        style="width: 100%; height: 100%; ">
                                </div>
                            @else
                                <div class="image-container text-center"
                                    style="border-radius: 5px; width: 170px; height: 230px; overflow: hidden;">
                                    <img id="previewImage" src="{{ asset('storage/images/preview.png') }}"
                                        style="width: 100%; height: 100%; object-fit: contain;">
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

    @if ($paginatedBukus->total() > 0)
        <div class="pagination-container">
            <div class="pg-info mt-3">Menampilkan <b>{{ $bukus->firstItem() }}-{{ $bukus->lastItem() }}</b> dari
                <b>{{ $paginatedBukus->total() }}</b> buku
            </div>
            <div class="pg-button">
                @if ($bukus->onFirstPage())
                    <button disabled><i class="ion-ios-arrow-back"></i></button>
                @else
                    <a href="{{ $bukus->previousPageUrl() }}" class="btn"><i class="ion-ios-arrow-back"></i></a>
                @endif

                <span>{{ $bukus->currentPage() }} / {{ $bukus->lastPage() }}</span>

                @if ($bukus->hasMorePages())
                    <a href="{{ $bukus->nextPageUrl() }}" class="btn">
                        <i class="ion-ios-arrow-forward"></i>
                    </a>
                @else
                    <button disabled><i class="ion-ios-arrow-forward"></i></button>
                @endif
            </div>
        </div>
    @endif
</section>
<hr>
<script src="{{ asset('storage/script.js') }}"></script>

@include('list_buku.footer')
