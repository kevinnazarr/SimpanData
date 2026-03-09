<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDetail extends Model
{
    use HasFactory;

    protected $table = 'penilaian_details';

    protected $fillable = [
        'penilaian_id',
        'kategori_penilaian_id',
        'nilai',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPenilaian::class, 'kategori_penilaian_id')->withTrashed();
    }
}
