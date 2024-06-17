<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Mail\VerificationEmail;
use App\Models\Member;
use App\Models\PeminjamanBuku;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class MemberService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Member::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $member = $this->findById($id);

        $member->member_nama = $data['member_nama'];
        $member->member_alamat = $data['member_alamat'];
        $member->member_tanggal_lahir = $data['member_tanggal_lahir'];
        $member->member_email = $data['member_email'];
        $member->member_notelp = $data['member_notelp'];
        $member->updated_at = now();

        return $member->save();
    }

    // DELETE
    public function destroy($id)
    {
        $member = $this->findById($id);

        return $member->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Member::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return Member::find($id);
    }

    // FIND MEMBER BY KODE MEMBER
    public function findMemberByKode($memberKode)
    {
        return Member::where('member_kode', $memberKode)->first();
    }

    // FIND MEMBER BY EMAIL
    public function findMemberByEmail($member_email)
    {
        return Member::where('member_email', $member_email)->first();
    }

    // FIND MEMBER BY TOKEN
    public function findMemberByToken($member_token)
    {
        return Member::where('member_token', $member_token)->first();
    }

    // FIND MEMBERS
    public function findMembers()
    {
        return Member::where('member_status', '!=', 0)->get();
    }

    // FIND NOT ACTIVE MEMBERS
    public function findNotActiveMembers()
    {
        return Member::where('member_status', 0)->get();
    }

    // FIND MEMBERS FOR DASHBOARD
    public function findMemberDashboards()
    {
        $inactiveMembers = Member::where('member_status', 0)->get();

        // Mendapatkan member yang akan habis masa aktifnya dalam 1 minggu
        $expirationDate = now()->addWeek();
        $expiringMembers = Member::where('member_status', 1)
            ->whereDate('member_tanggal_kedaluwarsa', '<=', $expirationDate)
            ->get();

        // Menggabungkan hasil dari kedua query
        $membersForDashboard = $inactiveMembers->merge($expiringMembers);

        return $membersForDashboard;
    }

    // DEACTIVE EXPIRED MEMBERS
    public function deactivateExpiredMembers()
    {
        $members = $this->findMembers();
        $today = Carbon::now();

        foreach ($members as $member) {
            $tanggal_kedaluwarsa = Carbon::parse($member->member_tanggal_kedaluwarsa);

            if ($tanggal_kedaluwarsa->isSameDay($today) || $tanggal_kedaluwarsa < $today) {
                $member->update(['member_status' => 0]);
            }
        }
    }

    // CHECK PEMINJAMAN STATUS
    public function checkPeminjamanStatus($memberId)
    {
        return PeminjamanBuku::where('peminjaman_member', $memberId)
            ->whereIn('peminjaman_status', [1, 2])
            ->exists();
    }

    // DELETE ALL PEMINJAMAN STATUS = 0
    public function deleteIfNoPeminjaman($memberId)
    {
        PeminjamanBuku::where('peminjaman_member', $memberId)
            ->where('peminjaman_status', 0)
            ->delete();

        return $this->destroy($memberId);
    }

    // GENERATE KODE MEMBER
    public function generateKodeMember()
    {
        $memberTerakhir = Member::latest('member_kode')->first();

        $kodeAngka = 1;
        if ($memberTerakhir) {
            $kodeAngkaTerakhir = (int) substr($memberTerakhir->member_kode, 1);
            $kodeAngka = $kodeAngkaTerakhir + 1;
        }

        if ($kodeAngka > 99999) {
            $kodeAngkaTemp = substr("000" . $kodeAngka, -7);
        } else {
            $kodeAngkaTemp = str_pad($kodeAngka, 5, '0', STR_PAD_LEFT);
        }

        $kode = 'M';

        return $kode . $kodeAngkaTemp;
    }

    // DELETE SELECTED MEMBERS
    public function deleteSelectedMembers($selectedMemberIds)
    {
        $deletedCount = 0;
        $member = [];

        $memberIdsArray = json_decode($selectedMemberIds, true);

        foreach ($memberIdsArray as $memberId) {

            $member = $this->findById($memberId);
            $cek_peminjaman_status = $this->checkPeminjamanStatus($member->member_id);

            if ($cek_peminjaman_status == true) {
                $deletedCount = -1;
                return $deletedCount;
            } else if ($cek_peminjaman_status == false) {
                $deleted = $this->deleteIfNoPeminjaman($member->member_id);
                $deletedCount++;
            } else {
                $deleted = $this->destroy($member->member_id);
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    public function convertDateFormat($dateInput)
    {
        $date_parts = explode('/', $dateInput);
        $dateFormatted = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0]; //(YYYY-MM-DD)

        return $dateFormatted;
    }

    public function sendEmail($to, $name, $verificationToken, $peminjaman, $type)
    {
        if ($type === 'register') {
            Mail::to($to)->send(new VerificationEmail($name, $verificationToken, $peminjaman, 'register'));
        } elseif ($type === 'reset_password') {
            Mail::to($to)->send(new VerificationEmail($name, $verificationToken, $peminjaman, 'reset_password'));
        } elseif ($type === 'reminder') {
            dd(3);
            Mail::to($to)->send(new VerificationEmail($name, $verificationToken, $peminjaman, 'reminder'));
        }
    }
}
