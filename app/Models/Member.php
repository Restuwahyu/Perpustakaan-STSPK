<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory;

    protected $table = 'member';
    protected $primaryKey = 'member_id';
    protected $fillable = [
        'member_nama',
        'member_kode',
        'member_alamat',
        'member_tanggal_lahir',
        'member_email',
        'member_password',
        'member_role',
        'member_notelp',
        'member_tanggal_registrasi',
        'member_tanggal_kedaluwarsa',
        'member_status',
        'member_email_verified_at',
        'member_token',
        'created_at',
        'updated_at',
    ];

    public function getCreatedAtAttribute()
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute()
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
