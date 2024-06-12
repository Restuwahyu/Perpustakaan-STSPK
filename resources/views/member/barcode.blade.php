<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kartu Anggota</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @page {
            size: landscape;
        }

        body {
            font-family: Verdana, sans-serif;
        }

        .tbody {
            padding: 2px;
        }

        .card-table {
            height: 50mm;
            width: 85mm;
            padding: 3px;
            float: left;
            margin-right: 2px;
            border: 1px solid #000;
            box-sizing: border-box;

        }

        .card-table th {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .card-table td {
            width: 10%;
            border: none;
            padding: 2px;
            text-align: left;
            font-size: 12px;
        }

        .ktp-row {
            display: flex;
            justify-content: flex-start;
        }

        .page-break {
            page-break-before: always;
        }

        .barcode {
            max-height: 15mm;
            max-width: 35mm;
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
            @foreach ($members as $index => $member)
                <div class="col-md-4 mr-2 text-center">
                    <table class="table table-bordered card-table mr-2">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    Kartu Anggota Perpustakaan
                                    <br>Seminari Tinggi St. Paulus Kentungan
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $member->member_nama }}</td>
                            </tr>
                            <tr>
                                <td>ID Anggota</td>
                                <td>: {{ $member->member_kode }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ $member->member_alamat }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Lahir</td>
                                <td>
                                    :
                                    {{ \Carbon\Carbon::parse($member->member_tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Registrasi</td>
                                <td>
                                    :
                                    {{ \Carbon\Carbon::parse($member->member_tanggal_registrasi)->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Kedaluwarsa</td>
                                <td>
                                    :
                                    {{ \Carbon\Carbon::parse($member->member_tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td>
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($member->member_kode, 'C128') }}"
                                        alt="{{ $member->member_kode }}" class="barcode">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @if (($index + 1) % 3 === 0)
                    <div class="delete"></div>
                @endif
                @php
                    $tableCount++;
                    if ($tableCount === 9) {
                        $tableCount = 0;
                        echo '<div class="page-break"></div>';
                    }
                @endphp
            @endforeach
        </div>
    </div>
</body>

</html>
