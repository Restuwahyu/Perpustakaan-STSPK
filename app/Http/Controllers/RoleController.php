<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    // Tampil Halaman Daftar Role User
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $roles = $this->roleService->findAll('role_nama', 'ASC')->get();

        return view('user.role.show', compact('roles'));
    }

    // Proses Tambah Daftar Role User
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $data = [
            'role_nama' => $request->role_nama,
        ];

        $role_nama = $request->role_nama;

        $checkNama = $this->roleService->findByNama($role_nama);

        if ($checkNama) {
            return redirect()->route('showUserRole')->with('error', "Role Sudah Ada");
        }

        $simpan = $this->roleService->add($data);

        if ($simpan) {
            return redirect()->route('showUserRole')->with('success', "Tambah Role");
        } else {
            return redirect()->route('showUserRole')->with('error', "Tambah Role");
        }
    }

    // Proses Edit Daftar Role User
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role_id = $request->input('role_id_edit');

        $role_nama = $request->role_nama;

        $data = [
            'role_nama' => $role_nama,
        ];

        $checkNama = $this->roleService->findByNama($role_nama);

        if ($checkNama) {
            return redirect()->route('showUserRole')->with('error', "Role Sudah Ada");
        }

        $updated = $this->roleService->update($role_id, $data);

        return redirect()->route('showUserRole')->with('success', "Edit Role");
    }

    // Proses Delete Selected Role User
    public function deleteSelectedRoles(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $selectedRoleIds = $request->input('selected_role_ids');
        $deletedCount = $this->roleService->deleteSelectedRoles($selectedRoleIds);

        if ($selectedRoleIds == null) {
            return redirect()->route('showUserRole')->with('error', 'Hapus Role User: Tidak Ada Data Terpilih');
        }

        if ($deletedCount > 0) {
            return redirect()->route('showUserRole')->with('success', 'Hapus Role User');
        } else if ($deletedCount < 0) {
            return redirect()->route('showUserRole')->with('error', 'Hapus Role User');
        } else {
            return redirect()->route('showUserRole')->with('error', 'Hapus Role User: Tidak Ada Data Terpilih');
        }
    }
}
