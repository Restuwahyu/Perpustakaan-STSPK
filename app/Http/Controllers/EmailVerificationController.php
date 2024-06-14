<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MemberService;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function verify($token)
    {
        dd($token);
        if ($token) {
            return redirect()->route('login')->with('error', 'Token verifikasi tidak valid.');
        }

        $member = $this->memberService->findMemberByToken($token);

        if ($member->email_verified_at) {
            return redirect()->route('login')->with('error', 'Email sudah diverifikasi sebelumnya.');
        }

        $member->member_email_verified_at = now();
        $member->member_tanggal_registrasi = Carbon::now();
        $member->member_tanggal_kedaluwarsa = Carbon::now()->addYears(4);
        $member->member_status = 1;
        $member->member_token = null;
        $member->save();

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi. Silakan masuk ke akun Anda.');
    }

    public function resetPassword($token)
    {
        if ($token) {
            return redirect()->route('login')->with('error', 'Token reset password tidak valid.');
        }

        $member = $this->memberService->findMemberByToken($token);

        return view('auth.password_member', compact('member'));
    }
}
