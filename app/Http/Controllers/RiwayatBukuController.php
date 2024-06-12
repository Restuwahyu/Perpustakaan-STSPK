<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EksemplarService;
use App\Services\PeminjamanBukuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RiwayatBukuController extends Controller
{
    protected $eksemplarService;
    protected $peminjamanBukuService;

    public function __construct(EksemplarService $eksemplarService, PeminjamanBukuService $peminjamanBukuService)
    {
        $this->eksemplarService = $eksemplarService;
        $this->peminjamanBukuService = $peminjamanBukuService;
    }

    // Tampil Halaman Daftar RiwayatBuku
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $riwayatBukus = $this->peminjamanBukuService->findAll('updated_at', 'ASC');
        // dd($riwayatBukus);
        return view('riwayat_buku.show', compact('riwayatBukus'));
    }

    // Tampil Halaman Tambah Daftar Pengembalian Buku
    public function show(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (session('success')) {
            $riwayatBukus = session('riwayatBukus');
            return view('pengembalian_buku.show', compact('riwayatBukus'));
        }
        // dd($riwayatBukus);

        return view('pengembalian_buku.show');

    }

    //Pengembalian Buku
    public function pengembalianBuku(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $eksemplar_kode = $request->input('eksemplar_kode');

        $eksemplar = $this->eksemplarService->findByKodeEksemplar($eksemplar_kode);

        if ($eksemplar == null) {
            return redirect()->route('showPengembalianBuku')->with('error', "Kode Buku Tidak Ditemukan");
        } else if ($eksemplar->eksemplar_status == 1) {
            return redirect()->route('showPengembalianBuku')->with('error', "Buku Belum Dipinjam");
        }

        $peminjaman = $this->peminjamanBukuService->findByEksemplarId($eksemplar->eksemplar_id);
        $data = [];
        $updated = $this->peminjamanBukuService->update($peminjaman->peminjaman_id, $data);

        $riwayatBukus = $this->peminjamanBukuService->findPeminjamanByMemberId($peminjaman->peminjaman_member, [0]);

        Session::put('riwayatBukus', $riwayatBukus);
        return redirect()->route('showPengembalianBuku')->with('success', "Buku Telah Dikembalikan");
    }
}
