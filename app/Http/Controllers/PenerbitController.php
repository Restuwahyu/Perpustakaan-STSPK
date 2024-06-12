<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PenerbitService;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerbitController extends Controller
{
    protected $penerbitService;

    public function __construct(PenerbitService $penerbitService)
    {
        $this->penerbitService = $penerbitService;
    }

    // Read Daftar Penerbit
    public function json()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = $this->penerbitService->findPenerbits();

        return DataTables::eloquent($query)
            ->make(true);
    }

    // Tampil Halaman Daftar Penerbit
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('penerbit.show');
    }

    // Proses Tambah Daftar Penerbit
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $penerbit_nama = $request->penerbit_nama;
        $existingPenerbit = $this->penerbitService->findByName($penerbit_nama);

        if ($existingPenerbit) {
            return redirect()->route('showPenerbit')->with('error', "Penerbit Sudah Ada.");
        }

        $data = [
            'penerbit_nama' => $request->penerbit_nama,
        ];

        $simpan = $this->penerbitService->add($data);

        return redirect()->route('showPenerbit')->with('success', "Tambah Penerbit");
    }

    // Proses Edit Daftar Penerbit
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $penerbit_id = $request->input('penerbit_id_edit');
        $penerbit_nama = $request->penerbit_nama;

        $existingPenerbit = $this->penerbitService->findByName($penerbit_nama);

        if ($existingPenerbit) {
            return redirect()->route('showPenerbit')->with('error', "Penerbit Sudah Ada.");
        }

        $data = [
            'penerbit_nama' => $penerbit_nama,
        ];

        $updated = $this->penerbitService->update($penerbit_id, $data);

        return redirect()->route('showPenerbit')->with('success', "Edit Penerbit");
    }

    // Proses Delete Selected Penerbit
    public function deleteSelectedPenerbits(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedPenerbitIds = $request->input('selected_penerbit_ids');
        $deletedCount = $this->penerbitService->deleteSelectedPenerbits($selectedPenerbitIds);

        if ($deletedCount > 0) {
            return redirect()->route('showPenerbit')->with('success', 'Hapus Penerbit');
        } else if ($deletedCount == -1) {
            return redirect()->route('showPenerbit')->with('error', 'Hapus Penerbit. Penerbit Masih Dipakai Pada Buku');
        } else {
            return redirect()->route('showPenerbit')->with('error', 'Hapus Penerbit: Tidak Ada Data Terpilih');
        }
    }
}
