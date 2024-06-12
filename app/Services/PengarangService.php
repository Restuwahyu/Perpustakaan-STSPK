<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\BukuPengarangPeran;
use App\Models\Pengarang;

class PengarangService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Pengarang::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $pengarang = $this->findById($id);

        $pengarang->pengarang_nama = $data['pengarang_nama'];
        $pengarang->pengarang_kategori = $data['pengarang_kategori'];
        $pengarang->updated_at = now();

        return $pengarang->save();
    }

    // DELETE
    public function destroy($id)
    {
        $pengarang = $this->findById($id);

        return $pengarang->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Pengarang::orderBy($sortBy, $orderBy);
    }

    // FIND PENGARANG
    public function findPengarangs()
    {
        return Pengarang::with('kategori')
            ->whereNotNull('pengarang_id')
            ->orderBy('pengarang_id', 'desc');
    }

    // FIND PENGARANG BY ID
    public function findPengarangById($id)
    {
        return Pengarang::with('kategori')
            ->whereNotNull('pengarang_id')
            ->orderBy('created_at', 'desc')
            ->find($id);
    }

    // READ BY ID
    public function findById($id)
    {
        return Pengarang::with('kategori')->find($id);
    }

    // READ BY NAME
    public function findByNameCategory($name, $category)
    {
        return Pengarang::where('pengarang_nama', $name)->where('pengarang_kategori', $category)->first();
    }

    // CHECK PENGARANG
    public function checkPengarang($pengarangId)
    {
        return BukuPengarangPeran::where('pengarang_id', $pengarangId)->exists();
    }

    // DELETE SELECTED PENGARANG
    public function deleteSelectedPengarangs($selectedPengarangIds)
    {
        $deletedCount = 0;
        $pengarang = [];

        $userPengarangIdsArray = json_decode($selectedPengarangIds, true);

        foreach ($userPengarangIdsArray as $pengarangId) {
            $cek_pengarang = $this->checkPengarang($pengarangId);
            if ($cek_pengarang == true) {
                return $deletedCount = -1;
            }
            $pengarang = $this->findById($pengarangId);
            $deleted = $this->destroy($pengarang->pengarang_id);
            $deletedCount++;
        }

        return $deletedCount;
    }

    // DELETE SELECTED PENGARANG
    public function deleteSelectedPeranPengarangs($selectedPeranPengarangIds)
    {
        $deletedCount = 0;
        $pengarang = [];

        $userPengarangIdsArray = json_decode($selectedPeranPengarangIds, true);

        foreach ($userPengarangIdsArray as $pengarangId) {
            $cek_pengarang = $this->checkPengarang($pengarangId);
            dd($cek_pengarang);
            if ($cek_pengarang == true) {
                dd(1);
                return $deletedCount = -1;
            }
            $pengarang = $this->findById($pengarangId);
            $deleted = $this->destroy($pengarang->pengarang_id);
            $deletedCount++;
        }

        return $deletedCount;
    }

    public function searchPengarang($search)
    {
        return Pengarang::with('kategori')
            ->where(function ($query) use ($search) {
                $query->where('pengarang_nama', 'like', "%$search%")
                    ->orWhereHas('kategori', function ($subquery) use ($search) {
                        $subquery->where('kategori_nama', 'like', "%$search%");
                    });
            })
            ->orderBy('pengarang_nama', 'asc')
            ->get();
    }
}
