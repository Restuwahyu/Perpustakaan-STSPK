<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\Kategori;
use App\Models\Pengarang;

class KategoriService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Kategori::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $kategori = $this->findById($id);

        $kategori->kategori_nama = $data['kategori_nama'];
        $kategori->updated_at = now();

        return $kategori->save();
    }

    // DELETE
    public function destroy($id)
    {
        $kategori = $this->findById($id);

        return $kategori->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Kategori::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return Kategori::find($id);
    }

    // READ BY NAME
    public function findByName($name)
    {
        return Kategori::where('kategori_nama', $name)->first();
    }

    // CHECK KATEGORI
    public function checkKategori($kategoriId)
    {
        return Pengarang::where('pengarang_kategori', $kategoriId)->exists();
    }

    // DELETE SELECTED KOTA KATEGORI
    public function deleteSelectedKategoris($selectedKategoriIds)
    {
        $deletedCount = 0;
        $kategori = [];

        $userKategoriIdsArray = json_decode($selectedKategoriIds, true);

        foreach ($userKategoriIdsArray as $kategoriId) {
            $cek_kategori = $this->checkKategori($kategoriId);
            if ($cek_kategori == true) {
                return $deletedCount = -1;
            }
            $kategori = $this->findById($kategoriId);
            $deleted = $this->destroy($kategori->kategori_id);
            $deletedCount++;
        }

        return $deletedCount;
    }
}
