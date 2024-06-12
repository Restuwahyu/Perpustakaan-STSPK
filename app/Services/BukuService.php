<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\Buku;
use App\Models\PeminjamanBuku;

class BukuService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Buku::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $buku = $this->findById($id);
        // dd($data);
        $buku->buku_judul = $data['buku_judul'];
        $buku->buku_deskripsi_fisik = $data['buku_deskripsi_fisik'];
        $buku->buku_isbn_issn = $data['buku_isbn_issn'];
        $buku->buku_edisi = $data['buku_edisi'];
        $buku->buku_seri = $data['buku_seri'];
        $buku->buku_klasifikasi = $data['buku_klasifikasi'];
        $buku->buku_penerbit = $data['buku_penerbit'];
        $buku->buku_tahun_terbit = $data['buku_tahun_terbit'];
        $buku->buku_kota_terbit = $data['buku_kota_terbit'];
        $buku->buku_bahasa = $data['buku_bahasa'];
        $buku->updated_at = now();

        return $buku->save();
    }

    // DELETE
    public function destroy($id)
    {
        $buku = $this->findById($id);
        return $buku->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Buku::orderBy($sortBy, $orderBy)->with('penerbit', 'klasifikasi');
    }

    // FIND BY ID
    public function findById($id)
    {
        return Buku::with('penerbit', 'klasifikasi', 'bahasa', 'eksemplars', 'subyeks', 'pengarangs', 'eksemplars.peminjamans')->find($id);
    }

    // FIND BY JUDUL BUKU
    public function findByJudul($judulBuku)
    {
        return Buku::where('buku_judul', 'like', '%' . $judulBuku . '%')
            ->limit(5)
            ->get();
    }

    // FIND BUKUS
    public function findBukus($searchTerm = null)
    {
        if ($searchTerm) {
            $query = Buku::with(['pengarangs', 'penerbit', 'klasifikasi', 'eksemplars', 'users'])
                ->whereHas('pengarangs', function ($q) use ($searchTerm) {
                    $q->where('pengarang.pengarang_nama', 'like', "%$searchTerm%");
                })
                ->whereHas('users', function ($q) use ($searchTerm) {
                    $q->where('user.user_nama', 'like', "%$searchTerm%");
                })
                ->orWhereHas('eksemplars', function ($q) use ($searchTerm) {
                    $q->where('eksemplar.eksemplar_no_eksemplar', 'like', "%$searchTerm%");
                })
                ->orWhereHas('klasifikasi', function ($q) use ($searchTerm) {
                    $q->where('klasifikasi.klasifikasi_nama', 'like', "%$searchTerm%");
                })
                ->orWhereHas('penerbit', function ($q) use ($searchTerm) {
                    $q->where('penerbit.penerbit_nama', 'like', "%$searchTerm%");
                })
                ->orWhere('buku_judul', 'like', "%$searchTerm%")
                ->orWhere('buku_tahun_terbit', 'like', "%$searchTerm%")
                ->orderBy('updated_at', 'desc');
        } else {
            $query = Buku::with(['pengarangs', 'penerbit', 'klasifikasi', 'eksemplars', 'users']);
        }
        return $query;
    }

    // DELETE SELECTED BUKU
    public function deleteSelectedBukus($selectedBukuIds)
    {
        $deletedCount = 0;
        $buku = [];

        $userBukuIdsArray = json_decode($selectedBukuIds, true);

        foreach ($userBukuIdsArray as $bukuId) {
            $buku = $this->findById($bukuId);
            // dd($buku);
            if ($this->checkPeminjamanStatus($buku) === 'peminjaman') {
                return $deleteCount = -1;
            } elseif ($this->checkPeminjamanStatus($buku) === 'riwayat') {
                return $deleteCount = -2;
            }
            $buku->pengarangs()->detach();

            $deleted = $this->destroy($buku->buku_id);
            $deletedCount++;
        }

        return $deletedCount;
    }

    private function checkPeminjamanStatus($buku)
    {
        $eksemplars = $buku->eksemplars;

        foreach ($eksemplars as $eksemplar) {
            if ($this->checkRiwayat($eksemplar->eksemplar_id) === true) {
                return 'riwayat';
            }
            foreach ($eksemplar->peminjamans as $peminjaman) {
                if ($peminjaman->peminjaman_status != 0) {
                    return 'peminjaman';
                }
            }
        }

        return false;
    }

    private function checkRiwayat($eksemplarId)
    {
        return PeminjamanBuku::where('peminjaman_eksemplar', $eksemplarId)->exists();
    }

    public function isKlasifikasiUsedInBuku($klasifikasiKode)
    {
        return Buku::where('buku_klasifikasi', $klasifikasiKode)->exists();
    }

    public function isSubyekUsedInBuku($subyekId)
    {
        return Buku::where('buku_subyek', $subyekId)->exists();
    }

    public function isBahasaUsedInBuku($bahasaId)
    {
        return Buku::where('buku_bahasa', $bahasaId)->exists();
    }
}
