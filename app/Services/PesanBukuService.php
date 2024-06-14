<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\PesanBuku;

class PesanBukuService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return PesanBuku::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {

    }

    // DELETE
    public function destroy($id)
    {
        $pesan_buku = $this->findById($id);

        return $pesan_buku->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return PesanBuku::orderBy($sortBy, $orderBy)->with('members', 'eksemplars');
    }

    // READ BY ID
    public function findById($id)
    {
        return PesanBuku::find($id);
    }

    // MENCARI PEMESANAN BUKU BY ID MEMBER
    public function findPemesanByMemberId($member_id)
    {
        $query = PesanBuku::with(['members', 'eksemplars'])->where('pemesanan_buku_member', $member_id);

        return $query;
    }
}
