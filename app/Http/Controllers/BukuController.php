<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BahasaService;
use App\Services\BukuPengarangPeranService;
use App\Services\BukuService;
use App\Services\BukuSubyekService;
use App\Services\EksemplarService;
use App\Services\KategoriService;
use App\Services\KlasifikasiService;
use App\Services\PeminjamanBukuService;
use App\Services\PenerbitService;
use App\Services\PengarangService;
use App\Services\PeranService;
use App\Services\SubyekService;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class BukuController extends Controller
{
    protected $bukuService;
    protected $pengarangService;
    protected $kategoriService;
    protected $peranService;
    protected $penerbitService;
    protected $klasifikasiService;
    protected $subyekService;
    protected $eksemplarService;
    protected $peminjamanBukuService;
    protected $bahasaService;
    protected $bukuPengarangPeranService;
    protected $bukuSubyekService;

    public function __construct(
        BukuService $bukuService,
        PengarangService $pengarangService,
        KategoriService $kategoriService,
        PeranService $peranService,
        PenerbitService $penerbitService,
        KlasifikasiService $klasifikasiService,
        SubyekService $subyekService,
        EksemplarService $eksemplarService,
        PeminjamanBukuService $peminjamanBukuService,
        BahasaService $bahasaService,
        BukuPengarangPeranService $bukuPengarangPeranService,
        BukuSubyekService $bukuSubyekService,
    ) {
        $this->bukuService = $bukuService;
        $this->kategoriService = $kategoriService;
        $this->peranService = $peranService;
        $this->pengarangService = $pengarangService;
        $this->penerbitService = $penerbitService;
        $this->klasifikasiService = $klasifikasiService;
        $this->subyekService = $subyekService;
        $this->eksemplarService = $eksemplarService;
        $this->peminjamanBukuService = $peminjamanBukuService;
        $this->bahasaService = $bahasaService;
        $this->bukuPengarangPeranService = $bukuPengarangPeranService;
        $this->bukuSubyekService = $bukuSubyekService;
    }

    public function json()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $searchTerm = request()->get('search')['value'];

        $query = $this->bukuService->findBukus($searchTerm)->orderby('updated_at', 'desc');
        // dd($query);
        return DataTables::of($query)->make(true);
    }

    // Tampil Halaman Daftar Buku untuk Member
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('buku.show');
    }

    // Tampil Halaman Daftar Buku untuk Member
    public function showListBuku()
    {
        $bukus = $this->bukuService->findBukus()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $bukuDatas = [];

        $klasifikasis = $this->klasifikasiService->findAll('klasifikasi_nama', 'ASC')->get();

        if ($bukus->isNotEmpty()) {
            $bukuDatas = $bukus->map(function ($buku) {
                $buku_id = $buku->buku_id;
                $pengarang_ids = $this->bukuPengarangPeranService->findByBukuId($buku_id);

                $bukuData = [
                    'buku' => $buku,
                    'pengarangs' => [],
                    'stok' => 0,
                ];

                $bukuData['pengarangs'] = collect($pengarang_ids[0]['pengarangs'])
                    ->map(function ($pengarangData) {
                        $pengarang_id = $pengarangData['pengarang_id'];
                        $pengarang = $this->pengarangService->findById($pengarang_id);
                        return $pengarang->pengarang_nama;
                    });

                $bukuData['pengarang'] = $bukuData['pengarangs']->implode('; ');

                $stok = $buku->eksemplars->where('eksemplar_status', 1)->count();
                $bukuData['stok'] = $stok;

                return $bukuData;
            });
        }

        // dd($rekomendasiBuku);
        return view('list_buku.show', compact('bukuDatas', 'klasifikasis'));
    }

    // Tampil Halaman Search Buku Member
    public function searchBuku(Request $request)
    {
        $searchKeyword = $request->input('keywords');
        $searchJudul = $request->input('judul');
        $searchPengarang = $request->input('pengarang');
        $searchPenerbit = $request->input('penerbit');
        $searchTahunTerbit = $request->input('tahun_terbit');
        $searchIsbnIssn = $request->input('isbn_issn');
        $searchTipeKoleksi = $request->input('tipe_koleksi');
        $perPage = 30;
        $currentPage = request()->get('page', 1);

        $bukusQuery = $this->bukuService->findBukus()->orderby('updated_at', 'desc');
        // Mengekstrak Pengarang dan Subyek
        if ($searchKeyword) {
            $bukusQuery->where(function ($query) use ($searchKeyword) {
                $query->where('buku_judul', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhereHas('penerbit', function ($penerbitQuery) use ($searchKeyword) {
                        $penerbitQuery->where('penerbit_nama', 'LIKE', '%' . $searchKeyword . '%');
                    })
                    ->orWhereHas('klasifikasi', function ($klasifikasiQuery) use ($searchKeyword) {
                        $klasifikasiQuery->where('klasifikasi_nama', 'LIKE', '%' . $searchKeyword . '%');
                    })
                    ->orWhere('buku_tahun_terbit', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('buku_isbn_issn', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhereHas('pengarangs', function ($pengarangQuery) use ($searchKeyword) {
                        $pengarangQuery->where('pengarang_nama', 'LIKE', '%' . $searchKeyword . '%');
                    })
                    ->orWhereHas('subyeks', function ($subyekQuery) use ($searchKeyword) {
                        $subyekQuery->where('subyek_nama', 'LIKE', '%' . $searchKeyword . '%');
                    });
            });
        } else if ($searchJudul != null || $searchPengarang != null || $searchPenerbit != null || $searchTahunTerbit != null || $searchIsbnIssn != null || $searchTipeKoleksi != null) {
            $searchKeyword = $searchJudul . ' ' . $searchPengarang . ' ' . $searchPenerbit . ' ' . $searchTahunTerbit . ' ' . $searchIsbnIssn . ' ' . $searchTipeKoleksi;

            $bukusQuery->when($searchJudul, function ($query, $searchJudul) {
                $query->orWhere('buku_judul', 'LIKE', "%$searchJudul%");
            });

            $bukusQuery->when($searchPengarang, function ($query, $searchPengarang) {
                $query->whereHas('pengarangs', function ($pengarangQuery) use ($searchPengarang) {
                    $pengarangQuery->where('pengarang_nama', 'LIKE', "%$searchPengarang%");
                });
            });

            $bukusQuery->when($searchPenerbit, function ($query, $searchPenerbit) {
                $query->whereHas('penerbit', function ($penerbitQuery) use ($searchPenerbit) {
                    $penerbitQuery->where('penerbit_nama', 'LIKE', "%$searchPenerbit%");
                });
            });

            $bukusQuery->when($searchTahunTerbit, function ($query, $searchTahunTerbit) {
                $query->orWhere('buku_tahun_terbit', 'LIKE', "%$searchTahunTerbit%");
            });

            $bukusQuery->when($searchIsbnIssn, function ($query, $searchIsbnIssn) {
                $query->orWhere('buku_isbn_issn', 'LIKE', "%$searchIsbnIssn%");
            });

            $bukusQuery->when($searchTipeKoleksi, function ($query, $searchTipeKoleksi) {
                $query->WhereHas('eksemplars', function ($eksemplarQuery) use ($searchTipeKoleksi) {
                    $eksemplarQuery->where('eksemplar_tipe_koleksi', 'LIKE', "%$searchTipeKoleksi%");
                });
            });
        }

        $bukus = $bukusQuery->paginate($perPage, ['*'], 'page', $currentPage);
        $bukuDatas = [];

        if ($bukus->isNotEmpty()) {
            $bukuDatas = $bukus->map(function ($buku) {
                $buku_id = $buku->buku_id;
                $pengarang_ids = $this->bukuPengarangPeranService->findByBukuId($buku_id);

                $bukuData = [
                    'buku' => $buku,
                    'pengarangs' => [],
                    'stok' => 0,
                ];

                $bukuData['pengarangs'] = collect($pengarang_ids[0]['pengarangs'])
                    ->map(function ($pengarangData) {
                        $pengarang_id = $pengarangData['pengarang_id'];
                        $pengarang = $this->pengarangService->findById($pengarang_id);
                        return $pengarang->pengarang_nama;
                    });

                $bukuData['pengarang'] = $bukuData['pengarangs']->implode('; ');

                $stok = $buku->eksemplars->where('eksemplar_status', 1)->count();
                $bukuData['stok'] = $stok;

                return $bukuData;
            });
        }

        $paginatedBukus = $bukus->appends(request()->query());
        // dd($paginatedBukus);
        return view('list_buku.show_search', compact('bukus', 'bukuDatas', 'paginatedBukus', 'searchKeyword'));
    }

    // Tampilan Detail Buku Member
    public function showListBukuDetail(Request $request)
    {
        $buku_id = $request->input('buku');
        $buku = $this->bukuService->findById($buku_id);

        $eksemplar_collection = $buku->eksemplars;

        $eksemplar_no_panggil_unik = $eksemplar_collection->unique('eksemplar_no_panggil')->pluck('eksemplar_no_panggil')->first();
        $buku->eksemplar_no_panggil = $eksemplar_no_panggil_unik;

        $pengarang_ids = $this->bukuPengarangPeranService->findByBukuId($buku_id);

        $bukuData = [
            'buku' => $buku,
            'pengarang' => [],
            'peran' => [],
        ];

        $pengarangs = collect($pengarang_ids[0]['pengarangs'])->map(function ($pengarangData) {
            $pengarang_id = $pengarangData['pengarang_id'];
            $peran_id = $pengarangData['peran_id'];

            $pengarang = $this->pengarangService->findById($pengarang_id);
            $peran = $this->peranService->findById($peran_id);

            return [
                'pengarang' => $pengarang,
                'peran' => $peran,
            ];
        });

        $bukuData = [
            'buku' => $buku,
            'pengarangs' => $pengarangs,
        ];

        // dd($bukuData);
        return view('list_buku.show_detail', compact('bukuData'));
    }

    // Tampil Halaman Tambah Daftar Buku
    public function showStoreForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pengarang_kategori = $this->kategoriService->findAll('kategori_nama', 'ASC')->get();
        $pengarang_peran = $this->peranService->findAll('peran_nama', 'ASC')->get();
        $buku_penerbit = $this->penerbitService->findAll('penerbit_nama', 'ASC')->get();
        $buku_klasifikasi = $this->klasifikasiService->findAll('klasifikasi_kode', 'ASC')->get();
        $buku_subyek = $this->subyekService->findAll('subyek_nama', 'ASC')->get();
        $buku_bahasa = $this->bahasaService->findAll('bahasa_nama', 'ASC')->get();

        return view('buku.tambah', compact('pengarang_kategori', 'pengarang_peran', 'buku_penerbit', 'buku_klasifikasi', 'buku_subyek', 'buku_bahasa'));
    }

    public function getPengarangData(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $search = $request->input('search');
        $pengarangs = $this->pengarangService->searchPengarang($search);

        $data = [];
        foreach ($pengarangs as $pengarang) {
            $namaPengarang = $pengarang->pengarang_nama;
            $namaKategori = $pengarang->kategori->kategori_nama ?? null;

            $displayText = $namaKategori ? "$namaPengarang - $namaKategori" : $namaPengarang;

            $data[] = [
                'id' => $pengarang->pengarang_id,
                'text' => $displayText,
            ];
        }

        return response()->json($data);
    }

    public function getPenerbitData(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $search = $request->input('search');
        $penerbits = $this->penerbitService->searchPenerbit($search);

        $data = [];
        foreach ($penerbits as $penerbit) {
            $data[] = [
                'id' => $penerbit->penerbit_id,
                'text' => $penerbit->penerbit_nama,
            ];
        }

        return response()->json($data);
    }

    protected function validateForm(Request $request)
    {
        $rules = [
            'buku_judul' => 'required',
            'buku_deskripsi_fisik' => 'required',
            'pengarangs' => 'required',
            'buku_klasifikasi' => 'required',
            'subyeks' => 'required',
            'buku_penerbit' => 'required',
            'buku_tahun_terbit' => 'required',
            'buku_kota_terbit' => 'required',
            'buku_bahasa' => 'required',
            'eksemplars.*.eksemplar_no_panggil' => 'required',
            'eksemplars.*.eksemplar_no_eksemplar' => 'required',
            'buku_cover' => 'mimes:jpeg,png,jpg|max:500',
        ];

        $messages = [
            'buku_judul.required' => 'Judul Harus Diisi',
            'buku_deskripsi_fisik.required' => 'Deskripsi Fisik Harus Diisi',
            'pengarangs.required' => 'Pengarang dan Peran harus diisi',
            'buku_klasifikasi.required' => 'Klasifikasi Harus Diisi',
            'subyeks.required' => 'Subyek Harus Diisi',
            'buku_penerbit.required' => 'Penerbit Harus Diisi',
            'buku_tahun_terbit.required' => 'Tahun Terbit Harus Diisi',
            'buku_kota_terbit.required' => 'Kota Terbit Harus Diisi',
            'buku_bahasa.required' => 'Bahasa Harus Diisi',
            'eksemplars.*.eksemplar_no_panggil.required' => 'No. Panggil, No. Eksemplar, Tipe Koleksi, dan Status Buku harus diisi',
            'eksemplars.*.eksemplar_no_eksemplar.required' => 'No. Panggil, No. Eksemplar, Tipe Koleksi, dan Status Buku harus diisi',
            'buku_cover.mimes' => 'File Harus Berformat jpeg, jpg, atau png',
            'buku_cover.max' => 'Ukuran File Tidak Boleh Melebihi 1 MB',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    // Proses Tambah Daftar Buku
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $eksemplars = $request->input('eksemplars');
        $pengarangs = $request->input('pengarangs');
        $subyeks = $request->input('subyeks');
        $buku_judul = $request->buku_judul;
        $isbn = str_replace('-', '', $request->buku_isbn_issn);

        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            return redirect()->route('tambahBuku')
                ->withErrors($validator)
                ->withInput();
        }

        // Cek Cover Buku
        if ($request->hasFile('buku_cover')) {
            $image = $request->file('buku_cover');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('images/cover_original', $fileName, 'public');
            $compressedImage = Image::make($image)->encode('jpg', 30);
            $compressedFileName = 'compressed_' . $fileName;
            Storage::disk('public')->put('images/cover_compressed/' . $compressedFileName, $compressedImage);
            $bukuData["buku_cover_original"] = '/storage/images/cover_original/' . $fileName;
            $bukuData["buku_cover_compressed"] = '/storage/images/cover_compressed/' . $compressedFileName;
        } else {
            $bukuData["buku_cover_original"] = null;
            $bukuData["buku_cover_compressed"] = null;
        }

        $buku_cover = [
            'buku_cover_original' => $bukuData["buku_cover_original"],
            'buku_cover_compressed' => $bukuData["buku_cover_compressed"],
        ];

        $buku = [
            'buku_judul' => $request->buku_judul,
            'buku_deskripsi_fisik' => $request->buku_deskripsi_fisik,
            'buku_isbn_issn' => $request->buku_isbn_issn,
            'buku_edisi' => $request->buku_edisi,
            'buku_seri' => $request->buku_seri,
            'buku_klasifikasi' => $request->buku_klasifikasi,
            'buku_penerbit' => $request->buku_penerbit,
            'buku_tahun_terbit' => $request->buku_tahun_terbit,
            'buku_kota_terbit' => $request->buku_kota_terbit,
            'buku_bahasa' => $request->buku_bahasa,
            'buku_cover_original' => $bukuData["buku_cover_original"],
            'buku_cover_compressed' => $bukuData["buku_cover_compressed"],
        ];

        $simpan_buku = $this->bukuService->add($buku);

        foreach ($eksemplars as $eksemplarData) {
            $eksemplar = [
                'buku_id' => $simpan_buku->buku_id,
                'eksemplar_kode' => $this->eksemplarService->generateKodeEksemplar(),
                'eksemplar_no_panggil' => $eksemplarData["eksemplar_no_panggil"],
                'eksemplar_no_eksemplar' => $eksemplarData["eksemplar_no_eksemplar"],
                'eksemplar_tipe_koleksi' => $eksemplarData["eksemplar_tipe_koleksi"],
                'eksemplar_status' => $eksemplarData["eksemplar_status"],
            ];
            $simpan_eksemplar = $this->eksemplarService->add($eksemplar);
            $eksemplar_id = $simpan_eksemplar->eksemplar_id;
            $eksemplar_kode = $simpan_eksemplar->eksemplar_kode;
            $tahun = $request->buku_tahun_terbit;
            $generate_kode_inventaris = $this->eksemplarService->updateKodeInventaris($eksemplar_id, $eksemplar_kode, $tahun);
        }

        foreach ($subyeks as $subyek) {
            $data = [
                'buku_id' => $simpan_buku->buku_id,
                'subyek_id' => $subyek,
            ];

            $simpan_subyek = $this->bukuSubyekService->add($data);
        }

        foreach ($pengarangs as $pengarangData) {
            $data = [
                'buku_id' => $simpan_buku->buku_id,
                'pengarang_id' => $pengarangData["pengarang_id"],
                'peran_id' => $pengarangData["peran_id"],
                'user_id' => $request->user_id,
            ];

            $bukuPengarangPeran = $this->bukuPengarangPeranService->add($data);
        }

        return redirect()->route('showBuku')->with('success', "Tambah Buku");
    }

    // Proses Tambah Daftar Pengarang
    public function storePengarang(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $pengarang_nama = $request->pengarang_nama;
        $pengarang_kategori = $request->pengarang_kategori;

        $existingPengarang = $this->pengarangService->findByNameCategory($pengarang_nama, $pengarang_kategori);

        if ($existingPengarang) {
            return redirect()->route('tambahBuku')->with('error', "Pengarang Sudah Ada.");
        }

        $data = [
            'pengarang_nama' => $pengarang_nama,
            'pengarang_kategori' => $pengarang_kategori,
        ];

        $simpan = $this->pengarangService->add($data);

        if ($simpan) {
            $syarat = $request->syarat;
            if ($syarat == 'edit') {
                $buku_id = session('buku_id_edit');

                return redirect()->route('editBuku.get', ['id_buku_edit' => $buku_id])->with('success', "Tambah Pengarang");
            }
            return redirect()->route('tambahBuku')->with('success', "Tambah Pengarang");
        } else {
            return redirect()->route('tambahBuku')->with('error', "Tambah Pengarang");
        }
    }

    // Proses Tambah Daftar Penerbit Buku
    public function storePenerbit(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $penerbit_nama = $request->penerbit_nama;

        $existingPenerbit = $this->penerbitService->findByName($penerbit_nama);

        if ($existingPenerbit) {
            return redirect()->route('tambahBuku')->with('error', "Penerbit Sudah Ada.");
        }

        $data = [
            'penerbit_nama' => $request->penerbit_nama,
        ];

        $simpan = $this->penerbitService->add($data);

        if ($simpan) {
            $syarat = $request->syarat;
            if ($syarat == 'edit') {
                $buku_id = session('buku_id_edit');

                return redirect()->route('editBuku.get', ['id_buku_edit' => $buku_id])->with('success', "Tambah Penerbit");
            }
            return redirect()->route('tambahBuku')->with('success', "Tambah Penerbit");
        } else {
            return redirect()->route('tambahBuku')->with('error', "Tambah Penerbit");
        }
    }

    // Proses Tambah Daftar Kategori Pengarang
    public function storeKategori(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $kategori_nama = $request->kategori_nama;
        $existingKategori = $this->kategoriService->findByName($kategori_nama);

        if ($existingKategori) {
            return redirect()->route('tambahBuku')->with('error', "Kategori Pengarang Sudah Ada.");
        }

        $data = [
            'kategori_nama' => $request->kategori_nama,
        ];

        $simpan = $this->kategoriService->add($data);

        if ($simpan) {
            $syarat = $request->syarat;
            if ($syarat == 'edit') {
                $buku_id = session('buku_id_edit');

                return redirect()->route('editBuku.get', ['id_buku_edit' => $buku_id])->with('success', "Tambah Kategori Pengarang");
            }
            return redirect()->route('tambahBuku')->with('success', "Tambah Kategori Pengarang");
        } else {
            return redirect()->route('tambahBuku')->with('error', "Tambah Kategori Pengarang");
        }
    }

    // Proses Tambah Daftar Peran Pengarang
    public function storePeran(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $peran_nama = $request->peran_nama;

        $existingPeran = $this->peranService->findByName($peran_nama);

        if ($existingPeran) {
            return redirect()->route('tambahBuku')->with('error', "Peran Pengarang Sudah Ada.");
        }

        $data = [
            'peran_nama' => $request->peran_nama,
        ];

        $simpan = $this->peranService->add($data);

        if ($simpan) {
            $syarat = $request->syarat;
            if ($syarat == 'edit') {
                $buku_id = session('buku_id_edit');

                return redirect()->route('editBuku.get', ['id_buku_edit' => $buku_id])->with('success', "Tambah Peran Pengarang");
            }
            return redirect()->route('tambahBuku')->with('success', "Tambah Peran Pengarang");
        } else {
            return redirect()->route('tambahBuku')->with('error', "Tambah Peran Pengarang");
        }
    }

    // Proses Tambah Daftar Bahasa Pengarang
    public function storeBahasa(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $bahasa_nama = $request->bahasa_nama;
        $existingBahasa = $this->bahasaService->findByName($bahasa_nama);

        if ($existingBahasa) {
            return redirect()->route('tambahBuku')->with('error', "Bahasa Sudah Ada.");
        }

        $data = [
            'bahasa_nama' => $request->bahasa_nama,
        ];

        $simpan = $this->bahasaService->add($data);

        if ($simpan) {
            $syarat = $request->syarat;
            if ($syarat == 'edit') {
                $buku_id = session('buku_id_edit');

                return redirect()->route('editBuku.get', ['id_buku_edit' => $buku_id])->with('success', "Tambah Bahasa");
            }
            return redirect()->route('tambahBuku')->with('success', "Tambah Bahasa");
        } else {
            return redirect()->route('tambahBuku')->with('error', "Tambah Bahasa");
        }
    }

    // Proses Tambah Daftar Klasifikasi Buku
    public function storeKlasifikasi(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $klasifikasi_nama = $request->klasifikasi_nama;
        $klasifikasi_kode = $request->klasifikasi_kode;

        $existingKlasifikasiName = $this->klasifikasiService->findByName($klasifikasi_nama);
        $existingKlasifikasiKode = $this->klasifikasiService->findByKode($klasifikasi_kode);

        if ($existingKlasifikasiName || $existingKlasifikasiKode) {
            return redirect()->route('tambahBuku')->with('error', "Klasifikasi Sudah Ada.");
        }

        $data = [
            'klasifikasi_kode' => $request->klasifikasi_kode,
            'klasifikasi_nama' => $request->klasifikasi_nama,
        ];

        $simpan = $this->klasifikasiService->add($data);
        $syarat = $request->syarat;
        if ($syarat == 'edit') {
            $buku_id = session('buku_id_edit');

            return redirect()->route('editBuku.get', ['id_buku_edit' => $buku_id])->with('success', "Tambah Klasifikasi");
        }

        return redirect()->route('tambahBuku')->with('success', "Tambah Klasifikasi");
    }

    // Proses Tambah Daftar Subyek Buku
    public function storeSubyek(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $subyek_nama = $request->subyek_nama;
        $existingSubyek = $this->subyekService->findByName($subyek_nama);

        if ($existingSubyek) {
            return redirect()->route('tambahBuku')->with('error', "Subyek Sudah Ada.");
        }

        $data = [
            'subyek_nama' => $request->subyek_nama,
        ];

        $simpan = $this->subyekService->add($data);
        $syarat = $request->syarat;
        if ($syarat == 'edit') {
            $buku_id = session('buku_id_edit');

            return redirect()->route('editBuku.get', ['id_buku_edit' => $buku_id])->with('success', "Tambah Subyek");
        }
        return redirect()->route('tambahBuku')->with('success', "Tambah Subyek");
    }

    // Tampil Halaman Edit Daftar Buku
    public function showUpdateForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $buku_id = $request->input('id_buku_edit');

        // dd($request->all(), $buku_id);
        session(['buku_id_edit' => $buku_id]);

        $all = $this->bukuPengarangPeranService->findByBukuId($buku_id);
        $subyeks = $this->bukuSubyekService->findByBukuId($buku_id);
        $eksemplars = $this->eksemplarService->findByBukuId($buku_id)->get();
        $bukuData = collect($all)->map(function ($result) use ($subyeks, $eksemplars) {
            $buku_id = $result['buku_id'];
            $pengarangs = $result['pengarangs'];
            $buku = $this->bukuService->findById($buku_id);

            $subyeksCollection = collect($subyeks);
            $subyeksData = $subyeksCollection->pluck('subyeks')->flatten(1)->toArray();

            $pengarangsData = collect($pengarangs)->map(function ($pengarangData) {
                $buku_pengarang_peran_id = $pengarangData['buku_pengarang_peran_id'];
                $pengarang_id = $pengarangData['pengarang_id'];
                $peran_id = $pengarangData['peran_id'];

                $pengarang = $this->pengarangService->findById($pengarang_id);
                $peran = $this->peranService->findById($peran_id);

                return [
                    'buku_pengarang_peran_id' => $buku_pengarang_peran_id,
                    'pengarang' => $pengarang,
                    'peran' => $peran,
                ];
            });

            return [
                'buku' => $buku,
                'eksemplars' => $eksemplars,
                'pengarangs' => $pengarangsData,
                'subyeks' => collect($subyeksData),
            ];
        })->first();

        $buku_pengarang = $this->pengarangService->findAll('pengarang_nama', 'ASC')->get();
        $pengarang_kategori = $this->kategoriService->findAll('kategori_nama', 'ASC')->get();
        $pengarang_peran = $this->peranService->findAll('peran_nama', 'ASC')->get();
        $buku_penerbit = $this->penerbitService->findAll('penerbit_nama', 'ASC')->get();
        $buku_klasifikasi = $this->klasifikasiService->findAll('klasifikasi_kode', 'ASC')->get();
        $buku_subyek = $this->subyekService->findAll('subyek_nama', 'ASC')->get();
        $buku_bahasa = $this->bahasaService->findAll('bahasa_nama', 'ASC')->get();

        return view('buku.edit', compact('bukuData', 'buku_pengarang', 'pengarang_kategori', 'pengarang_peran', 'buku_penerbit', 'buku_klasifikasi', 'buku_subyek', 'buku_bahasa'));
    }

    // Proses Edit Daftar Buku
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $eksemplarDatas = $request->input('eksemplars');
        $pengarangDatas = $request->input('pengarangs');
        $subyekDatas = $request->input('subyeks');
        $buku_id = session('buku_id_edit');
        $buku = $this->bukuService->findById($buku_id);
        $pengarangs = $this->bukuPengarangPeranService->findByBukuId($buku_id);
        $eksemplars = $this->eksemplarService->findByBukuId($buku_id)->get();
        $bukuSubyeks = $this->bukuSubyekService->findByBukuId($buku_id);
        $buku_judul = $buku->buku_judul;

        foreach ($eksemplars as $eksemplar) {
            if ($eksemplar->eksemplar_status == 2) {
                return redirect()->route('showBuku')->with('error', "Edit Buku, Masih Dalam Proses Peminjaman.");
            }
        }

        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $errorMessage = implode(', ', $errorMessages);

            return redirect()->route('showBuku')->withErrors($validator)->with('error', "Edit Buku: $errorMessage");
        }

        if ($request->hasFile('buku_cover')) {
            $image = $request->file('buku_cover');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('images/cover_original', $fileName, 'public');
            $compressedImage = Image::make($image)->encode('jpg', 30);
            $compressedFileName = 'compressed_' . $fileName;
            Storage::disk('public')->put('images/cover_compressed/' . $compressedFileName, $compressedImage);
            $buku_cover_original = '/storage/images/cover_original/' . $fileName;
            $buku_cover_compressed = '/storage/images/cover_compressed/' . $compressedFileName;
            $buku->buku_cover_original = $buku_cover_original;
            $buku->buku_cover_compressed = $buku_cover_compressed;
            $buku->save();
        }

        $buku = [
            'buku_judul' => $request->buku_judul,
            'buku_deskripsi_fisik' => $request->buku_deskripsi_fisik,
            'buku_isbn_issn' => $request->buku_isbn_issn,
            'buku_edisi' => $request->buku_edisi,
            'buku_seri' => $request->buku_seri,
            'buku_klasifikasi' => $request->buku_klasifikasi,
            'buku_penerbit' => $request->buku_penerbit,
            'buku_tahun_terbit' => $request->buku_tahun_terbit,
            'buku_kota_terbit' => $request->buku_kota_terbit,
            'buku_bahasa' => $request->buku_bahasa,
        ];

        $update_buku = $this->bukuService->update($buku_id, $buku);

        foreach ($eksemplarDatas as $eksemplarData) {
            if (isset($eksemplarData["eksemplar_id"])) {
                $data = [
                    'eksemplar_id' => $eksemplarData["eksemplar_id"],
                    'eksemplar_no_panggil' => $eksemplarData["eksemplar_no_panggil"],
                    'eksemplar_no_eksemplar' => $eksemplarData["eksemplar_no_eksemplar"],
                    'eksemplar_tipe_koleksi' => $eksemplarData["eksemplar_tipe_koleksi"],
                    'eksemplar_status' => $eksemplarData["eksemplar_status"],
                ];
                $update_eksemplar = $this->eksemplarService->update($data["eksemplar_id"], $data);
            } else {
                $data = [
                    'buku_id' => $buku_id,
                    'eksemplar_kode' => $this->eksemplarService->generateKodeEksemplar(),
                    'eksemplar_no_panggil' => $eksemplarData["eksemplar_no_panggil"],
                    'eksemplar_no_eksemplar' => $eksemplarData["eksemplar_no_eksemplar"],
                    'eksemplar_tipe_koleksi' => $eksemplarData["eksemplar_tipe_koleksi"],
                    'eksemplar_status' => $eksemplarData["eksemplar_status"],
                ];

                $simpan_eksemplar = $this->eksemplarService->add($data);
                $eksemplar_id = $simpan_eksemplar->eksemplar_id;
                $eksemplar_kode = $simpan_eksemplar->eksemplar_kode;
                $tahun = $request->buku_tahun_terbit;
                $generate_kode_inventaris = $this->eksemplarService->updateKodeInventaris($eksemplar_id, $eksemplar_kode, $tahun);
            }
        }

        foreach ($eksemplars as $eksemplar) {
            $eksemplarFound = false;
            foreach ($eksemplarDatas as $eksemplarData) {
                if (isset($eksemplarData["eksemplar_id"]) && $eksemplarData["eksemplar_id"] == $eksemplar["eksemplar_id"]) {
                    $eksemplarFound = true;
                    break;
                }
            }

            if (!$eksemplarFound) {
                $delete_eksemplar = $this->eksemplarService->destroy($eksemplar->eksemplar_id);
            }
        }

        $existingSubyeks = $bukuSubyeks->pluck('subyeks')->flatten()->unique()->toArray();
        $subyekDatasCollection = collect($subyekDatas);

        $subyeksToDelete = array_values(array_diff($existingSubyeks, $subyekDatas));
        $bukuSubyekIdsToDelete = [];

        foreach ($bukuSubyeks as $bukuSubyek) {
            foreach ($bukuSubyek['subyeks'] as $index => $subyek) {
                if (in_array($subyek, $subyeksToDelete)) {
                    $bukuSubyekIdsToDelete[] = $bukuSubyek['buku_subyek_ids'][$index];
                }
            }
        }

        $bukuSubyekIdsToDelete = array_filter($bukuSubyekIdsToDelete);
        $bukuSubyekIdsToDelete = array_values($bukuSubyekIdsToDelete);

        $subyeksToInsert = array_values(array_diff($subyekDatas, $existingSubyeks));

        foreach ($bukuSubyekIdsToDelete as $bukuSubyekId) {
            $this->bukuSubyekService->destroy($bukuSubyekId);
        }

        foreach ($subyeksToInsert as $subyekId) {
            $data = [
                'buku_id' => $buku_id,
                'subyek_id' => $subyekId,
            ];
            $simpan_subyek = $this->bukuSubyekService->add($data);
        }

        foreach ($pengarangDatas as $pengarangData) {
            if (isset($pengarangData["buku_pengarang_peran_id"])) {
                $data = [
                    'buku_pengarang_peran_id' => $pengarangData["buku_pengarang_peran_id"],
                    'buku_id' => $buku_id,
                    'pengarang_id' => $pengarangData["pengarang_id"],
                    'peran_id' => $pengarangData["peran_id"],
                    'user_id' => $request->user_id,
                ];

                $update_pengarang = $this->bukuPengarangPeranService->update($data["buku_pengarang_peran_id"], $data);
            } else {
                $data = [
                    'buku_id' => $buku_id,
                    'pengarang_id' => $pengarangData["pengarang_id"],
                    'peran_id' => $pengarangData["peran_id"],
                    'user_id' => $request->user_id,
                ];

                $bukuPengarangPeran = $this->bukuPengarangPeranService->add($data);
            }
        }

        foreach ($pengarangs[0]["pengarangs"] as $pengarang) {
            $pengarangFound = false;
            foreach ($pengarangDatas as $pengarangData) {
                if (isset($pengarangData["buku_pengarang_peran_id"]) && $pengarangData["buku_pengarang_peran_id"] == $pengarang["buku_pengarang_peran_id"]) {
                    $pengarangFound = true;
                    break;
                }
            }

            if (!$pengarangFound) {
                $delete_pengarang = $this->bukuPengarangPeranService->destroy($pengarang['buku_pengarang_peran_id']);
            }
        }

        return redirect()->route('showBuku')->with('success', "Edit Buku");
    }

    // Proses Delete Daftar Buku
    public function destroy(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $buku_id = $request->input('id_buku_delete');

        $cek_peminjaman_status = $this->bukuService->cekPeminjaman($buku_id);

        if ($cek_peminjaman_status) {
            return redirect()->route('showBuku')->with('error', "Buku masih dalam peminjaman dan tidak dapat dihapus");
        } else {
            $deletedByStatus = $this->bukuService->deleteByStatus($buku_id);

            $deleted = $this->bukuService->destroy($buku_id);

            return redirect()->route('showBuku')->with('success', "Hapus Buku");
        }
    }

    // Proses Delete Selected Buku
    public function deleteSelectedBukus(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedBukuIds = $request->input('selected_buku_ids');

        $deletedCount = $this->bukuService->deleteSelectedBukus($selectedBukuIds);

        if ($deletedCount > 0) {
            return redirect()->route('showBuku')->with('success', 'Hapus Buku');
        } elseif ($deletedCount == -1) {
            return redirect()->route('showBuku')->with('error', 'Hapus Buku: Buku masih dalam peminjamn dan tidak dapat dihapus');
        } elseif ($deletedCount == -2) {
            return redirect()->route('showBuku')->with('error', 'Hapus Buku: Buku masih ada dalam riwayat peminjaman dan tidak dapat dihapus');
        } else {
            return redirect()->route('showBuku')->with('error', 'Hapus Buku: Tidak Ada Data Terpilih');
        }
    }

}
