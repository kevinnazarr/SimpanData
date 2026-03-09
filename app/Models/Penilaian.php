<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $peserta_id
 * @property int $user_id
 * @property int $kedisiplinan
 * @property int $keterampilan
 * @property int $kerjasama
 * @property int $inisiatif
 * @property int $komunikasi
 * @property int $nilai_akhir
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Peserta $peserta
 * @property-read \App\Models\User $user
 */
class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'peserta_id',
        'user_id',
        'nilai_akhir',
        'catatan',
    ];

    /**
     * Hitung nilai akhir dari rata-rata semua aspek yang ada
     * Ini bisa dipanggil sebelum save untuk kalkulasi nilai akhir
     */
    public function hitungNilaiAkhirLagi(): void
    {
        if ($this->details()->count() > 0) {
            $this->nilai_akhir = round($this->details()->avg('nilai'));
            $this->save();
        }
    }

    /**
     * Mendapatkan grade berdasarkan nilai
     */
    public function getGradeAttribute(): string
    {
        if ($this->nilai_akhir >= 90) return 'A';
        if ($this->nilai_akhir >= 80) return 'B';
        if ($this->nilai_akhir >= 70) return 'C';
        if ($this->nilai_akhir >= 60) return 'D';
        return 'E';
    }

    /**
     * Relasi ke peserta
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    /**
     * Relasi ke user (admin yang menilai)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke detail penilaian
     */
    public function details()
    {
        return $this->hasMany(PenilaianDetail::class);
    }
}
