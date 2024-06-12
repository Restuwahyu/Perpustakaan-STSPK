<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman Buku</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }

        h2 {
            color: #000000;
        }

        h4 {
            text-align: left
        }

        p {
            font-size: 14px;
            color: #777;
        }

        #chart-container {
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }

        th {
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: #fff;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }

        #additional-info,
        #additional-info-yearly,
        #additional-info-monthly,
        #additional-info-weekly {
            margin-top: 10px;
        }

        #additional-info table,
        #additional-info-yearly table,
        #additional-info-monthly table,
        #additional-info-weekly table,
        #additional-info-klasifikasi table,
        #additional-info-tipeKoleksi table {
            width: 40%;
        }

        #additional-info th,
        #additional-info-yearly th,
        #additional-info-monthly th,
        #additional-info-weekly th,
        #additional-info td,
        #additional-info-yearly td,
        #additional-info-monthly td,
        #additional-info-weekly td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }

        #additional-info th,
        #additional-info-yearly th,
        #additional-info-monthly th,
        #additional-info-weekly th {
            background-color: #3498db;
            color: #fff;
        }

        #additional-info-yearly {
            page-break-before: always;
        }

        #additional-info-monthly {
            page-break-before: always;
        }

        #additional-info-weekly {
            page-break-before: always;
        }

        #additional-info-member {
            page-break-before: always;
        }

        #additional-info-book {
            page-break-before: always;
        }

        #additional-info-klasifikasi {
            page-break-before: always;
        }

        #additional-info-tipeKoleksi {
            page-break-before: always;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div style="text-align: center;">
        @if ($activeChart == 'chart-bar-5tahun')
            <h2>Laporan Peminjaman Buku 5 Tahun Terakhir</h2>
        @elseif($activeChart == 'chart-bar-tahun')
            <h2>Laporan Peminjaman Buku Tahun {{ Carbon\Carbon::now()->locale('id')->isoFormat('Y') }}</h2>
        @elseif($activeChart == 'chart-bar-bulan')
            <h2>Laporan Peminjaman Buku Bulan {{ Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</h2>
        @endif

        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>

        <!-- Add your chart display logic here -->
        <div id="chart-container">
            <img id="myChartImage" alt="Chart Image">
        </div>

        <h4>Data Member Peminjaman Buku</h4>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Member</th>
                    <th>Judul Buku</th>
                    <th style="width: 70px;">Tanggal Pinjam</th>
                    <th style="width: 70px;">Tanggal Kembali</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($data['filteredPeminjamans'] as $peminjaman)
                    <tr>
                        <td style="text-align: center;">{{ $i++ . '.' }}</td>
                        <td>{{ $peminjaman->member->member_nama }}</td>
                        <td>{{ \Str::limit($peminjaman->eksemplar->buku->buku_judul, 50, '...') }}</td>
                        <td style="text-align: center;">{{ $peminjaman->peminjaman_tgl_pinjam }}</td>
                        <td style="text-align: center;">{{ $peminjaman->peminjaman_tgl_kembali }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($activeChart == 'chart-bar-5tahun')
            <div id="additional-info-member">
                <h4>Data Peminjaman Buku Berdasarkan Member</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Member</th>
                            @php
                                $currentYear = now()->year;
                                $startYear = $currentYear - 4;
                                $i = 1;
                            @endphp
                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                <th>{{ $year }}</th>
                            @endfor
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberTotals as $memberNama => $yearlyCounts)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $memberNama }}</td>
                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                    <td>{{ $yearlyCounts[$year] ?? 0 }}</td>
                                @endfor
                                <td>{{ array_sum($yearlyCounts) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="additional-info-book">
                <h4>Data Peminjaman Buku Berdasarkan Buku</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($bukuTotals as $judulBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $judulBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Buku</td>
                            <td style="text-align: center;">{{ $data['totalPerYear']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-klasifikasi">
                <h4>Data Peminjaman Buku Berdasarkan Klasifikasi</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Klasifikasi</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($klasifikasiTotals as $klasifikasiBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $klasifikasiBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Klasifikasi</td>
                            <td style="text-align: center;">{{ $data['totalPerYear']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-tipeKoleksi">
                <h4>Data Peminjaman Buku Berdasarkan Tipe Koleksi</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe Koleksi</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($tipeKoleksiTotals as $tipeKoleksiBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $tipeKoleksiBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Tipe Koleksi</td>
                            <td style="text-align: center;">{{ $data['totalPerYear']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-yearly">
                <h4>Data Peminjaman Buku Berdasarkan Tahun </h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tahun</th>
                            <th>Total Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentYear = now()->year;
                            $startYear = $currentYear - 4;
                            $i = 1;
                        @endphp

                        @for ($year = $currentYear; $year >= $startYear; $year--)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $year }}</td>
                                <td>{{ $data['totalPerYear']->get($year, 0) }}</td>
                            </tr>
                        @endfor

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Tahun</td>
                            <td>{{ $data['totalPerYear']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @elseif($activeChart == 'chart-bar-tahun')
            <div id="additional-info-member">
                <h4>Data Peminjaman Buku Berdasarkan Member</h4>
                <table>
                    <thead>
                        <tr>
                            @php
                                $namaBulan = [
                                    '1' => 'Januari',
                                    '2' => 'Februari',
                                    '3' => 'Maret',
                                    '4' => 'April',
                                    '5' => 'Mei',
                                    '6' => 'Juni',
                                    '7' => 'Juli',
                                    '8' => 'Agustus',
                                    '9' => 'September',
                                    '10' => 'Oktober',
                                    '11' => 'November',
                                    '12' => 'Desember',
                                ];
                                $namaBulan2 = [
                                    '01' => 'Jan',
                                    '02' => 'Feb',
                                    '03' => 'Mar',
                                    '04' => 'Apr',
                                    '05' => 'Mei',
                                    '06' => 'Jun',
                                    '07' => 'Jul',
                                    '08' => 'Agt',
                                    '09' => 'Sept',
                                    '10' => 'Okt',
                                    '11' => 'Nov',
                                    '12' => 'Des',
                                ];
                                $i = 1;
                            @endphp

                            <th>No.</th>
                            <th>Nama Member</th>
                            @foreach ($namaBulan2 as $bulanIndex => $bulanNama)
                                <th>{{ $bulanNama }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberTotals as $memberNama => $monthlyCounts)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $memberNama }}</td>
                                @foreach ($namaBulan as $bulanIndex => $bulanNama)
                                    <td style="text-align: center;">{{ $monthlyCounts[$bulanIndex] ?? 0 }}</td>
                                @endforeach
                                <td style="text-align: center;">{{ array_sum($monthlyCounts) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="additional-info-book">
                <h4>Data Peminjaman Buku Berdasarkan Buku</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($bukuTotals as $judulBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $judulBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Buku</td>
                            <td style="text-align: center;">{{ $data['totalPerMonth']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-klasifikasi">
                <h4>Data Peminjaman Buku Berdasarkan Klasifikasi</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Klasifikasi</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($klasifikasiTotals as $klasifikasiBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $klasifikasiBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Klasifikasi</td>
                            <td style="text-align: center;">{{ $data['totalPerMonth']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-tipeKoleksi">
                <h4>Data Peminjaman Buku Berdasarkan Tipe Koleksi</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe Koleksi</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($tipeKoleksiTotals as $tipeKoleksiBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $tipeKoleksiBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Tipe Koleksi</td>
                            <td style="text-align: center;">{{ $data['totalPerMonth']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-monthly">
                <h4>Data Peminjaman Buku Berdasarkan Bulan </h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Bulan</th>
                            <th>Total Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($data['totalPerMonth'] as $bulan => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td style="text-align: left">{{ $namaBulan[$bulan] }}</td>
                                <td>{{ $total }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Bulan</td>
                            <td>{{ $data['totalPerMonth']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @elseif($activeChart == 'chart-bar-bulan')
            <div id="additional-info-member">
                <h4>Data Peminjaman Buku Berdasarkan Member</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Member</th>
                            @foreach ($data['totalPerWeek'] as $minggu => $total)
                                <th>{{ $minggu }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($memberTotals as $memberNama => $weeklyCounts)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $memberNama }}</td>
                                @foreach ($data['totalPerWeek'] as $minggu => $total)
                                    <td style="text-align: center;">{{ $weeklyCounts[$minggu] ?? 0 }}</td>
                                @endforeach
                                <td style="text-align: center;">{{ array_sum($weeklyCounts) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="additional-info-book">
                <h4>Data Peminjaman Buku Berdasarkan Buku</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($bukuTotals as $judulBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $judulBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Buku</td>
                            <td style="text-align: center;">{{ $data['totalPerWeek']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-klasifikasi">
                <h4>Data Peminjaman Buku Berdasarkan Klasifikasi</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Klasifikasi</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($klasifikasiTotals as $klasifikasiBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $klasifikasiBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Klasifikasi</td>
                            <td style="text-align: center;">{{ $data['totalPerWeek']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-tipeKoleksi">
                <h4>Data Peminjaman Buku Berdasarkan Tipe Koleksi</h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe Koleksi</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($tipeKoleksiTotals as $tipeKoleksiBuku => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $tipeKoleksiBuku }}</td>
                                <td style="text-align: center;">{{ $total }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Tipe Koleksi</td>
                            <td style="text-align: center;">{{ $data['totalPerWeek']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="additional-info-weekly">
                <h4>Data Peminjaman Buku Berdasarkan Minggu </h4>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Minggu</th>
                            <th>Total Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($data['totalPerWeek'] as $minggu => $total)
                            <tr>
                                <td style="text-align: center;">{{ $i++ . '.' }}</td>
                                <td>{{ $minggu }}</td>
                                <td>{{ $total }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="text-align: center;" colspan="2">Total Semua Minggu</td>
                            <td>{{ $data['totalPerWeek']->sum() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        {{-- <div id="additional-info">
            <h4>Data Peminjaman Buku Berdasarkan Kategori </h4>
            <table>
                <thead>
                    <tr>
                        <th>Kategori Member</th>
                        <th>Total Peminjaman</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalPeminjaman = 0;
                    @endphp
                    @foreach ($data['totalByKategori'] as $kategori => $total)
                        <tr>
                            <td style="text-align: left;">{{ $kategori }}</td>
                            <td>{{ $total }}</td>
                        </tr>
                        @php $totalPeminjaman += $total; @endphp
                    @endforeach

                    <tr>
                        <td style="text-align: left;">Total Semua Kategori</td>
                        <td>{{ $totalPeminjaman }}</td>
                    </tr>
                </tbody>
            </table>
        </div> --}}
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the chart data and options based on the activeChart
            @if ($activeChart == 'chart-bar-5tahun')
                var chartData = {!! json_encode($data['totalPerYear']) !!};
                var chartLabel = "Tahun";
            @elseif ($activeChart == 'chart-bar-tahun')
                var chartData = {!! json_encode($data['totalPerMonth']) !!};
                var chartLabel = "Bulan";
            @elseif ($activeChart == 'chart-bar-bulan')
                var chartData = {!! json_encode($data['totalPerWeek']) !!};
                var chartLabel = "Minggu";
            @endif

            // Generate the chart
            var ctx = document.createElement('canvas').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(chartData),
                    datasets: [{
                        label: 'Total Peminjaman',
                        data: Object.values(chartData),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            title: {
                                display: true,
                                text: chartLabel
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Peminjaman'
                            }
                        }
                    }
                }
            });

            // Convert the chart to an image and set it as the source for the img element
            var chartImage = new Image();
            chartImage.src = ctx.canvas.toDataURL();
            document.getElementById('myChartImage').src = chartImage.src;
        });
    </script>
</body>

</html>
