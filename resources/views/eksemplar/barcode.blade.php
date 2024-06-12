<!DOCTYPE html>
<html lang="en">

<head>
    <title>Label Buku</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @page {
            size: landscape;
        }

        body {
            font-family: Verdana, sans-serif;
            font-size: 11px;
            font-weight: bold;
        }

        .tbody {
            padding: 2px;
        }

        .card-table {
            height: 30mm;
            max-width: 50mm;
            padding: 2px;
            float: left;
            margin-right: 5px;
            border: 1px solid #000;
            box-sizing: border-box;
        }

        .card-table th {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            padding: 3px;
            font-size: 14px;
            font-weight: bold;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .card-table td {
            max-width: 20mm;
            border: none;
            padding: 2px;
            text-align: center;
            font-size: 13px;
        }

        .page-break {
            page-break-before: always;
        }

        .barcode {
            padding: 2px;
            height: 13mm;
            width: 20mm;
        }

        .delete {
            clear: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            @php $tableCount = 0; @endphp
            @foreach ($eksemplars as $index => $eksemplar)
                <div class="col-md-4 mr-2 text-center">
                    <table class="table table-bordered card-table mr-2">
                        <thead>
                            <tr>
                                <th style="background-color: white"></th>
                                <th style="background-color: white"></th>
                                <th>
                                    SEMINARI Kentungan
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="mt-2">
                                <td>
                                    <div style="padding: 5px; font-size: 10px">
                                        <div
                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 18mm; font-size: 8px; font-weight: normal;">
                                            <?php
                                            $buku_judul = $eksemplar->buku->buku_judul;
                                            $words = explode(' ', $buku_judul);
                                            
                                            $truncatedTitle = strlen($buku_judul) > 16 ? substr($buku_judul, 0, 13) . '...' : $buku_judul;
                                            
                                            echo $truncatedTitle;
                                            ?>
                                        </div>
                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($eksemplar->eksemplar_kode, 'C128') }}"
                                            alt="{{ $eksemplar->eksemplar_kode }}" class="barcode">
                                        {{ $eksemplar->eksemplar_kode }}
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    {{-- {{ $eksemplar->eksemplar_no_eksemplar }} --}}
                                    <?php
                                    $no_eksemplar = $eksemplar->eksemplar_no_eksemplar;
                                    $words = explode(' ', $no_eksemplar);
                                    
                                    foreach ($words as $word) {
                                        $wordCount = str_word_count($word);
                                        if ($wordCount === 1) {
                                            echo $word . '<br>';
                                        } else {
                                            $subwords = explode(' ', $word);
                                            foreach ($subwords as $subword) {
                                                echo $subword . '<br>';
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if (($index + 1) % 4 === 0)
                    <div class="delete"></div>
                @endif
                @php
                    $tableCount++;
                    if ($tableCount === 20) {
                        $tableCount = 0;
                        echo '<div class="page-break"></div>';
                    }
                @endphp
            @endforeach
        </div>
    </div>
</body>

</html>
