<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BahasaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BahasaController extends Controller
{
    protected $bahasaService;

    public function __construct(BahasaService $bahasaService)
    {
        $this->bahasaService = $bahasaService;
    }

    // Tampil Halaman Daftar Bahasa
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $bahasas = $this->bahasaService->findAll('bahasa_nama', 'ASC')->get();

        return view('bahasa.show', compact('bahasas'));
    }

    // Proses Tambah Daftar Bahasa
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $bahasa_nama = $request->bahasa_nama;
        $existingBahasa = $this->bahasaService->findByName($bahasa_nama);

        if ($existingBahasa) {
            return redirect()->route('showBahasa')->with('error', "Bahasa sudah ada.");
        }

        $data = [
            'bahasa_nama' => $request->bahasa_nama,
        ];

        $simpan = $this->bahasaService->add($data);

        return redirect()->route('showBahasa')->with('success', "Tambah Bahasa");
    }

    // Proses Edit Daftar Bahasa
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $bahasa_id = $request->input('bahasa_id_edit');
        $bahasa_nama = $request->bahasa_nama;
        $existingBahasa = $this->bahasaService->findByName($bahasa_nama);

        if ($existingBahasa) {
            return redirect()->route('showBahasa')->with('error', "Bahasa sudah ada.");
        }

        $data = [
            'bahasa_nama' => $bahasa_nama,
        ];

        $updated = $this->bahasaService->update($bahasa_id, $data);

        return redirect()->route('showBahasa')->with('success', "Edit Bahasa");
    }

    // Proses Delete Selected Bahasa
    public function deleteSelectedBahasas(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedBahasaIds = $request->input('selected_bahasa_ids');
        $deletedCount = $this->bahasaService->deleteSelectedBahasas($selectedBahasaIds);

        if ($deletedCount == -1) {
            return redirect()->route('showBahasa')->with('error', "Hapus Bahasa. Bahasa Masih Dipakai Pada Buku");
        } else if ($deletedCount > 0) {
            return redirect()->route('showBahasa')->with('success', 'Hapus Bahasa');
        } else {
            return redirect()->route('showBahasa')->with('error', 'Hapus Bahasa: Tidak Ada Data Terpilih');
        }
    }
}
