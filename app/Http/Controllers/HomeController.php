<?php

namespace App\Http\Controllers;

use App\Services\BukuService;
use App\Services\MemberService;
use App\Services\PeminjamanBukuService;
use App\Services\PesanBukuService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class HomeController extends Controller
{
    protected $memberService;
    protected $peminjamanBukuService;
    protected $pesanBukuService;
    protected $bukuService;

    public function __construct(MemberService $memberService, PeminjamanBukuService $peminjamanBukuService, PesanBukuService $pesanBukuService, BukuService $bukuService)
    {
        $this->memberService = $memberService;
        $this->peminjamanBukuService = $peminjamanBukuService;
        $this->pesanBukuService = $pesanBukuService;
        $this->bukuService = $bukuService;
    }

    public function sentEmailRemainder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $member = $this->memberService->findById($request->member_id);
        $peminjaman = $this->peminjamanBukuService->findById($request->peminjaman_id);
        $this->memberService->sendEmail($member->member_email, $member->member_nama, '-', $peminjaman, 'reminder');
        $peminjaman->peminjaman_email_sent = 1;
        $peminjaman->save();

        return redirect()->back()->with('success', 'Email Pengingat Pengembalian Buku.');
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $members = $this->memberService->findMemberDashboards();
        $peminjamans = $this->peminjamanBukuService->findAll('peminjaman_id', 'asc');

        $today = now();

        $pengembalian_buku = $peminjamans->filter(function ($peminjaman) use ($today) {
            // dd($peminjaman);
            $tglKembali = Carbon::parse($peminjaman->peminjaman_tgl_kembali);
            $daysRemaining = $today->diffInDays($tglKembali, false) + 1;
            $member = $this->memberService->findById($peminjaman->peminjaman_member);
            // if ($peminjaman->peminjaman_status == 1 && $tglKembali->isSameDay($today) && ) {

            // }

            if ($peminjaman->peminjaman_status == 1) {
                if ($tglKembali->isSameDay($today)) {
                    if (!$peminjaman->peminjaman_email_sent) {
                        $this->memberService->sendEmail($member->member_email, $member->member_nama, '-', $peminjaman, 'reminder');
                        $peminjaman->peminjaman_email_sent = 1;
                        $peminjaman->save();
                    }
                    $peminjaman->status_kembali = 'Harus Dikembalikan Hari Ini';
                } elseif ($tglKembali->gt($today)) {
                    $peminjaman->status_kembali = 'Peminjaman Buku Habis dalam ' . $daysRemaining . ' hari';
                } elseif ($daysRemaining <= 1) {
                    $peminjaman->status_kembali = 'Harus Dikembalikan Hari Ini';
                    $peminjaman->daysRemaining = $daysRemaining - 1;
                }
            }

            return $daysRemaining <= 7 && $peminjaman->peminjaman_status == 1;
        });
        // dd($pengembalian_buku);

        $totalPeminjaman = $peminjamans->count();

        //Peminjaman dalam 1 Bulan
        $weeksInMonth = \Carbon\CarbonPeriod::create(now()->startOfMonth(), '1 week', now()->endOfMonth());
        $peminjamanLastMonth = [];
        $weekNumber = 1;
        $totalPeminjamanLastMonth = 0;

        foreach ($weeksInMonth as $weekStartDate) {
            $weekStartDate = $weekStartDate->startOfWeek();
            $weekEndDate = $weekStartDate->copy()->endOfWeek();

            $peminjamanMingguIni = $peminjamans->filter(function ($peminjaman) use ($weekStartDate, $weekEndDate) {
                $peminjamanDate = Carbon::parse($peminjaman->peminjaman_tgl_pinjam);
                return $peminjamanDate->isBetween($weekStartDate, $weekEndDate, true, true) || $peminjamanDate->isSameDay($weekEndDate);
            });

            $totalMingguIni = $peminjamanMingguIni->count();
            $weekLabel = "Minggu-" . $weekNumber;

            $peminjamanLastMonth[$weekLabel] = [
                'items' => $peminjamanMingguIni,
                'total' => $totalMingguIni,
            ];

            $totalPeminjamanLastMonth += $totalMingguIni;

            $weekNumber++;
        }

        //Peminjaman dalam 1 Tahun
        $peminjamanLastYear = [];
        $monthsInYear = \Carbon\CarbonPeriod::create(now()->startOfYear(), '1 month', now()->endOfYear());
        $totalPeminjamanLastYear = 0;

        foreach ($monthsInYear as $monthStartDate) {
            $monthStartDate = $monthStartDate->startOfMonth();
            $monthEndDate = $monthStartDate->copy()->endOfMonth();

            $peminjamanBulanIni = $peminjamans->filter(function ($peminjaman) use ($monthStartDate, $monthEndDate) {
                $peminjamanDate = Carbon::parse($peminjaman->peminjaman_tgl_pinjam);
                return $peminjamanDate->isBetween($monthStartDate, $monthEndDate, true, true) || $peminjamanDate->isSameDay($monthEndDate);
            });

            $totalBulanIni = $peminjamanBulanIni->count();
            $monthLabel = Carbon::parse($monthStartDate)->locale('id')->isoformat('MMM'); // Get month name in Indonesian

            $peminjamanLastYear[$monthLabel] = [
                'items' => $peminjamanBulanIni,
                'total' => $totalBulanIni,
            ];

            $totalPeminjamanLastYear += $totalBulanIni;
        }

        //Peminjaman dalam 5 Tahun
        $peminjamanLast5Years = [];
        $totalPeminjamanLast5Years = 0;

        $startYear = now()->subYears(4)->startOfYear();
        $yearsIn5Years = \Carbon\CarbonPeriod::create($startYear, '1 year', now()->endOfYear());

        foreach ($yearsIn5Years as $yearStartDate) {
            $yearStartDate = $yearStartDate->startOfYear();
            $yearEndDate = $yearStartDate->copy()->endOfYear();

            $peminjamanTahunIni = $peminjamans->filter(function ($peminjaman) use ($yearStartDate, $yearEndDate) {
                $peminjamanDate = Carbon::parse($peminjaman->peminjaman_tgl_pinjam);
                return $peminjamanDate->isBetween($yearStartDate, $yearEndDate, true, true) || $peminjamanDate->isSameDay($yearEndDate);
            });

            $totalTahunIni = $peminjamanTahunIni->count();
            $yearLabel = Carbon::parse($yearStartDate)->format('Y');
            $peminjamanLast5Years[$yearLabel] = [
                'items' => $peminjamanTahunIni,
                'total' => $totalTahunIni,
            ];

            $totalPeminjamanLast5Years += $totalTahunIni;
        }

        $pesanBukus = $this->pesanBukuService->findAll('pemesanan_buku_id', 'ASC')->get();

        foreach ($pesanBukus as $pesanBuku) {
            $buku = $this->bukuService->findById($pesanBuku->eksemplars->buku_id);
            $member = $this->memberService->findById($pesanBuku->pemesanan_buku_member);
            $pesanBuku->bukus = $buku;
            $pesanBuku->members = $member;
        }

        return view('layouts.master',
            compact(
                'members',
                'peminjamans',
                'pengembalian_buku',
                'peminjamanLastMonth',
                'peminjamanLastYear',
                'peminjamanLast5Years',
                'totalPeminjaman',
                'totalPeminjamanLastMonth',
                'totalPeminjamanLastYear',
                'totalPeminjamanLast5Years',
                'pesanBukus'
            ));
    }

    // Proses Update Status Member
    public function renewalMember(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member_id = $request->input('id_member_status');

        $member = $this->memberService->findById($member_id);
        $member_nama = $member->member_nama;

        if ($member) {
            $member->update(['member_status' => 1]);
            $member->update(['member_tanggal_registrasi' => Carbon::now()]);
            $member->update(['member_tanggal_kedaluwarsa' => Carbon::now()->addYears(4)]);
            return redirect()->route('home')->with('success', "Status member telah diperbarui");
        } else {
            return redirect()->route('home')->with('error', "Gagal memperbarui status member");
        }
    }

    // Cetak Data Peminjaman
    public function printData(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $date = Carbon::now()->locale('id')->isoFormat('D MMM Y');
        $activeChart = $request->input('activeChart');
        $data = $this->getDataForChart($activeChart);
        $filteredPeminjamans = $data['filteredPeminjamans'];
        // dd($filteredPeminjamans);
        $totalPerYear = $data['totalPerYear'];
        $totalPerMonth = $data['totalPerMonth'];
        $totalPerWeek = $data['totalPerWeek'];

        $memberTotals = $this->calculateMemberTotals($activeChart, $filteredPeminjamans);
        $bukuTotals = $this->calculateBukuTotals($activeChart, $filteredPeminjamans);
        $klasifikasiTotals = $this->calculateKlasifikasiTotals($activeChart, $filteredPeminjamans);
        $tipeKoleksiTotals = $this->calculateTipeTotals($activeChart, $filteredPeminjamans);
        // dd($bukuTotals);
        $pdf = PDF::loadView('layouts.laporan', [
            'data' => $data,
            'activeChart' => $activeChart,
            'filteredPeminjamans' => $filteredPeminjamans,
            'totalPerYear' => $totalPerYear,
            'totalPerMonth' => $totalPerMonth,
            'totalPerWeek' => $totalPerWeek,
            'memberTotals' => $memberTotals,
            'bukuTotals' => $bukuTotals,
            'klasifikasiTotals' => $klasifikasiTotals,
            'tipeKoleksiTotals' => $tipeKoleksiTotals,
        ]);

        // $pdfContent = $pdf->output();
        // dd($date);

        return $pdf->stream('laporan_' . $date . '.pdf');
    }

    public function calculateMemberTotals($activeChart, $filteredPeminjamans)
    {
        $memberTotals = [];

        if ($activeChart == "chart-bar-5tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $memberNama = $peminjaman->member->member_nama;

                $peminjamanYear = Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->year;

                if (array_key_exists($memberNama, $memberTotals)) {
                    if (array_key_exists($peminjamanYear, $memberTotals[$memberNama])) {
                        $memberTotals[$memberNama][$peminjamanYear]++;
                    } else {
                        $memberTotals[$memberNama][$peminjamanYear] = 1;
                    }
                } else {
                    $memberTotals[$memberNama] = [$peminjamanYear => 1];
                }
            }
        } elseif ($activeChart == "chart-bar-tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $memberNama = $peminjaman->member->member_nama;
                $peminjamanYear = Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->year;
                $peminjamanMonth = Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->month;

                if ($peminjamanYear == now()->year) {
                    if (array_key_exists($memberNama, $memberTotals)) {
                        if (array_key_exists($peminjamanMonth, $memberTotals[$memberNama])) {
                            $memberTotals[$memberNama][$peminjamanMonth]++;
                        } else {
                            $memberTotals[$memberNama][$peminjamanMonth] = 1;
                        }
                    } else {
                        $memberTotals[$memberNama] = [$peminjamanMonth => 1];
                    }
                }
            }
        } elseif ($activeChart == "chart-bar-bulan") {
            $memberTotals = [];

            $weeksInMonth = \Carbon\CarbonPeriod::create(now()->startOfMonth(), '1 week', now()->endOfMonth());
            $weekNumber = 1;
            foreach ($weeksInMonth as $weekStartDate) {
                $weekStartDate = $weekStartDate->startOfWeek();
                $weekEndDate = $weekStartDate->copy()->endOfWeek();
                $peminjamanMingguIni = $filteredPeminjamans->filter(function ($peminjaman) use ($weekStartDate, $weekEndDate) {
                    $peminjamanDate = Carbon::parse($peminjaman->peminjaman_tgl_pinjam);
                    return $peminjamanDate->isBetween($weekStartDate, $weekEndDate, true, true) || $peminjamanDate->isSameDay($weekEndDate);
                });

                foreach ($peminjamanMingguIni as $peminjaman) {
                    $memberNama = $peminjaman->member->member_nama;
                    $weekLabel = 'Minggu-' . $weekNumber;

                    if (!isset($memberTotals[$memberNama][$weekLabel])) {
                        $memberTotals[$memberNama][$weekLabel] = 1;
                    } else {
                        $memberTotals[$memberNama][$weekLabel]++;
                    }

                    $weekNumber++;
                }
            }
        }

        ksort($memberTotals);

        $memberTotalsCollection = collect($memberTotals);

        return $memberTotalsCollection;
    }

    public function calculateBukuTotals($activeChart, $filteredPeminjamans)
    {
        $bukuTotals = [];

        if ($activeChart == "chart-bar-5tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $judulBuku = $peminjaman->eksemplar->buku->buku_judul;

                if (array_key_exists($judulBuku, $bukuTotals)) {
                    $bukuTotals[$judulBuku]++;
                } else {
                    $bukuTotals[$judulBuku] = 1;
                }
            }
        } elseif ($activeChart == "chart-bar-tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $judulBuku = $peminjaman->eksemplar->buku->buku_judul;
                $klasifikasiBuku = $peminjaman->eksemplar->buku->klasifikasi->klasifikasi_nama;
                $tipeKoleksiBuku = $peminjaman->eksemplar->eksemplar_tipe_koleksi;
                if (array_key_exists($judulBuku, $bukuTotals)) {
                    $bukuTotals[$judulBuku]++;
                } else {
                    $bukuTotals[$judulBuku] = 1;
                }
            }

        } elseif ($activeChart == "chart-bar-bulan") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $judulBuku = $peminjaman->eksemplar->buku->buku_judul;

                if (array_key_exists($judulBuku, $bukuTotals)) {
                    $bukuTotals[$judulBuku]++;
                } else {
                    $bukuTotals[$judulBuku] = 1;
                }
            }
        }

        ksort($bukuTotals);

        $bukuTotalsCollection = collect($bukuTotals);

        return $bukuTotalsCollection;
    }

    public function calculateKlasifikasiTotals($activeChart, $filteredPeminjamans)
    {
        $klasifikasiTotals = [];

        if ($activeChart == "chart-bar-5tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $klasifikasiBuku = $peminjaman->eksemplar->buku->klasifikasi->klasifikasi_nama;

                if (array_key_exists($klasifikasiBuku, $klasifikasiTotals)) {
                    $klasifikasiTotals[$klasifikasiBuku]++;
                } else {
                    $klasifikasiTotals[$klasifikasiBuku] = 1;
                }
            }
        } elseif ($activeChart == "chart-bar-tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $klasifikasiBuku = $peminjaman->eksemplar->buku->klasifikasi->klasifikasi_nama;

                if (array_key_exists($klasifikasiBuku, $klasifikasiTotals)) {
                    $klasifikasiTotals[$klasifikasiBuku]++;
                } else {
                    $klasifikasiTotals[$klasifikasiBuku] = 1;
                }
            }
        } elseif ($activeChart == "chart-bar-bulan") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $klasifikasiBuku = $peminjaman->eksemplar->buku->klasifikasi->klasifikasi_nama;

                if (array_key_exists($klasifikasiBuku, $klasifikasiTotals)) {
                    $klasifikasiTotals[$klasifikasiBuku]++;
                } else {
                    $klasifikasiTotals[$klasifikasiBuku] = 1;
                }
            }
        }

        ksort($klasifikasiTotals);

        $klasifikasiTotalsCollection = collect($klasifikasiTotals);

        return $klasifikasiTotalsCollection;
    }

    public function calculateTipeTotals($activeChart, $filteredPeminjamans)
    {
        $tipeKoleksiTotals = [];

        if ($activeChart == "chart-bar-5tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $tipeKoleksiBuku = $peminjaman->eksemplar->eksemplar_tipe_koleksi;

                if (array_key_exists($tipeKoleksiBuku, $tipeKoleksiTotals)) {
                    $tipeKoleksiTotals[$tipeKoleksiBuku]++;
                } else {
                    $tipeKoleksiTotals[$tipeKoleksiBuku] = 1;
                }
            }
        } elseif ($activeChart == "chart-bar-tahun") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $tipeKoleksiBuku = $peminjaman->eksemplar->eksemplar_tipe_koleksi;

                if (array_key_exists($tipeKoleksiBuku, $tipeKoleksiTotals)) {
                    $tipeKoleksiTotals[$tipeKoleksiBuku]++;
                } else {
                    $tipeKoleksiTotals[$tipeKoleksiBuku] = 1;
                }
            }
        } elseif ($activeChart == "chart-bar-bulan") {
            foreach ($filteredPeminjamans as $peminjaman) {
                $tipeKoleksiBuku = $peminjaman->eksemplar->eksemplar_tipe_koleksi;

                if (array_key_exists($tipeKoleksiBuku, $tipeKoleksiTotals)) {
                    $tipeKoleksiTotals[$tipeKoleksiBuku]++;
                } else {
                    $tipeKoleksiTotals[$tipeKoleksiBuku] = 1;
                }
            }
        }

        ksort($tipeKoleksiTotals);

        $tipeKoleksiTotalsCollection = collect($tipeKoleksiTotals);

        return $tipeKoleksiTotalsCollection;
    }

    private function getDataForChart($activeChart)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $peminjamans = $this->peminjamanBukuService->findAll('peminjaman_id', 'asc');

        $filteredPeminjamans = collect([]);

        switch ($activeChart) {
            case 'chart-bar-5tahun':
                $filteredPeminjamans = $peminjamans->filter(function ($peminjaman) {
                    return Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->diffInYears(now()) <= 5;
                });
                break;

            case 'chart-bar-tahun':
                $filteredPeminjamans = $peminjamans->filter(function ($peminjaman) {
                    $tgl_pinjam = Carbon::parse($peminjaman->peminjaman_tgl_pinjam);
                    $currentYear = now()->year;

                    return $tgl_pinjam->diffInMonths(now()) <= 12 && $tgl_pinjam->year == $currentYear;
                });
                break;

            case 'chart-bar-bulan':
                $filteredPeminjamans = $peminjamans->filter(function ($peminjaman) {
                    return Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->isSameMonth(Carbon::now());
                });
                break;
        }

        $data = collect([
            'filteredPeminjamans' => $filteredPeminjamans,
            'totalPerYear' => $this->getCountsPerYear($filteredPeminjamans),
            'totalPerMonth' => $this->getCountsPerMonth($filteredPeminjamans),
            'totalPerWeek' => $this->getCountsPerWeek($filteredPeminjamans),
        ]);

        return $data;
    }

    private function getCountsPerYear($filteredPeminjamans)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $filteredPeminjamans->groupBy(function ($peminjaman) {
            return Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->format('Y');
        })->map->count();
    }

    private function getCountsPerMonth($filteredPeminjamans)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $currentYear = now()->year;
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $counts = $filteredPeminjamans->filter(function ($peminjaman) use ($i, $currentYear) {
                $peminjamanMonth = Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->month;
                $peminjamanYear = Carbon::parse($peminjaman->peminjaman_tgl_pinjam)->year;

                return $peminjamanMonth === $i && $peminjamanYear === $currentYear;
            });

            $months[$i] = $counts->count();
        }
        return collect($months);
    }

    private function getCountsPerWeek($filteredPeminjamans)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $weeks = [];
        $currentMonth = now()->month;
        $weeksInMonth = \Carbon\CarbonPeriod::create(now()->startOfMonth(), '1 week', now()->endOfMonth());
        $weekNumber = 1;

        for ($i = 1; $i <= 5; $i++) {
            $weeks['Minggu-' . $i] = 0;
        }

        foreach ($weeksInMonth as $weekStartDate) {
            $weekStartDate = $weekStartDate->startOfWeek();
            $weekEndDate = $weekStartDate->copy()->endOfWeek();
            $peminjamanMingguIni = $filteredPeminjamans->filter(function ($peminjaman) use ($weekStartDate, $weekEndDate) {
                $peminjamanDate = Carbon::parse($peminjaman->peminjaman_tgl_pinjam);

                return $peminjamanDate->isBetween($weekStartDate, $weekEndDate, true, true) || $peminjamanDate->isSameDay($weekEndDate);
            });

            $totalMingguIni = $peminjamanMingguIni->count();

            $weekLabel = "Minggu-" . $weekNumber;

            $weeks[$weekLabel] = $totalMingguIni;

            $weekNumber++;
        }
        return collect($weeks);
    }

}
