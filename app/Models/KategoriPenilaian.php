<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPenilaian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_penilaians';

    protected $fillable = [
        'peserta_id',
        'nama',
        'deskripsi',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function penilaianDetails()
    {
        return $this->hasMany(PenilaianDetail::class);
    }
}
