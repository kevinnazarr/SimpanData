<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $fillable = [
        'peserta_id',
        'judul',
        'deskripsi',
        'file_path',
        'tanggal_laporan',
        'status',
        'catatan_admin',
    ];
    protected $casts = [
        'tanggal_laporan' => 'date',
    ];
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }
}
