<?php

namespace App\Models;

use App\Models\Buku;
use App\Models\Pengarang;
use App\Models\Peran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuPengarangPeran extends Model
{
    use HasFactory;

    protected $table = 'buku_pengarang_peran';
    protected $primaryKey = 'buku_pengarang_peran_id';
    protected $fillable = [
        'buku_id',
        'pengarang_id',
        'peran_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function bukus()
    {
        return $this->hasMany(Buku::class, 'buku_id', 'buku_id');
    }

    public function pengarangs()
    {
        return $this->hasMany(Pengarang::class, 'pengarang_id', 'pengarang_id');
    }

    public function perans()
    {
        return $this->hasMany(Peran::class, 'peran_id', 'peran_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
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
