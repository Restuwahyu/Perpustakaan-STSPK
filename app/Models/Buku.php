<?php

namespace App\Models;

use App\Models\Bahasa;
use App\Models\Buku;
use App\Models\Eksemplar;
use App\Models\Klasifikasi;
use App\Models\Penerbit;
use App\Models\Pengarang;
use App\Models\Peran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'buku_id';
    protected $fillable = [
        'buku_judul',
        'buku_deskripsi_fisik',
        'buku_isbn_issn',
        'buku_edisi',
        'buku_seri',
        'buku_klasifikasi',
        'buku_penerbit',
        'buku_tahun_terbit',
        'buku_kota_terbit',
        'buku_bahasa',
        'buku_cover_original',
        'buku_cover_compressed',
        'created_at',
        'updated_at',
    ];

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'buku_penerbit', 'penerbit_id');
    }
    // klasifikasi_kode
    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'buku_klasifikasi', 'klasifikasi_kode');
    }

    public function bahasa()
    {
        return $this->belongsTo(Bahasa::class, 'buku_bahasa', 'bahasa_id');
    }

    public function eksemplars()
    {
        return $this->hasMany(Eksemplar::class, 'buku_id', 'buku_id');
    }

    public function pengarangs()
    {
        return $this->belongsToMany(Pengarang::class, 'buku_pengarang_peran', 'buku_id', 'pengarang_id')
            ->withPivot(['peran_id'])
            ->withTimestamps()
            ->with('peran');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'buku_pengarang_peran', 'buku_id', 'user_id')->distinct();
    }

    public function perans()
    {
        return $this->belongsToMany(Peran::class, 'buku_pengarang_peran', 'buku_id', 'peran_id');
    }

    public function subyeks()
    {
        return $this->belongsToMany(Subyek::class, 'buku_subyek', 'buku_id', 'subyek_id');
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
