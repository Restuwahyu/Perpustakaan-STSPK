<?php

namespace App\Models;

use App\Models\Kategori;
use App\Models\Peran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengarang extends Model
{
    use HasFactory;

    protected $table = 'pengarang';
    protected $primaryKey = 'pengarang_id';
    protected $fillable = [
        'pengarang_nama',
        'pengarang_kategori',
        'created_at',
        'updated_at',
    ];

    public function peran()
    {
        return $this->belongsToMany(Peran::class, 'buku_pengarang_peran', 'pengarang_id', 'peran_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'pengarang_kategori', 'kategori_id');
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
