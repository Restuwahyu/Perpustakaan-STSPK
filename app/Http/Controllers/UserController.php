<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    // Tampil Halaman Daftar User
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = session('user');
        $users = $this->userService->findAll('user_nama', 'ASC')->with('role')->get();

        return view('user.show', compact('users'));
    }

    // Tampil Halaman Tambah Daftar User
    public function showStoreForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $roles = $this->roleService->findAll('role_nama', 'ASC')->get();

        return view('user.tambah', compact('roles'));
    }

    protected function validateForm(Request $request, $userId = null)
    {
        $rules = [
            'user_nama' => 'required',
            'user_tanggal_lahir' => 'required',
            'user_role' => 'required',
            'user_email' => [
                'required',
                'email',
                Rule::unique('user', 'user_email')->ignore($userId, 'user_id'),
            ],
        ];

        $messages = [
            'user_nama.required' => 'Nama Harus Diisi',
            'user_tanggal_lahir.required' => 'Tanggal Lahir Harus Diisi',
            'user_email.required' => 'Email Harus Diisi',
            'user_email.email' => 'Format Email Tidak Valid',
            'user_email.unique' => 'Email Sudah Digunakan',
            'user_role.required' => 'Role Harus Dipilih',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    // Proses Tambah Daftar User
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validator = $this->validateForm($request);
        $generate_password = date('dmY', strtotime($request->user_tanggal_lahir));
        $user_nama = $request->user_nama;

        if ($validator->fails()) {
            return redirect()->route('tambahUser')
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'user_nama' => $request->user_nama,
            'user_kode' => $this->userService->generateKodeUser($request->user_role),
            'user_tanggal_lahir' => $request->user_tanggal_lahir,
            'user_email' => $request->user_email,
            'user_role' => $request->user_role,
            'user_password' => Hash::make($generate_password),
        ];

        $simpan = $this->userService->add($data);

        return redirect()->route('showUser')->with('success', "Tambah User");
    }

    // Proses Tambah Daftar Role
    public function storeRole(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validator = $request->validate([
            'role_nama' => 'required',
        ], [
            'role_nama.required' => 'Nama Role Harus Diisi',
        ]);

        $data = [
            'role_nama' => $request->role_nama,
        ];

        $simpan = $this->roleService->add($data);

        if ($simpan) {
            return redirect()->route('tambahUser')->with('success', 'Tambah Role');
        } else {
            return redirect()->route('tambahUser')->with('error', 'Tambah Role Gagal');
        }
    }

    // Tampil Halaman Edit Daftar User
    public function showUpdateForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = $request->input('id_user_edit');
        session(['user_id_edit' => $user_id]);

        $user = $this->userService->findById($user_id);
        $roles = $this->roleService->findAll('role_nama', 'asc')->get();

        return view('user.edit', compact('user', 'roles'));

    }

    // Proses Edit Daftar User
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = session('user_id_edit');
        $user_nama = $request->user_nama;

        $existingUser = $this->userService->getUserByName($user_nama);

        if ($existingUser && $existingUser->user_id != $user_id) {
            return redirect()->route('showUser')->with('error', "Edit User, Gagal: Nama sudah ada.");
        }

        $validator = $this->validateForm($request, $user_id);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $errorMessage = implode(', ', $errorMessages);

            return redirect()->route('showUser')->withErrors($validator)->with('error', "Edit User, Gagal: $errorMessage");
        }

        $data = [
            'user_nama' => $user_nama,
            'user_tanggal_lahir' => $request->user_tanggal_lahir,
            'user_email' => $request->user_email,
            'user_role' => $request->user_role,
        ];

        $updated = $this->userService->update($user_id, $data);

        return redirect()->route('showUser')->with('success', "Edit User");
    }

    // Tampil Halaman Ganti Password Daftar User
    public function showGantiPasswordForm(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = $request->input('id_user_reset');
        $user = $this->userService->findById($user_id);

        return view('user.edit_password', compact('user'));
    }

    // Proses Ganti Password User
    public function gantiPassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = $request->input('id_user_reset');
        $user = $this->userService->findById($user_id);
        $user_nama = $user->user_nama;
        $syarat = $request->input('cek_modal');

        $request->session()->flash('current_password', $request->input('current_password'));
        $request->session()->flash('new_password', $request->input('new_password'));
        $request->session()->flash('password_confirmation', $request->input('password_confirmation'));

        // Cek Password Lama
        if (!Hash::check($request->input('current_password'), $user->user_password)) {
            if ($syarat == 1) {
                return redirect()->route('login')->with('error', 'Password Lama Salah');
            } else {
                return redirect()->route('gantiPassword')->with('error', 'Password Lama Salah');
            }
        } else if ($request->input('new_password') != $request->input('password_confirmation')) {
            if ($syarat == 1) {
                return redirect()->route('login')->with('error', 'Password Baru dan Konfirmasi Berbeda');
            } else {
                return redirect()->route('gantiPassword')->with('error', 'Password Baru dan Konfirmasi Berbeda');
            }
        }

        $user->user_password = Hash::make($request->input('new_password'));
        $user->updated_at = now();

        $simpan = $user->save();
        if ($syarat == 1) {
            return redirect()->route('login')->with('success', "Ganti Password User");
        } else {
            return redirect()->route('home')->with('success', "Ganti Password User");
        }
    }

    // Proses Reset Password User
    public function resetDefaultPasswordUser(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = $request->input('id_user_reset');

        $user = $this->userService->findById($user_id);
        $user_nama = $user->user_nama;

        $birthdate = Carbon::parse($user->user_tanggal_lahir)->format('dmY');

        $new_password = $birthdate;

        $hashed_password = Hash::make($new_password);
        $user->user_password = $hashed_password;
        $simpan = $user->save();

        return redirect()->route('showUser')->with('success', "Reset Password User");
    }

    // Proses Delete Selected User
    public function deleteSelectedUsers(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedUserIds = $request->input('selected_data_ids');

        $deletedCount = $this->userService->deleteSelectedUsers($selectedUserIds);

        if ($deletedCount > 0) {
            return redirect()->route('showUser')->with('success', 'Hapus User');
        } else if ($deletedCount < 0) {
            return redirect()->route('showUser')->with('error', 'Hapus User');
        } else {
            return redirect()->route('showUser')->with('error', 'Hapus User Gagal: Tidak Ada Data Terpilih');
        }
    }
}
