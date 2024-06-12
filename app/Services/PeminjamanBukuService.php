<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\PeminjamanBuku;

class PeminjamanBukuService implements PerpustakaanInterface
{

    // CREATE
    public function add(array $data)
    {
        return PeminjamanBuku::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $peminjaman = $this->findById($id);

        $eksemplar = $peminjaman->eksemplar;
        $eksemplar->eksemplar_status = 1;
        $eksemplar->updated_at = now();
        $eksemplar->save();

        $peminjaman->peminjaman_status = 0;
        $peminjaman->peminjaman_email_sent = 0;
        $peminjaman->peminjaman_tgl_kembali = now();
        $peminjaman->updated_at = now();

        return $peminjaman->save();
    }

    // DELETE
    public function destroy($id)
    {
        $peminjaman = $this->findById($id);
        return $peminjaman->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return PeminjamanBuku::orderBy($sortBy, $orderBy)
            ->with('member', 'eksemplar', 'eksemplar.buku', 'eksemplar.buku.pengarangs', 'eksemplar.buku.perans', 'eksemplar.buku.klasifikasi')
            ->get();
    }

    // READ BY ID
    public function findById($id)
    {
        return PeminjamanBuku::with('member', 'eksemplar')->find($id);
    }

    // READ BY BUKU ID
    public function findByBukuId($bukuId)
    {
        return PeminjamanBuku::where('peminjaman_eksemplar', $bukuId)
            ->where('peminjaman_status', 0)->get();
    }

    // READ BY EKSEMPLAR ID
    public function findByEksemplarId($eksemplarId)
    {
        return PeminjamanBuku::where('peminjaman_eksemplar', $eksemplarId)
            ->where('peminjaman_status', 1)
            ->first();
    }

    // CEK PEMINJAMAN STATUS
    public function cekPeminjaman($id)
    {
        $buku_id = $this->findById($id);

        return PeminjamanBuku::where('peminjaman_buku', $buku_id)
            ->whereIn('peminjaman_status', [1, 2])
            ->exists();
    }

    // DELETE PEMINJAMAN BUKU STATUS 0
    public function deleteByStatus($id)
    {
        $buku_id = $this->findById($id);

        return PeminjamanBuku::where('peminjaman_buku', $buku_id)
            ->where('peminjaman_status', 0)
            ->delete();

    }

    // MENCARI PEMINJAMAN BUKU BY ID MEMBER
    public function findPeminjamanByMemberId($member_id, $statusFilter = null)
    {
        $query = PeminjamanBuku::with(['member', 'eksemplar', 'eksemplar.buku', 'eksemplar.buku.klasifikasi', 'eksemplar.buku.bahasa', 'eksemplar.buku.penerbit', 'eksemplar.buku.pengarangs'])
            ->where('peminjaman_member', $member_id);

        if (!is_null($statusFilter)) {
            $query->whereIn('peminjaman_status', $statusFilter);
        }

        $peminjamans = $query->get();

        return $peminjamans;
    }
}
