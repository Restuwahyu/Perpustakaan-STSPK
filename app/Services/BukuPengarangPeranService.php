<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\BukuPengarangPeran;

class BukuPengarangPeranService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return BukuPengarangPeran::create($data);
    }
    // UPDATE
    public function update($id, array $data)
    {
        $bukuPengarangPeran = $this->findById($id);
        // dd($bukuPengarangPeran);
        $bukuPengarangPeran->buku_id = $data['buku_id'];
        $bukuPengarangPeran->pengarang_id = $data['pengarang_id'];
        $bukuPengarangPeran->peran_id = $data['peran_id'];
        $bukuPengarangPeran->updated_at = now();

        // dd($bukuPengarangPeran);
        return $bukuPengarangPeran->save();
    }

    // DELETE
    public function destroy($id)
    {
        $bukuPengarangPeran = $this->findById($id);

        return $bukuPengarangPeran->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return BukuPengarangPeran::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return BukuPengarangPeran::find($id);
    }

    // READ BY BUKU ID
    public function findByBukuId($id)
    {
        $results = BukuPengarangPeran::where('buku_id', $id)
        // ->with('buku', 'pengarang', 'peran')
            ->with(['bukus', 'pengarangs', 'perans'])
            ->get();

        $groupedResults = $results->groupBy('buku_id')->map(function ($group) {
            $mergedData = [];
            foreach ($group as $item) {

                $mergedData['buku_id'] = $item->buku_id;
                $mergedData['pengarangs'][] = [
                    'pengarang_id' => $item->pengarang_id,
                    'peran_id' => $item->peran_id,
                    'buku_pengarang_peran_id' => $item->buku_pengarang_peran_id,
                ];
            }

            return $mergedData;
        });

        return $groupedResults->values();
    }

    // READ BY PENGARANG ID
    public function findByPengarangId($id)
    {
        return BukuPengarangPeran::where('pengarang_id', $id)
            ->with(['bukus', 'bukus.klasifikasi', 'bukus.penerbit', 'bukus.bahasa', 'bukus.pengarangs', 'bukus.pengarangs.peran', 'bukus.perans', 'bukus.eksemplars'])
            ->get();
    }
}
