<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\KlasifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KlasifikasiController extends Controller
{
    protected $klasifikasiService;

    public function __construct(KlasifikasiService $klasifikasiService)
    {
        $this->klasifikasiService = $klasifikasiService;
    }

    // Tampil Halaman Daftar Klasifikasi
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $klasifikasis = $this->klasifikasiService->findAll('klasifikasi_nama', 'ASC')->get();

        return view('klasifikasi.show', compact('klasifikasis'));
    }

    // Proses Tambah Daftar Klasifikasi
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $klasifikasi_nama = $request->klasifikasi_nama;
        $klasifikasi_kode = $request->klasifikasi_kode;

        $existingKlasifikasiName = $this->klasifikasiService->findByName($klasifikasi_nama);
        $existingKlasifikasiKode = $this->klasifikasiService->findByKode($klasifikasi_kode);

        if ($existingKlasifikasiName || $existingKlasifikasiKode) {
            return redirect()->route('showKlasifikasi')->with('error', "Klasifikasi Sudah Ada.");
        }

        $data = [
            'klasifikasi_kode' => $request->klasifikasi_kode,
            'klasifikasi_nama' => $request->klasifikasi_nama,
        ];

        $simpan = $this->klasifikasiService->add($data);

        return redirect()->route('showKlasifikasi')->with('success', "Tambah Klasifikasi");
    }

    // Proses Edit Daftar Klasifikasi
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $klasifikasi_id = $request->input('klasifikasi_id_edit');
        $klasifikasi = $this->klasifikasiService->findById($klasifikasi_id);
        $klasifikasi_nama = $request->klasifikasi_nama;
        $klasifikasi_kode = $request->klasifikasi_kode;

        $existingKlasifikasiName = $this->klasifikasiService->findByName($klasifikasi_nama);
        $existingKlasifikasiKode = $this->klasifikasiService->findByKode($klasifikasi_kode);

        if ($existingKlasifikasiName && !$klasifikasi->klasifikasi_nama) {
            return redirect()->route('showKlasifikasi')->with('error', "Nama Klasifikasi Sudah Ada.");
        } else if ($existingKlasifikasiKode && !$klasifikasi->klasifikasi_kode) {
            return redirect()->route('showKlasifikasi')->with('error', "Kode Klasifikasi Sudah Ada.");
        }

        $data = [
            'klasifikasi_kode' => $klasifikasi_kode,
            'klasifikasi_nama' => $klasifikasi_nama,
        ];

        $updated = $this->klasifikasiService->update($klasifikasi_id, $data);

        return redirect()->route('showKlasifikasi')->with('success', "Edit Klasifikasi");
    }

    // Proses Delete Selected Klasifikasi
    public function deleteSelectedKlasifikasis(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedKlasifikasiIds = $request->input('selected_klasifikasi_ids');

        // dd($selectedKlasifikasiIds);
        $deletedCount = $this->klasifikasiService->deleteSelectedKlasifikasis($selectedKlasifikasiIds);

        if ($deletedCount == -1) {
            return redirect()->route('showKlasifikasi')->with('error', "Hapus Klasifikasi. Klasifikasi Masih Dipakai Pada Buku");
        } else if ($deletedCount > 0) {
            return redirect()->route('showKlasifikasi')->with('success', 'Hapus Klasifikasi');
        } else {
            return redirect()->route('showKlasifikasi')->with('error', 'Hapus Klasifikasi: Tidak Ada Data Terpilih');
        }
    }
}
