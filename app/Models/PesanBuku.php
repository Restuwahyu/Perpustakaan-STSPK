<?php

namespace App\Models;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanBuku extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_buku';
    protected $primaryKey = 'pemesanan_buku_id';
    protected $fillable = [
        'pemesanan_buku_member',
        'pemesanan_buku_eksemplar',
        'pemesanan_buku_tanggal_pemesanan',
        'pemesanan_buku_tanggal_pengambilan',
        'pemesanan_buku_status',
        'created_at',
        'updated_at',
    ];

    public function members()
    {
        return $this->belongsTo(Member::class, 'pemesanan_buku_member', 'member_id');
    }

    public function eksemplars()
    {
        return $this->belongsTo(Eksemplar::class, 'pemesanan_buku_eksemplar', 'eksemplar_id');
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
