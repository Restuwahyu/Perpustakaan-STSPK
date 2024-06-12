<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\Buku;
use App\Models\Penerbit;

class PenerbitService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Penerbit::create($data);
    }

    // UPDATEa
    public function update($id, array $data)
    {
        $penerbit = $this->findById($id);

        $penerbit->penerbit_nama = $data['penerbit_nama'];
        $penerbit->updated_at = now();

        return $penerbit->save();
    }

    // DELETE
    public function destroy($id)
    {
        $penerbit = $this->findById($id);

        return $penerbit->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Penerbit::orderBy($sortBy, $orderBy);
    }

    // FIND PENERBIT
    public function findPenerbits()
    {
        return Penerbit::whereNotNull('penerbit_id')
            ->orderBy('penerbit_id', 'desc');
    }

    // READ BY ID
    public function findById($id)
    {
        return Penerbit::find($id);
    }

    // READ BY NAME
    public function findByName($name)
    {
        return Penerbit::where('penerbit_nama', $name)->first();
    }

    // CHECK PENERBIT
    public function checkPenerbit($penerbitId)
    {
        return Buku::where('buku_penerbit', $penerbitId)->exists();
    }

    // DELETE SELECTED PENERBIT
    public function deleteSelectedPenerbits($selectedPenerbitIds)
    {
        $deletedCount = 0;
        $penerbit = [];

        $userPenerbitIdsArray = json_decode($selectedPenerbitIds, true);

        foreach ($userPenerbitIdsArray as $penerbitId) {
            $cek_penerbit = $this->checkPenerbit($penerbitId);
            if ($cek_penerbit == true) {
                return $deletedCount = -1;
            }
            $penerbit = $this->findById($penerbitId);
            $deleted = $this->destroy($penerbit->penerbit_id);
            $deletedCount++;
        }

        return $deletedCount;
    }

    public function searchPenerbit($search)
    {
        return Penerbit::where('penerbit_nama', 'like', "%$search%")
            ->orderBy('penerbit_nama', 'asc')
            ->get();
    }
}
