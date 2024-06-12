<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $password = 'user_password';
    protected $fillable = [
        'user_nama',
        'user_kode',
        'user_tanggal_lahir',
        'user_email',
        'user_role',
        'user_password',
    ];

    public $timestamps = false;

    // Mengubah Password menjadi user_password
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    // Mengubah Email menjadi user_email
    public function getAuthIdentifierName()
    {
        return 'user_email';
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'user_role', 'role_id');
    }

}
