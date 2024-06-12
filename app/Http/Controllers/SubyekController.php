<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SubyekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubyekController extends Controller
{
    protected $subyekService;

    public function __construct(SubyekService $subyekService)
    {
        $this->subyekService = $subyekService;
    }

    // Tampil Halaman Daftar Subyek
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $subyeks = $this->subyekService->findAll('subyek_nama', 'ASC')->get();

        return view('subyek.show', compact('subyeks'));
    }

    // Proses Tambah Daftar Subyek
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $subyek_nama = $request->subyek_nama;

        $existingSubyek = $this->subyekService->findByName($subyek_nama);

        if ($existingSubyek) {
            return redirect()->route('showSubyek')->with('error', "Subyek Sudah Ada.");
        }

        $data = [
            'subyek_nama' => $request->subyek_nama,
        ];

        $simpan = $this->subyekService->add($data);

        if ($simpan) {
            return redirect()->route('showSubyek')->with('success', "Tambah Subyek");
        } else {
            return redirect()->route('showSubyek')->with('error', "Tambah Subyek");
        }
    }

    // Proses Edit Daftar Subyek
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $subyek_id = $request->input('subyek_id_edit');
        $subyek_nama = $request->subyek_nama;
        $existingSubyek = $this->subyekService->findByName($subyek_nama);

        if ($existingSubyek) {
            return redirect()->route('showSubyek')->with('error', "Subyek Sudah Ada.");
        }

        $subyek_nama = $request->subyek_nama;

        $data = [
            'subyek_nama' => $subyek_nama,
        ];

        $updated = $this->subyekService->update($subyek_id, $data);

        return redirect()->route('showSubyek')->with('success', "Edit Subyek");
    }

    // Proses Delete Selected Subyek
    public function deleteSelectedSubyeks(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedSubyekIds = $request->input('selected_subyek_ids');

        $deletedCount = $this->subyekService->deleteSelectedSubyeks($selectedSubyekIds);
        // dd($deletedCount);
        if ($deletedCount == -1) {
            return redirect()->route('showSubyek')->with('error', "Hapus Subyek. Subyek Masih Dipakai Pada Buku");
        } else if ($deletedCount > 0) {
            return redirect()->route('showSubyek')->with('success', 'Hapus Subyek');
        } else {
            return redirect()->route('showSubyek')->with('error', 'Hapus Subyek: Tidak Ada Data Terpilih');
        }
    }
}
