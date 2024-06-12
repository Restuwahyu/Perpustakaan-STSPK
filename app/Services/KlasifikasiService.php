<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\Buku;
use App\Models\Klasifikasi;

class KlasifikasiService implements PerpustakaanInterface
{

    // CREATE
    public function add(array $data)
    {
        return Klasifikasi::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $klasifikasi = $this->findById($id);

        $klasifikasi->klasifikasi_kode = $data['klasifikasi_kode'];
        $klasifikasi->klasifikasi_nama = $data['klasifikasi_nama'];
        $klasifikasi->updated_at = now();

        return $klasifikasi->save();
    }

    // DELETE
    public function destroy($id)
    {
        $klasifikasi = $this->findById($id);

        return $klasifikasi->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Klasifikasi::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return Klasifikasi::find($id);
    }

    // READ BY NAME
    public function findByName($name)
    {
        return Klasifikasi::where('klasifikasi_nama', $name)->first();
    }

    // READ BY KODE
    public function findByKode($kode)
    {
        return Klasifikasi::where('klasifikasi_kode', $kode)->first();
    }

    // CHECK KLASIFIKASI
    public function checkKlasifikasi($klasifikasiKode)
    {
        return Buku::where('buku_klasifikasi', $klasifikasiKode)->exists();
    }

    // DELETE SELECTED KLASIFIKASI
    public function deleteSelectedKlasifikasis($selectedKlasifikasiIds)
    {
        $deletedCount = 0;
        $userKlasifikasiIdsArray = json_decode($selectedKlasifikasiIds, true);

        foreach ($userKlasifikasiIdsArray as $klasifikasiId) {
            $klasifikasi = $this->findById($klasifikasiId);

            $cek_klasifikasi = $this->checkKlasifikasi($klasifikasi->klasifikasi_kode);
            if ($cek_klasifikasi == true) {
                return $deletedCount = -1;
            }
            $klasifikasi = $this->findById($klasifikasiId);
            $deleted = $this->destroy($klasifikasi->klasifikasi_id);
            $deletedCount++;
        }

        return $deletedCount;
    }
}
