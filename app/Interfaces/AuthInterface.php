<?php

namespace App\Interfaces;

interface AuthInterface
{
    // public function login($credentials); // LOGIN
    public function login($email, $password);
    public function logout(); // LOGOUT
    public function checkEmailUser($email); // CEK EMAIL USER
    public function checkEmailMember($email); // CEK EMAIL MEMBER
}
