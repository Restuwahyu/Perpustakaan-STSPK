<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BukuService;
use App\Services\EksemplarService;
use App\Services\PeminjamanBukuService;
use App\Services\PesanBukuService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanBukuController extends Controller
{
    protected $pesanBukuService;
    protected $bukuService;
    protected $eksemplarService;
    protected $peminjamanBukuService;

    public function __construct(PesanBukuService $pesanBukuService, BukuService $bukuService, EksemplarService $eksemplarService, PeminjamanBukuService $peminjamanBukuService)
    {
        $this->pesanBukuService = $pesanBukuService;
        $this->bukuService = $bukuService;
        $this->eksemplarService = $eksemplarService;
        $this->peminjamanBukuService = $peminjamanBukuService;
    }

    // Tampil Halaman Daftar Pesanan Buku
    public function index()
    {
        $member = session('member');
        $pemesanans = $this->pesanBukuService->findPemesanByMemberId($member->member_id)->get();

        foreach ($pemesanans as $pemesanan) {
            $buku = $this->bukuService->findById($pemesanan->eksemplars->buku_id);
            $pemesanan->bukus = $buku;
            if ($pemesanan->pemesanan_buku_status == 1) {
                $this->pesanBukuService->destroy($pemesanan->pemesanan_buku_id);
            }
        }

        return view('member.show_pemesanan', compact('pemesanans'));
    }

    public function store(Request $request)
    {
        if ($request->session()->has('member')) {
            $member_id = $request->session()->get('member')['member_id'];
            $eksemplar_id = $request->input('eksemplar_id');
            $tanggal_pemesanan = Carbon::parse($request->input('tanggal_pemesanan'))->format('Y-m-d');
            $tanggal_pengambilan = Carbon::parse($request->input('tanggal_pengambilan'))->format('Y-m-d');
            $selisih_hari = Carbon::parse($tanggal_pemesanan)->diffInDays($tanggal_pengambilan);
            $hari_pengambilan = Carbon::parse($tanggal_pengambilan)->translatedFormat('l');
            $hari_ini = Carbon::today()->format('Y-m-d');
            $eksemplar = $this->eksemplarService->findById($eksemplar_id);

            if ($tanggal_pemesanan < $hari_ini || $tanggal_pengambilan < $hari_ini) {
                return redirect()->route('showPemesananBuku')->with('error', 'Pemesanan buku hanya dapat dilakukan untuk tanggal yang akan datang.');
            } else if ($selisih_hari < 1) {
                return redirect()->route('showPemesananBuku')->with('error', 'Pemesanan harus dilakukan minimal 1 hari sebelum pengambilan buku.');
            } else if ($hari_pengambilan == 'Sabtu' || $hari_pengambilan == 'Minggu') {
                return redirect()->route('showPemesananBuku')->with('error', 'Pengambilan buku harus dilakukan pada jam kerja (Senin - Jumat, 08:00 - 16:00 WIB).');
            } else if ($eksemplar->eksemplar_status != 1) {
                return redirect()->route('showPemesananBuku')->with('error', 'Buku sudah di pesan tidak dapat melakukan pemesanan.');
            }

            $data = [
                'pemesanan_buku_member' => $member_id,
                'pemesanan_buku_eksemplar' => $eksemplar_id,
                'pemesanan_buku_tanggal_pemesanan' => $tanggal_pemesanan,
                'pemesanan_buku_tanggal_pengambilan' => $tanggal_pengambilan,
                'pemesanan_buku_status' => 0,
            ];

            $simpan = $this->pesanBukuService->add($data);

            $eksemplar->eksemplar_status = 3;
            $eksemplar->save();

            return redirect()->route('showPemesananBuku')->with('success', 'Pemesanan buku berhasil dibuat.');
        } else {
            return back()->with('error', 'Anda harus login terlebih dahulu untuk melakukan pemesanan buku.');
        }
    }

    public function delete(Request $request)
    {
        $member = $request->session()->has('member');
        $role = $request->input('role');

        if ($member || $role) {
            $pemesanan_id = $request->input('pemesanan_id');
            $pemesanan = $this->pesanBukuService->findById($pemesanan_id);
            $eksemplar = $this->eksemplarService->findById($pemesanan->pemesanan_buku_eksemplar);

            if ($pemesanan && $role == null) {
                $eksemplar->eksemplar_status = 1;
                $eksemplar->save();
                $this->pesanBukuService->destroy($pemesanan_id);
                return redirect()->route('showPemesananBuku')->with('success', 'Pemesanan buku berhasil dibatalkan.');
            } else if (!$pemesanan && $role == null) {
                return redirect()->route('showPemesananBuku')->with('error', 'Pemesanan buku tidak ditemukan.');
            }

            if ($pemesanan && $role == 'user') {
                $this->pesanBukuService->destroy($pemesanan_id);
                return redirect()->route('home')->with('success', 'Pemesanan buku telah selesai.');
            } else if (!$pemesanan && $role == 'user') {
                return redirect()->route('home')->with('error', 'Pemesanan buku tidak ditemukan.');
            }
        } else if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function verifikasi(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pemesanan_id = $request->input('pemesanan_id');
        $pemesanan = $this->pesanBukuService->findById($pemesanan_id);

        $pemesanan->pemesanan_buku_status = 2;
        $pemesanan->save();

        return redirect()->route('home')->with('success', 'Pemesanan buku telah diverifikasi.');
    }

    public function selesaiTransaksi(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user_id = $request->input('user_id');
        $pemesanan_id = $request->input('pemesanan_id');
        $pemesanan = $this->pesanBukuService->findById($pemesanan_id);
        $eksemplar = $this->eksemplarService->findById($pemesanan->pemesanan_buku_eksemplar);

        $data = [
            'peminjaman_user' => $user_id,
            'peminjaman_member' => $pemesanan->pemesanan_buku_member,
            'peminjaman_eksemplar' => $pemesanan->pemesanan_buku_eksemplar,
            'peminjaman_tgl_pinjam' => Carbon::now(),
            'peminjaman_tgl_kembali' => Carbon::now()->addMonth(),
            'peminjaman_status' => 1,
        ];

        $this->peminjamanBukuService->add($data);

        $pemesanan->pemesanan_buku_status = 1;
        $pemesanan->save();

        $eksemplar->eksemplar_status = 2;
        $eksemplar->save();

        $this->pesanBukuService->destroy($pemesanan_id);
        return redirect()->route('home')->with('success', 'Pemesanan buku telah selesai.');
    }
}
