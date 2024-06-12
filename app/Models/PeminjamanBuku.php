<?php

namespace App\Models;

use App\Models\Eksemplar;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanBuku extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_buku';
    protected $primaryKey = 'peminjaman_id';
    protected $fillable = [
        'peminjaman_member',
        'peminjaman_eksemplar',
        'peminjaman_user',
        'peminjaman_tgl_pinjam',
        'peminjaman_tgl_kembali',
        'peminjaman_status',
        'created_at',
        'updated_at',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'peminjaman_member', 'member_id');
    }

    public function eksemplar()
    {
        return $this->belongsTo(Eksemplar::class, 'peminjaman_eksemplar', 'eksemplar_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'peminjaman_user', 'user_id');
    }

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
