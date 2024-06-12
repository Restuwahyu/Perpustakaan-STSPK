<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\KategoriService;
use App\Services\PengarangService;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengarangController extends Controller
{
    protected $pengarangService;
    protected $kategoriService;

    public function __construct(PengarangService $pengarangService, KategoriService $kategoriService)
    {
        $this->pengarangService = $pengarangService;
        $this->kategoriService = $kategoriService;
    }

    // Read Daftar Pengarang
    public function json()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = $this->pengarangService->findPengarangs();

        return DataTables::eloquent($query)
            ->make(true);
    }

    // Tampil Halaman Daftar Pengarang
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $kategoris = $this->kategoriService->findAll('kategori_nama', 'ASC')->get();

        return view('pengarang.show', compact('kategoris'));
    }

    // Proses Tambah Daftar Pengarang
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pengarang_nama = $request->pengarang_nama;
        $pengarang_kategori = $request->pengarang_kategori;

        $existingPengarang = $this->pengarangService->findByNameCategory($pengarang_nama, $pengarang_kategori);

        if ($existingPengarang) {
            return redirect()->route('showPengarang')->with('error', "Pengarang Sudah Ada.");
        }

        $data = [
            'pengarang_nama' => $pengarang_nama,
            'pengarang_kategori' => $pengarang_kategori,
        ];

        $simpan = $this->pengarangService->add($data);

        return redirect()->route('showPengarang')->with('success', "Tambah Pengarang");
    }

    // Proses Tambah Daftar Kategori Pengarang
    public function storeKategori(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $kategori_nama = $request->kategori_nama;

        $data = [
            'kategori_nama' => $request->kategori_nama,
        ];

        $simpan = $this->kategoriService->add($data);

        if ($simpan) {
            return redirect()->route('showPengarang')->with('success', "Tambah Kategori Pengarang");
        } else {
            return redirect()->route('showPengarang')->with('error', "Tambah Kategori Pengarang");
        }
    }

    // Proses Edit Daftar Pengarang
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pengarang_id = $request->input('pengarang_id_edit');
        $pengarang_nama = $request->pengarang_nama;
        $pengarang_kategori = $request->pengarang_kategori;

        $existingPengarang = $this->pengarangService->findByNameCategory($pengarang_nama, $pengarang_kategori);

        if ($existingPengarang) {
            return redirect()->route('showPengarang')->with('error', "Pengarang Sudah Ada.");
        }

        $data = [
            'pengarang_nama' => $pengarang_nama,
            'pengarang_kategori' => $pengarang_kategori,
        ];

        $updated = $this->pengarangService->update($pengarang_id, $data);

        return redirect()->route('showPengarang')->with('success', "Edit Pengarang");
    }

    // Proses Delete Selected Pengarang
    public function deleteSelectedPengarangs(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedPengarangIds = $request->input('selected_pengarang_ids');

        $deletedCount = $this->pengarangService->deleteSelectedPengarangs($selectedPengarangIds);

        if ($deletedCount > 0) {
            return redirect()->route('showPengarang')->with('success', 'Hapus Pengarang');
        } else if ($deletedCount == -1) {
            return redirect()->route('showPengarang')->with('error', 'Hapus Pengarang. Pengarang Masih Dipakai Pada Buku');
        } else {
            return redirect()->route('showPengarang')->with('error', 'Hapus Pengarang: Tidak Ada Data Terpilih');
        }
    }

    // Proses Delete Selected Pengarang
    public function deleteSelectedPeranPengarangs(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedPeranPengarangIds = $request->input('selected_peran_pengarang_ids');

        $deletedCount = $this->pengarangService->deleteSelectedPengarangs($selectedPeranPengarangIds);

        if ($deletedCount > 0) {
            return redirect()->route('showPengarang')->with('success', 'Hapus Pengarang');
        } else if ($deletedCount == -1) {
            return redirect()->route('showPengarang')->with('error', 'Hapus Pengarang. Pengarang Masih Dipakai Pada Buku');
        } else {
            return redirect()->route('showPengarang')->with('error', 'Hapus Pengarang: Tidak Ada Data Terpilih');
        }
    }
}
