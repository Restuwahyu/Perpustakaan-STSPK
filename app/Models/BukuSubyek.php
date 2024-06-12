<?php

namespace App\Models;

use App\Models\Buku;
use App\Models\Subyek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuSubyek extends Model
{
    use HasFactory;

    protected $table = 'buku_subyek';
    protected $primaryKey = 'buku_subyek_id';
    protected $fillable = [
        'buku_id',
        'subyek_id',
        'created_at',
        'updated_at',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'buku_id');
    }

    public function subyek()
    {
        return $this->belongsTo(Subyek::class, 'subyek_id', 'subyek_id');
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
