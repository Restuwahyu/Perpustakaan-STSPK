<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authService;
    protected $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    // Tampil Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $user = $this->authService->checkEmailUser($request->email);
        $member = $this->authService->checkEmailMember($request->email);
        $login = $this->authService->login($email, $password);
        // dd($user, $member);
        if ($user) {
            $birthdate = Carbon::parse($user->user_tanggal_lahir)->format('dmY');

            if ($login) {
                if ($user->user_role == 2 && $user->created_at == $user->updated_at) {
                    session(['login_id' => $user->user_id]);

                    return redirect()->route('password');
                } else {
                    session(['user' => $user]);

                    return redirect()->route('home')->with('success', "Selamat Datang, $user->user_nama");
                }
            } else {
                return redirect()->route('login')->with('error', 'Email atau Password tidak sesuai.'); // Email atau Password User Salah
            }
        } elseif ($member) {
            if (Hash::check($password, $member->member_password)) {
                if ($member->member_email_verified_at != null) {
                    session(['member' => $member]);
                    return redirect()->route('showListBuku');
                } else {
                    return redirect()->route('login')->with('error', 'Email Anda belum diverifikasi. Silakan verifikasi akun Anda terlebih dahulu.');
                }
            } else {
                return redirect()->route('login')->with('error', 'Email atau Password tidak sesuai.'); // Email atau Password Member Salah
            }
        } else {
            return back()->withInput()->with('error', 'Email tidak ditemukan.'); // Tidak Ada User
        }
    }

    // Tampil Halaman Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Tampil Halaman Forgot Password
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    // Tampil Halaman Ganti Password User
    public function showPasswordForm()
    {
        return view('auth.password');
    }

    // Proses Ganti Password User
    public function gantiPassword(Request $request)
    {
        $user_id = $request->input('id_user_reset');
        $user = $this->userService->findById($user_id);
        $user_nama = $user->user_nama;

        $request->session()->flash('current_password', $request->input('current_password'));
        $request->session()->flash('new_password', $request->input('new_password'));
        $request->session()->flash('password_confirmation', $request->input('password_confirmation'));

        // Cek Password Lama
        if (!Hash::check($request->input('current_password'), $user->user_password)) {
            return redirect()->route('password')->with('error', 'Password Lama Salah');

        } else if ($request->input('new_password') != $request->input('password_confirmation')) {
            return redirect()->route('password')->with('error', 'Password Baru dan Konfirmasi Berbeda');
        }

        $user->user_password = Hash::make($request->input('new_password'));
        $user->updated_at = now();

        $simpan = $user->save();
        return redirect()->route('login')->with('success', "Ganti Password USER: $user_nama");
    }

    // Proses Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
