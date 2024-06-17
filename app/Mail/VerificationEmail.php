<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $namaMember;
    public $verificationToken;
    public $judulBuku;
    public $type;

    public function __construct($namaMember, $verificationToken, $judulBuku, $type)
    {
        dd($namaMember, $verificationToken, $judulBuku, $type);
        $this->namaMember = $namaMember;
        $this->verificationToken = $verificationToken;
        $this->judulBuku = $judulBuku;
        $this->type = $type;
    }

    public function build()
    {
        if ($this->type === 'reset_password') {
            return $this->view('member.mail.reset_password_email')
                ->subject('Reset Password');
        } elseif ($this->type === 'register') {
            return $this->view('member.mail.verification_email')
                ->subject('Verifikasi Email');
        } elseif ($this->type === 'reminder') {
            return $this->view('member.mail.verification_email')
                ->subject('Verifikasi Email');
            // return $this->view('member.mail.reminder_email')
            //     ->subject('Pengembalian Buku');
        }
    }
}
