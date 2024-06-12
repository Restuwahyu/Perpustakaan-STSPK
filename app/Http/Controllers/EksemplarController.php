<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EksemplarService;
use DataTables;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Auth;

class EksemplarController extends Controller
{
    protected $eksemplarService;

    public function __construct(EksemplarService $eksemplarService)
    {
        $this->eksemplarService = $eksemplarService;
    }

    // Read Daftar Eksemplar
    public function json()
    {
        if (!Auth::check()) {
            return redirect()->route('login'); 
        }
        
        $query = $this->eksemplarService->findEksemplars();

        return DataTables::eloquent($query)
            ->make(true);
    }

    // Tampil Halaman Daftar Eksemplar
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login'); 
        }
        
        return view('eksemplar.show');
    }

    // Proses Tambah Daftar Eksemplar
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); 
        }
        
        $eksemplar_nama = $request->eksemplar_nama;

        $data = [
            'eksemplar_nama' => $request->eksemplar_nama,
        ];

        $simpan = $this->eksemplarService->add($data);

        return redirect()->route('showEksemplar')->with('success', "Tambah Eksemplar");
    }

    // Cetak Barcode Eksemplar
    public function printSelectedEksemplars(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); 
        }
        
        $selectedCetakIds = $request->input('selected_cetak_ids');
        $eksemplarIdsArray = json_decode($selectedCetakIds, true);
        $eksemplars = [];

        if (empty($eksemplarIdsArray)) {
            return redirect()->route('showEksemplar')->with('error', 'Cetak Eksemplar Gagal: Tidak Ada Data Terpilih');
        }

        foreach ($eksemplarIdsArray as $eksemplarId) {
            $eksemplar = $this->eksemplarService->findById($eksemplarId);
            $eksemplars[] = $eksemplar;
        }

        $pdf = PDF::loadView('eksemplar.barcode', compact('eksemplars'));

        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
        ]);

        // dd($pdf);
        return $pdf->download('eksemplar.pdf');
    }
}
