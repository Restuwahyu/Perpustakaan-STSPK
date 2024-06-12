<?php

namespace App\Models;

use App\Models\Buku;
use App\Models\PeminjamanBuku;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eksemplar extends Model
{
    use HasFactory;

    protected $table = 'eksemplar';
    protected $primaryKey = 'eksemplar_id';
    protected $fillable = [
        'buku_id',
        'eksemplar_kode',
        'eksemplar_kode_inventaris',
        'eksemplar_no_panggil',
        'eksemplar_no_eksemplar',
        'eksemplar_tipe_koleksi',
        'eksemplar_status',
        'created_at',
        'updated_at',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'buku_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(PeminjamanBuku::class, 'peminjaman_eksemplar', 'eksemplar_id');
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
