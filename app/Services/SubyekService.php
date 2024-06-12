<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\BukuSubyek;
use App\Models\Subyek;

class SubyekService implements PerpustakaanInterface
{

    // CREATE
    public function add(array $data)
    {
        return Subyek::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $subyek = $this->findById($id);

        $subyek->subyek_nama = $data['subyek_nama'];
        $subyek->updated_at = now();

        return $subyek->save();
    }

    // DELETE
    public function destroy($id)
    {
        $subyek = $this->findById($id);

        return $subyek->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Subyek::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return Subyek::find($id);
    }

    // READ BY NAME
    public function findByName($name)
    {
        return Subyek::where('subyek_nama', $name)->first();
    }

    // CHECK SUBYEK
    public function checkSubyek($subyekId)
    {
        return BukuSubyek::where('subyek_id', $subyekId)->exists();
    }

    // DELETE SELECTED KOTA SUBYEK
    public function deleteSelectedSubyeks($selectedSubyekIds)
    {
        $deletedCount = 0;
        $subyek = [];

        $userSubyekIdsArray = json_decode($selectedSubyekIds, true);

        foreach ($userSubyekIdsArray as $subyekId) {
            $cek_subyek = $this->checkSubyek($subyekId);
            if ($cek_subyek == true) {
                return $deletedCount = -1;
            }
            $subyek = $this->findById($subyekId);
            $deleted = $this->destroy($subyek->subyek_id);
            $deletedCount++;
        }

        return $deletedCount;
    }
}
