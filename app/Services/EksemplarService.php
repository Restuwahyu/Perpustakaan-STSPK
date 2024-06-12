<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\Eksemplar;

class EksemplarService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Eksemplar::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $eksemplar = $this->findById($id);

        $eksemplar->eksemplar_no_panggil = $data['eksemplar_no_panggil'];
        $eksemplar->eksemplar_no_eksemplar = $data['eksemplar_no_eksemplar'];
        $eksemplar->eksemplar_tipe_koleksi = $data['eksemplar_tipe_koleksi'];
        $eksemplar->eksemplar_status = $data['eksemplar_status'];
        $eksemplar->updated_at = now();
        // dd($eksemplar);
        return $eksemplar->save();
    }

    // DELETE
    public function destroy($id)
    {
        $eksemplar = $this->findById($id);

        return $eksemplar->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Eksemplar::orderBy($sortBy, $orderBy);
    }

    // FIND EKSEMPLAR
    public function findEksemplars()
    {
        return Eksemplar::with(['buku']);
    }

    // READ BY ID
    public function findById($id)
    {
        return Eksemplar::find($id);
    }

    // READ BY KODE EKSEMPLAR
    public function findByKodeEksemplar($kodeEksemplar)
    {
        return Eksemplar::where('eksemplar_kode', $kodeEksemplar)
            ->with('buku')
            ->first();
    }

    // GENERATE KODE EKSEMPLAR
    public function generateKodeEksemplar()
    {
        $timestamp = microtime(true) * 1000;
        $roundedTimestamp = round($timestamp);
        $eksemplar_kode = substr((string) $roundedTimestamp, 0, 13);

        return $eksemplar_kode;
    }

    // UPDATE
    public function updateKodeInventaris($id, $kode, $tahun)
    {
        $eksemplar = $this->findById($id);

        $eksemplar->eksemplar_kode_inventaris = $kode . '/ST/' . $tahun;

        return $eksemplar->save();
    }

    // READ BY BUKU ID
    public function findByBukuId($id)
    {
        return Eksemplar::where('buku_id', $id);
    }
}
