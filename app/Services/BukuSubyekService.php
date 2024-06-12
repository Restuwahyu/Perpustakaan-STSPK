<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\BukuSubyek;

class BukuSubyekService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return BukuSubyek::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $bukuSubyek = $this->findById($id);

        $bukuSubyek->buku_id = $data['buku_id'];
        $bukuSubyek->subyek_id = $data['subyek_id'];
        $bukuSubyek->updated_at = now();

        return $bukuSubyek->save();
    }

    // DELETE
    public function destroy($id)
    {
        $bukuSubyek = $this->findById($id);

        return $bukuSubyek->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return BukuSubyek::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return BukuSubyek::find($id);
    }

    // READ BY BUKU ID
    public function findByBukuId($id)
    {
        $results = BukuSubyek::where('buku_id', $id)->get();

        $groupedResults = $results->groupBy('buku_id')->map(function ($group) {
            return [
                'buku_subyek_ids' => $group->pluck('buku_subyek_id'),
                'subyeks' => $group->pluck('subyek_id'),
            ];
        });

        return $groupedResults->values();
    }
}
