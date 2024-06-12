<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\BukuPengarangPeran;
use App\Models\Peran;

class PeranService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Peran::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $peran = $this->findById($id);

        $peran->peran_nama = $data['peran_nama'];
        $peran->updated_at = now();

        return $peran->save();
    }

    // DELETE
    public function destroy($id)
    {
        $peran = $this->findById($id);

        return $peran->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Peran::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return Peran::find($id);
    }

    // READ BY NAME
    public function findByName($name)
    {
        return Peran::where('peran_nama', $name)->first();
    }

    // CHECK PERAN
    public function checkPeran($peranId)
    {
        return BukuPengarangPeran::where('peran_id', $peranId)->exists();
    }

    // DELETE SELECTED KOTA PERAN
    public function deleteSelectedPerans($selectedPeranIds)
    {
        $deletedCount = 0;
        $peran = [];

        $userPeranIdsArray = json_decode($selectedPeranIds, true);

        foreach ($userPeranIdsArray as $peranId) {
            $cek_peran = $this->checkPeran($peranId);
            if ($cek_peran == true) {
                return $deletedCount = -1;
            }
            $peran = $this->findById($peranId);
            $deleted = $this->destroy($peran->peran_id);
            $deletedCount++;
        }

        return $deletedCount;
    }
}
