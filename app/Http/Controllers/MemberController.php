<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\VerificationEmail;
use App\Services\BukuService;
use App\Services\MemberService;
use App\Services\PeminjamanBukuService;
use App\Services\PesanBukuService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PDF;

class MemberController extends Controller
{
    protected $memberService;
    protected $peminjamanBukuService;
    protected $pesanBukuService;
    protected $bukuService;

    public function __construct(MemberService $memberService, PeminjamanBukuService $peminjamanBukuService, PesanBukuService $pesanBukuService, BukuService $bukuService)
    {
        $this->memberService = $memberService;
        $this->peminjamanBukuService = $peminjamanBukuService;
        $this->pesanBukuService = $pesanBukuService;
        $this->bukuService = $bukuService;
    }

    // Proses Logout Member
    public function logoutMember()
    {
        session()->forget('member');
        return redirect()->route('showListBuku');
    }

    // Tampil Halaman Daftar Member
    public function indexMember()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $members = $this->memberService->findMembers();
        session(['members' => $members]);
        $members = session('members');

        $this->memberService->deactivateExpiredMembers();

        return view('member.show', compact('members'));
    }

    // Tampil Halaman Dashboar Member
    public function showDashboard(Request $request)
    {
        $member = session('member');
        $riwayatBukus = $this->peminjamanBukuService->findPeminjamanByMemberId($member->member_id, [0, 1, 2]);
        $totalBukuDipinjam = $riwayatBukus->count();
        $pemesanans = $this->pesanBukuService->findPemesanByMemberId($member->member_id)->get();
        $totalBukuDiambil = 0;
        $bukuDipinjam = 0;
        $bukuSelesaiDipinjam = 0;

        foreach ($pemesanans as $pemesanan) {
            $buku = $this->bukuService->findById($pemesanan->eksemplars->buku_id);
            $pemesanan->bukus = $buku;
            if ($pemesanan->pemesanan_buku_status == 1) {
                $this->pesanBukuService->destroy($pemesanan->pemesanan_buku_id);
            }
            if ($pemesanan->pemesanan_buku_status == 2) {
                $totalBukuDiambil += 1;
            }
        }

        foreach ($riwayatBukus as $peminjamanBuku) {
            if ($peminjamanBuku->peminjaman_status == 1) {
                $bukuDipinjam += 1;
            } elseif ($peminjamanBuku->peminjaman_status != 1) {
                $bukuSelesaiDipinjam += 1;
            }
        }
        return view('member.dashboard', compact('member', 'riwayatBukus', 'pemesanans', 'totalBukuDipinjam', 'totalBukuDiambil', 'bukuDipinjam', 'bukuSelesaiDipinjam'));
    }

    // Tampil Halaman Daftar Member Kedaluwarsa
    public function indexMemberNotActive()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $members = $this->memberService->findNotActiveMembers();
        session(['membersNotActive' => $members]);
        $members = session('membersNotActive');

        return view('member.show_exp', compact('members'));
    }

    // Tampil Halaman Tambah Daftar Member
    public function showStoreForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('member.tambah');
    }

    public function showPeminjaman(Request $request)
    {
        $member_id = $request->input('member_id');
        $type = $request->input('type');
        if (Auth::check()) {
            $member_id = $request->input('member_id');
            $member = $this->memberService->findById($member_id);
            $riwayatBukus = $this->peminjamanBukuService->findPeminjamanByMemberId($member_id, [0, 1, 2]);

            return view('member.show_riwayat_buku', compact('member', 'riwayatBukus', 'type'));
        } else if ($type == 'member') {
            $member_id = $request->input('member_id');
            $member = $this->memberService->findById($member_id);
            $riwayatBukus = $this->peminjamanBukuService->findPeminjamanByMemberId($member_id, [0, 1, 2]);

            return view('member.show_riwayat_buku', compact('member', 'riwayatBukus', 'type'));
        } else {
            return redirect()->route('login');
        }
    }

    protected function validateForm(Request $request, $memberId = null)
    {
        $rules = [
            'member_nama' => 'required',
            'member_alamat' => 'required',
            'member_notelp' => 'required|min:10|max:13',
            'member_tanggal_lahir' => 'required',
            'member_password' => 'min:8',
            'confirmation_password' => 'min:8',
            'member_email' => [
                'required',
                'email',
                Rule::unique('member', 'member_email')->ignore($memberId, 'member_id'),
            ],
        ];

        $messages = [
            'member_nama.required' => 'Nama Harus Diisi',
            'member_alamat.required' => 'Alamat Harus Diisi',
            'member_tanggal_lahir.required' => 'Tanggal Lahir Harus Diisi',
            'member_email.required' => 'Email Harus Diisi',
            'member_password.required' => 'Password Harus Diisi',
            'confirmation_password.required' => 'Konfirmasi Password Harus Diisi',
            'member_email.email' => 'Format Email Tidak Valid',
            'member_email.unique' => 'Email Sudah Digunakan',
            'member_notelp.required' => 'Nomor Telepon Harus Diisi',
            'member_notelp.min' => 'Nomor Telepon Minimal 10 Digit',
            'member_notelp.max' => 'Nomor Telepon Maximal 13 Digit',
            'member_password.min' => 'Password Minimal 8 Karakter',
            'confirmation_password.min' => 'Password Minimal 8 Karakter',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    // Proses Tambah Daftar Member
    public function store(Request $request)
    {
        $registerValue = $request->input('register');
        if ($registerValue == null || $registerValue == 1) {
            if ($registerValue == 1) {

                $password = $request->member_password;
                $confirmation_password = $request->confirmation_password;

                // Cek Password
                if ($password != $confirmation_password) {
                    return redirect()->route('register')->with('error', 'Password Tidak Sama')->withInput();
                }

                $generate_password = $password;
                $email_verifed = null;
                $member_tanggal_registrasi = null;
                $member_tanggal_kedaluwarsa = null;
                $member_status = 0;
                $verificationToken = Str::random(60);
            } else {
                $member_tanggal_registrasi = Carbon::now();
                $member_tanggal_kedaluwarsa = Carbon::now()->addYears(4);
                $member_status = 1;
                $generate_password = date('dmY', strtotime($request->member_tanggal_lahir));
                $email_verifed = Carbon::now();
                $verificationToken = null;
            }

            $member_nama = $request->member_nama;
            $validator = $this->validateForm($request);

            if ($registerValue == 1) {
                if ($validator->fails()) {
                    return redirect()->route('register')
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                if ($validator->fails()) {
                    return redirect()->route('tambahMember')
                        ->withErrors($validator)
                        ->withInput();
                }
            }

            $data = [
                'member_nama' => $request->member_nama,
                'member_kode' => $this->memberService->generateKodeMember(),
                'member_alamat' => $request->member_alamat,
                'member_tanggal_lahir' => $request->member_tanggal_lahir,
                'member_email' => $request->member_email,
                'member_password' => Hash::make($generate_password),
                'member_role' => 3,
                'member_notelp' => $request->member_notelp,
                'member_tanggal_registrasi' => $member_tanggal_registrasi,
                'member_tanggal_kedaluwarsa' => $member_tanggal_kedaluwarsa,
                'member_status' => $member_status,
                'member_email_verified_at' => $email_verifed,
                'member_token' => $verificationToken,
            ];

            $simpan = $this->memberService->add($data);

            if ($registerValue == 1) {
                $this->memberService->sendEmail($simpan->member_email, $simpan->member_nama, $verificationToken, '-', 'register');

                return redirect()->route('login')->with('success', "Silakan cek email Anda untuk melakukan verifikasi.");
            } else {
                return redirect()->route('showMember')->with('success', "Tambah Member");
            }
        } else if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    // Tampil Halaman Edit Daftar Member
    public function showUpdateForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member_id = $request->input('id_member_edit');
        $member = $this->memberService->findById($member_id);

        return view('member.edit', compact('member'));
    }

    // Proses Edit Daftar Member
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member_id = $request->input('id_member_edit');
        $member = $this->memberService->findById($member_id);
        $member_nama = $request->member_nama;
        $generate_password = $member->member_password;

        $validator = $this->validateForm($request, $member_id);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $errorMessage = implode(', ', $errorMessages);

            return redirect()->route('showMember')->withErrors($validator)->with('error', "Edit Member, Gagal: $errorMessage");
        }

        $data = [
            'member_nama' => $member_nama,
            'member_alamat' => $request->member_alamat,
            'member_tanggal_lahir' => $request->member_tanggal_lahir,
            'member_email' => $request->member_email,
            'member_password' => Hash::make($generate_password),
            'member_notelp' => $request->member_notelp,
        ];

        $updated = $this->memberService->update($member_id, $data);

        return redirect()->route('showMember')->with('success', "Edit Member");
    }

    // Tampil Halaman Ganti Password Daftar Member
    public function showGantiPasswordMemberForm(Request $request)
    {
        return view('member.edit_password');
    }

    // Tampil Halaman Ganti Password Daftar Member
    public function showForgotPasswordForm(Request $request)
    {
        return view('auth.password_member');
    }

    // Proses Ganti Password Member
    public function gantiPasswordMember(Request $request)
    {
        $member_id = $request->input('id_member_reset');
        $type = $request->input('type');
        $member = $this->memberService->findById($member_id);
        $member_nama = $member->member_nama;
        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');
        $passwordConfirmation = $request->input('password_confirmation');

        $request->session()->put([
            'current_password' => $currentPassword,
            'new_password' => $newPassword,
            'password_confirmation' => $passwordConfirmation,
        ]);

        // Cek Password Lama
        if ($newPassword != $passwordConfirmation && $type == 'ganti_password') {
            return redirect()->back()->with('error', 'Password Baru dan Konfirmasi Password Berbeda');
        } else if (!Hash::check($currentPassword, $member->member_password) && !$type == 'ganti_password') {
            return redirect()->route('showGantiPasswordMember')->with('error', 'Password Lama Salah');
        } else if ($newPassword != $passwordConfirmation && !$type == 'ganti_password') {
            return redirect()->route('showGantiPasswordMember')->with('error', 'Password Baru dan Konfirmasi Berbeda');
        }

        $member->member_password = Hash::make($request->input('new_password'));
        $member->updated_at = now();
        $member->member_token = null;
        $simpan = $member->save();

        if ($simpan) {
            session()->forget(['current_password', 'new_password', 'password_confirmation']);
        }

        if ($type == 'ganti_password') {
            return redirect()->route('login')->with('success', "Ganti Password");
        } else {
            return redirect()->route('showGantiPasswordMember')->with('success', "Ganti Password");
        }
    }

    public function sentEmailRemainder(Request $request)
    {
        $member_id = $request->input('member_id');
        $member = $this->memberService->findById($member_id);
        $peminjaman = $this->peminjamanBukuService->findById($request->peminjaman_id);
        $judulBuku = $peminjaman->eksemplar->buku->buku_judul;
        Mail::to($member->member_email)->send(new VerificationEmail($member->member_nama, '-', $judulBuku, 'reminder'));
        // $this->memberService->sendEmail($member->member_email, $member->member_nama, '-', $judulBuku, 'reminder');
        $peminjaman->peminjaman_email_sent = 1;
        $peminjaman->save();

        return redirect()->route('home')->with('success', 'Email Pengingat Pengembalian Buku.');
    }

    // Proses Ganti Password Forgot Password Member
    public function forgotPasswordMember(Request $request)
    {
        $member_email = $request->input('member_email');
        $member = $this->memberService->findMemberByEmail($member_email);

        if (!$member) {
            return redirect()->route('forgot')->with('error', 'Email tidak ditemukan.');
        }

        $verificationToken = Str::random(60);
        $member->member_token = $verificationToken;
        $member->save();

        $this->memberService->sendEmail($member->member_email, $member->member_nama, $verificationToken, '-', 'reset_password');

        return redirect()->route('login')->with('success', 'Email verifikasi reset password telah dikirim.');
    }

    // Proses Reset Password Member
    public function resetDefaultPasswordMember(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member_id = $request->input('id_member_reset');

        $member = $this->memberService->findById($member_id);
        $member_nama = $member->member_nama;

        $birthdate = Carbon::parse($member->member_tanggal_lahir)->format('dmY');

        $new_password = $birthdate;

        $hashed_password = Hash::make($new_password);
        $member->member_password = $hashed_password;
        $simpan = $member->save();

        return redirect()->route('showMember')->with('success', "Reset Password Member");
    }

    // Proses Update Status Member
    public function renewalMember(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $member_id = $request->input('id_member_status');

        $member = $this->memberService->findById($member_id);
        $member_nama = $member->member_nama;

        if ($member) {
            $member->update(['member_status' => 1]);
            $member->update(['member_tanggal_registrasi' => Carbon::now()]);
            $member->update(['member_tanggal_kedaluwarsa' => Carbon::now()->addYears(4)]);
            return redirect()->route('showMember')->with('success', "Status member telah diperbarui");
        } else {
            return redirect()->route('showMember')->with('error', "Gagal memperbarui status member");
        }
    }

    // Cetak Member Card
    public function printSelectedMembers(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedCetakIds = $request->input('selected_cetak_ids');
        $memberIdsArray = json_decode($selectedCetakIds, true);
        $members = [];

        if (empty($memberIdsArray)) {
            return redirect()->route('showMember')->with('error', 'Cetak Member: Tidak Ada Data Terpilih');
        }

        foreach ($memberIdsArray as $memberId) {

            $member = $this->memberService->findById($memberId);
            $members[] = $member;
        }

        $pdf = PDF::loadView('member.barcode', compact('members'));
        $pdf->setPaper('A4', 'potrait');

        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
        ]);

        return $pdf->download('member.pdf');
    }

    // Proses Delete Selected Member
    public function deleteSelectedMembers(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $syarat = $request->input('cek');
        $selectedDeleteIds = $request->input('selected_delete_ids');
        $deletedCount = $this->memberService->deleteSelectedMembers($selectedDeleteIds);

        if ($syarat == 1) {
            if ($deletedCount > 0) {
                return redirect()->route('showMember')->with('success', 'Hapus Member');
            } else if ($deletedCount == -1) {
                return redirect()->route('showMember')->with('error', 'Hapus Member. Member Masih Mempunyai Peminjaman Buku');
            } else {
                return redirect()->route('showMember')->with('error', 'Hapus Member: Tidak Ada Data Terpilih');
            }
        } else if ($syarat == 2) {
            if ($deletedCount > 0) {
                return redirect()->route('showExpMember')->with('success', 'Hapus Member');
            } else if ($deletedCount == -1) {
                return redirect()->route('showExpMember')->with('error', 'Hapus Member. Member Masih Mempunyai Peminjaman Buku');
            } else {
                return redirect()->route('showExpMember')->with('error', 'Hapus Member: Tidak Ada Data Terpilih');
            }
        }

    }
}
