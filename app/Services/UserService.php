<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\User;

class UserService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return User::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $user = $this->findById($id);

        $user->user_nama = $data['user_nama'];
        $user->user_tanggal_lahir = $data['user_tanggal_lahir'];
        $user->user_email = $data['user_email'];
        $user->user_role = $data['user_role'];
        $user->updated_at = now();

        return $user->save();
    }

    // DELETE
    public function destroy($id)
    {
        $user = $this->findById($id);

        return $user->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return User::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return User::find($id);
    }

    // GENERATE KODE USER & MEMBER
    public function generateKodeUser($userRole)
    {
        $userTerakhir = User::where('user_role', $userRole)->latest('user_kode')->first();

        $kodeAngka = 1;
        if ($userTerakhir) {
            $kodeAngkaTerakhir = (int) substr($userTerakhir->user_kode, 1);
            $kodeAngka = $kodeAngkaTerakhir + 1;
        }

        if ($kodeAngka > 99999) {
            $kodeAngkaTemp = substr("000" . $kodeAngka, -7);
        } else {
            $kodeAngkaTemp = str_pad($kodeAngka, 5, '0', STR_PAD_LEFT);
        }

        $kode = '';

        if ($userRole == 1) {
            $kode = 'S';
        } elseif ($userRole == 2) {
            $kode = 'A';
        }

        return $kode . $kodeAngkaTemp;
    }

    // DELETE SELECTED USERS
    public function deleteSelectedUsers($selectedUserIds)
    {
        $deletedCount = 0;
        $user = [];

        $userIdsArray = json_decode($selectedUserIds, true);

        foreach ($userIdsArray as $userId) {
            $user = $this->findById($userId);
            $deleted = $this->destroy($user->user_id);
            $deletedCount++;
        }

        return $deletedCount;
    }

    public function convertDateFormat($dateInput)
    {
        $date_parts = explode('/', $dateInput);
        $dateFormatted = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0]; // (YYYY-MM-DD)

        return $dateFormatted;
    }

    public function getUserByName($user_nama)
    {
        return User::where('user_nama', $user_nama)->first();
    }
}
