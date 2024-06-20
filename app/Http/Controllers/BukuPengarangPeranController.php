<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BukuPengarangPeranService;
use App\Services\KategoriService;
use App\Services\PengarangService;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuPengarangPeranController extends Controller
{
    protected $bukuPengarangPeranService;
    protected $pengarangService;
    protected $kategoriService;

    public function __construct(BukuPengarangPeranService $bukuPengarangPeranService, PengarangService $pengarangService, KategoriService $kategoriService)
    {
        $this->bukuPengarangPeranService = $bukuPengarangPeranService;
        $this->pengarangService = $pengarangService;
        $this->kategoriService = $kategoriService;
    }

    // Read Daftar Pengarang
    public function json()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = $this->bukuPengarangPeranService->findByPengarangId();

        return DataTables::eloquent($query)
            ->make(true);
    }

    // Tampil Halaman Daftar Pengarang
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pengarang_id = $request->input('pengarang_id');
        $pengarang = $this->pengarangService->findById($pengarang_id);
        $datas = $this->bukuPengarangPeranService->findByPengarangId($pengarang_id);

        foreach ($datas as $data) {
            $bukus = $data->bukus;
            $eksemplar_collection = $bukus->flatMap->eksemplars;
            $eksemplar_no_panggil_unik = $eksemplar_collection->unique('eksemplar_no_panggil')->pluck('eksemplar_no_panggil')->first();
            $data->eksemplar_no_panggil = $eksemplar_no_panggil_unik;
        }

        return view('pengarang.show_peran', compact('pengarang', 'datas'));
    }
}
