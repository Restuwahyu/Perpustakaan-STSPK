<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BukuService;
use App\Services\EksemplarService;
use App\Services\MemberService;
use App\Services\PeminjamanBukuService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PeminjamanBukuController extends Controller
{
    protected $peminjamanBukuService;
    protected $memberService;
    protected $bukuService;
    protected $userService;

    public function __construct(
        PeminjamanBukuService $peminjamanBukuService,
        MemberService $memberService,
        EksemplarService $eksemplarService,
        BukuService $bukuService,
        UserService $userService,
    ) {
        $this->peminjamanBukuService = $peminjamanBukuService;
        $this->memberService = $memberService;
        $this->eksemplarService = $eksemplarService;
        $this->bukuService = $bukuService;
        $this->userService = $userService;
    }

    // Tampil Halaman Tambah Daftar Peminjaman Buku
    public function show(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('peminjaman_buku.show');
    }

    // Tampil Halaman Tambah Daftar Peminjaman Buku
    public function showStoreForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member = Session::get('memberPeminjaman');

        if (!$member) {
            Session::forget('memberPeminjaman');
            return redirect()->route('showPinjamBuku')->with('error', 'Member Tidak Ditemukan');
        }
        $loanBooksTemp = Session::get('loanBooksTemp', []);
        $peminjamans_id = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [1, 2]);
        $peminjamans_riwayat = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [0]);

        if (collect($loanBooksTemp)->contains('peminjaman_eksemplar', null)) {
            $loanBooksTemp = Session::forget('loanBooksTemp');
        }

        return view('peminjaman_buku.tambah', compact('member', 'loanBooksTemp', 'peminjamans_id', 'peminjamans_riwayat'));
    }

    // Proses Tambah Daftar Peminjaman Buku
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member = $this->memberService->findMemberByKode($request->member_kode);

        if (!$member) {
            Session::forget('memberPeminjaman');
            return redirect()->route('showPinjamBuku')->with('error', 'Member Tidak Ditemukan');
        }

        Session::put('memberPeminjaman', $member);

        if ($member->member_status == 0) {
            Session::forget('memberPeminjaman');
            return redirect()->route('showPinjamBuku')->with('error', 'Member Tidak Aktif');
        }

        $loanBooksTemp = Session::get('loanBooksTemp', []);

        return redirect()->route('tambahPinjamBuku');
    }

    // Proses Memasukkan Daftar Peminjaman Sementara
    public function tambahPeminjamanSementara(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member = session('memberPeminjaman');
        $eksemplar_kode = $request->input('eksemplar_kode');
        $user_id = $request->input('user_id');
        $eksemplar = $this->eksemplarService->findByKodeEksemplar($eksemplar_kode);
        $user = $this->userService->findById($user_id);

        if ($eksemplar == null) {
            return redirect()->route('tambahPinjamBuku')->with('error', 'Buku Tidak Ditemukan');
        } elseif ($eksemplar->eksemplar_status == 2) {
            return redirect()->route('tambahPinjamBuku')->with('error', 'Buku Masih Dalam Peminjaman');
        }

        $eksemplar->eksemplar_status = 2;
        $eksemplar->save();

        $loanBooksTemp = session('loanBooksTemp', []);

        if (collect($loanBooksTemp)->contains('peminjaman_eksemplar.eksemplar_kode', $eksemplar_kode)) {
            return redirect()->route('tambahPinjamBuku')->with('error', 'Buku Sudah Dimasukkan ke Daftar Peminjaman');
        }

        $peminjamans_id = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [1, 2]);
        $peminjamans_riwayat = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [0]);

        $newLoanBook = [
            'id' => Str::uuid(),
            'peminjaman_member' => $member,
            'peminjaman_eksemplar' => $eksemplar,
            'peminjaman_user' => $user,
            'peminjaman_tgl_pinjam' => Carbon::now(),
            'peminjaman_tgl_kembali' => Carbon::now()->addMonth(),
            'peminjaman_status' => 1,
        ];

        $loanBooksTemp[] = $newLoanBook;

        Session::put('loanBooksTemp', $loanBooksTemp);
        $loanBooksTemp = session('loanBooksTemp', []);

        if (collect($loanBooksTemp)->contains('peminjaman_eksemplar', null)) {
            $loanBooksTemp = Session::forget('loanBooksTemp');
            return redirect()->route('tambahPinjamBuku')->with('error', 'Kode Buku Tidak Ditemukan');
        }

        return redirect()->route('tambahPinjamBuku')->with('success', 'Buku Berhasil Ditambahkan Ke Daftar Peminjaman Sementara');
    }

    // Proses Create Daftar Peminjaman
    public function selesaiTransaksi(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $loanBooksTemp = Session::get('loanBooksTemp', []);

        foreach ($loanBooksTemp as $loanBookTemp) {
            $data = [
                'peminjaman_member' => $loanBookTemp['peminjaman_member']->member_id,
                'peminjaman_eksemplar' => $loanBookTemp['peminjaman_eksemplar']->eksemplar_id,
                'peminjaman_user' => $loanBookTemp['peminjaman_user']->user_id,
                'peminjaman_tgl_pinjam' => $loanBookTemp['peminjaman_tgl_pinjam'],
                'peminjaman_tgl_kembali' => $loanBookTemp['peminjaman_tgl_kembali'],
                'peminjaman_status' => $loanBookTemp['peminjaman_status'],
            ];
            $this->peminjamanBukuService->add($data);

            $eksemplar = $loanBookTemp['peminjaman_eksemplar'];
            $eksemplar->eksemplar_status = 2;
            $eksemplar->save();
        }

        Session::forget('memberPeminjaman');
        Session::forget('loanBooksTemp');

        $member = Session::get('memberPeminjaman');

        return redirect()->route('showPinjamBuku')->with('success', 'Transaksi Berhasil Diselesaikan');
    }

    // Proses Delete Daftar Peminjaman Sementara
    public function deletePeminjamanSementara(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $peminjaman_id = $request->input('id_peminjaman_delete');
        $member = session('memberPeminjaman');

        $peminjamans_id = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [1, 2]);
        $peminjamans_riwayat = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [0]);

        $loanBooksTemp = Session::get('loanBooksTemp', []);
        $indexToDelete = array_search($peminjaman_id, array_column($loanBooksTemp, 'id'));

        if ($indexToDelete !== false) {
            $eksemplar = $loanBooksTemp[$indexToDelete]['peminjaman_eksemplar'];
            $eksemplar->eksemplar_status = 1;
            $eksemplar->save();
            unset($loanBooksTemp[$indexToDelete]);
        }
        Session::put('loanBooksTemp', array_values($loanBooksTemp));

        return redirect()->route('tambahPinjamBuku')->with('success', 'Data peminjaman Sementara Berhasil Dihapus');
    }

    // Proses Edit Daftar Buku
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $peminjaman_id = $request->input('id_peminjaman_edit');
        $peminjaman = $this->peminjamanBukuService->findById($peminjaman_id);
        $member = session('memberPeminjaman');
        $peminjamans = $request->all();

        $updated = $this->peminjamanBukuService->update($peminjaman_id, $peminjamans);

        if ($updated) {
            $peminjamans_id = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [1, 2]);
            $peminjamans_riwayat = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [0]);

            return redirect()->route('tambahPinjamBuku')->with('success', 'Data Peminjaman Buku Berhasil Diupdate');
        } else {
            return redirect()->route('tambahPinjamBuku')->with('error', 'Gagal Update Peminjaman');
        }
    }

    public function destroy(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $peminjaman_id = $request->input('id_peminjaman_delete');

        $selectedBooks = Session::get('selectedBooks', []);

        $bookIndex = array_search($peminjaman_id, array_column($selectedBooks, 'peminjaman_id'));

        if ($bookIndex !== false) {
            array_splice($selectedBooks, $bookIndex, 1);
            Session::put('selectedBooks', $selectedBooks);

            return redirect()->back()->with('success', 'Buku Berhasil Dihapus dari Daftar Peminjaman Sementara');
        } else {
            return redirect()->back()->with('error', 'Buku Tidak Ditemukan pada Daftar Peminjaman Sementara');
        }
    }
}
