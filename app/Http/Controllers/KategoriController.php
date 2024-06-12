<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\KategoriService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    protected $kategoriService;

    public function __construct(KategoriService $kategoriService)
    {
        $this->kategoriService = $kategoriService;
    }

    // Tampil Halaman Daftar Kategori
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $kategoris = $this->kategoriService->findAll('kategori_nama', 'ASC')->get();

        return view('kategori.show', compact('kategoris'));
    }

    // Proses Tambah Daftar Kategori
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $kategori_nama = $request->kategori_nama;

        $existingKategori = $this->kategoriService->findByName($kategori_nama);

        if ($existingKategori) {
            return redirect()->route('showKategori')->with('error', "Kategori sudah ada.");
        }

        $data = [
            'kategori_nama' => $request->kategori_nama,
        ];

        $simpan = $this->kategoriService->add($data);

        if ($simpan) {
            return redirect()->route('showKategori')->with('success', "Tambah Kategori");
        } else {
            return redirect()->route('showKategori')->with('error', "Tambah Kategori");
        }
    }

    // Proses Edit Daftar Kategori
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $kategori_id = $request->input('kategori_id_edit');
        $kategori_nama = $request->kategori_nama;

        $existingKategori = $this->kategoriService->findByName($kategori_nama);

        if ($existingKategori) {
            return redirect()->route('showKategori')->with('error', "Kategori sudah ada.");
        }

        $data = [
            'kategori_nama' => $kategori_nama,
        ];

        $updated = $this->kategoriService->update($kategori_id, $data);

        return redirect()->route('showKategori')->with('success', "Edit Kategori");
    }

    // Proses Delete Selected Kategori
    public function deleteSelectedKategoris(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedKategoriIds = $request->input('selected_kategori_ids');
        $deletedCount = $this->kategoriService->deleteSelectedKategoris($selectedKategoriIds);

        if ($deletedCount > 0) {
            return redirect()->route('showKategori')->with('success', 'Hapus Kategori');
        } else if ($deletedCount == -1) {
            return redirect()->route('showKategori')->with('error', 'Hapus Kategori. Kategori Masih Dipakai Pada Pengarang');
        } else {
            return redirect()->route('showKategori')->with('error', 'Hapus Kategori: Tidak Ada Data Terpilih');
        }
    }
}
