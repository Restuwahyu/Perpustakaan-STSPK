<?php

namespace App\Services;

use App\Interfaces\AuthInterface;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthInterface
{
    // LOGIN
    public function login($email, $password)
    {
        $data = [
            'user_email' => $email,
            'password' => $password,
        ];

        return Auth::attempt($data);
    }

    // LOGOUT
    public function logout()
    {
        return Auth::logout();
    }

    // CEK EMAIL USER
    public function checkEmailUser($email)
    {
        return User::where('user_email', $email)->first();
    }

    // CEK EMAIL MEMBER
    public function checkEmailMember($email)
    {
        return Member::where('member_email', $email)->first();
    }
}
