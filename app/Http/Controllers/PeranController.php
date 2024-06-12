<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PeranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeranController extends Controller
{
    protected $peranService;

    public function __construct(PeranService $peranService)
    {
        $this->peranService = $peranService;
    }

    // Tampil Halaman Daftar Peran
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $perans = $this->peranService->findAll('peran_id', 'desc')->get();

        return view('peran.show', compact('perans'));
    }

    // Proses Tambah Daftar Peran
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $peran_nama = $request->peran_nama;
        $existingPeran = $this->peranService->findByName($peran_nama);

        if ($existingPeran) {
            return redirect()->route('showPeran')->with('error', "Peran Sudah Ada.");
        }

        $data = [
            'peran_nama' => $request->peran_nama,
        ];

        $simpan = $this->peranService->add($data);

        return redirect()->route('showPeran')->with('success', "Tambah Peran");
    }

    // Proses Edit Daftar Peran
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $peran_id = $request->input('peran_id_edit');
        $peran_nama = $request->peran_nama;
        $existingPeran = $this->peranService->findByName($peran_nama);

        if ($existingPeran) {
            return redirect()->route('showPeran')->with('error', "Peran Sudah Ada.");
        }

        $data = [
            'peran_nama' => $peran_nama,
        ];

        $updated = $this->peranService->update($peran_id, $data);

        return redirect()->route('showPeran')->with('success', "Edit Peran");
    }

    // Proses Delete Selected Peran
    public function deleteSelectedPerans(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedPeranIds = $request->input('selected_peran_ids');

        $deletedCount = $this->peranService->deleteSelectedPerans($selectedPeranIds);

        if ($deletedCount > 0) {
            return redirect()->route('showPeran')->with('success', 'Hapus Peran');
        } else if ($deletedCount == -1) {
            return redirect()->route('showPeran')->with('error', 'Hapus Peran. Kategori Masih Dipakai Pada Pengarang');
        } else {
            return redirect()->route('showPeran')->with('error', 'Hapus Peran: Tidak Ada Data Terpilih');
        }
    }
}
