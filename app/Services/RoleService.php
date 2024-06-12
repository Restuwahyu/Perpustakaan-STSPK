<?php

namespace App\Services;

use App\Interfaces\PerpustakaanInterface;
use App\Models\Role;

class RoleService implements PerpustakaanInterface
{
    // CREATE
    public function add(array $data)
    {
        return Role::create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        $user_role = $this->findById($id);

        $user_role->role_nama = $data['role_nama'];
        $user_role->updated_at = now();

        return $user_role->save();
    }

    // DELETE
    public function destroy($id)
    {
        $user_role = $this->findById($id);

        return $user_role->delete();
    }

    // READ ALL
    public function findAll($sortBy, $orderBy)
    {
        return Role::orderBy($sortBy, $orderBy);
    }

    // READ BY ID
    public function findById($id)
    {
        return Role::find($id);
    }

    // READ BY NAMA
    public function findByNama($nama)
    {
        return Role::where('role_nama', $nama)->first();
    }

    // DELETE SELECTED USER ROLES
    public function deleteSelectedRoles($selectedRoleIds)
    {
        $deletedCount = 0;
        $user_role = [];

        $userRoleIdsArray = json_decode($selectedRoleIds, true);

        foreach ($userRoleIdsArray as $userRoleId) {
            $user_role = $this->findById($userRoleId);
            $deleted = $this->destroy($user_role->role_id);
            $deletedCount++;
        }

        return $deletedCount;
    }
}
