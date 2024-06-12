<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\Bahasa;
use App\Models\Buku;

class BahasaService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Bahasa::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $bahasa = $this->findById($id);

        $bahasa->bahasa_nama = $data['bahasa_nama'];
        $bahasa->updated_at = now();

        return $bahasa->save();
    }

    // DELETE
    public function destroy($id)
    {
        $bahasa = $this->findById($id);

        return $bahasa->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Bahasa::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return Bahasa::find($id);
    }

    // READ BY NAME
    public function findByName($name)
    {
        return Bahasa::where('bahasa_nama', $name)->first();
    }

    // CHECK BAHASA
    public function checkBahasa($bahasaId)
    {
        return Buku::where('buku_bahasa', $bahasaId)->exists();
    }

    // DELETE SELECTED BAHASA
    public function deleteSelectedBahasas($selectedBahasaIds)
    {
        $deletedCount = 0;
        $userBahasaIdsArray = json_decode($selectedBahasaIds, true);

        foreach ($userBahasaIdsArray as $bahasaId) {
            $cek_bahasa = $this->checkBahasa($bahasaId);
            if ($cek_bahasa == true) {
                return $deletedCount = -1;
            }
            $bahasa = $this->findById($bahasaId);
            $deleted = $this->destroy($bahasa->bahasa_id);
            $deletedCount++;

        }

        return $deletedCount;
    }
}
