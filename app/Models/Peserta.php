<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LaporanAkhir;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $foto
 * @property string $nama
 * @property string $asal_sekolah_universitas
 * @property string $jurusan
 * @property string|null $alamat
 * @property string|null $no_telepon
 * @property string $jenis_kegiatan
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_selesai
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 */
class Peserta extends Model
{
    use HasFactory;
    
    protected $appends = ['is_lengkap'];

    protected $table = 'peserta';
    protected $fillable = [
        'user_id',
        'foto',
        'nama',
        'asal_sekolah_universitas',
        'jurusan',
        'alamat',
        'no_telepon',
        'jenis_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    public function arsip()
    {
        return $this->hasOne(Arsip::class);
    }

    public function penilaian()
    {
        return $this->hasOne(Penilaian::class);
    }

    public function laporanAkhir()
    {
        return $this->hasOne(LaporanAkhir::class);
    }

    /**
     * Scope untuk peserta dengan profil terisi (tidak ada data '-')
     */
    public function scopeTerisi($query)
    {
        return $query->where('asal_sekolah_universitas', '!=', '-')
                     ->where('jurusan', '!=', '-')
                     ->where('alamat', '!=', '-')
                     ->where('no_telepon', '!=', '-')
                     ->whereNotNull('asal_sekolah_universitas')
                     ->whereNotNull('jurusan')
                     ->whereNotNull('alamat')
                     ->whereNotNull('no_telepon');
    }

    /**
     * Scope untuk peserta dengan profil belum lengkap (ada data '-')
     */
    public function scopeBelumTerisi($query)
    {
        return $query->where(function ($q) {
            $q->where('asal_sekolah_universitas', '-')
              ->orWhere('jurusan', '-')
              ->orWhere('alamat', '-')
              ->orWhere('no_telepon', '-')
              ->orWhereNull('asal_sekolah_universitas')
              ->orWhereNull('jurusan')
              ->orWhereNull('alamat')
              ->orWhereNull('no_telepon');
        });
    }

    /**
     * Accessor untuk cek kelengkapan profil
     */
    public function getIsLengkapAttribute()
    {
        return $this->asal_sekolah_universitas !== '-' &&
               $this->jurusan !== '-' &&
               $this->alamat !== '-' &&
               $this->no_telepon !== '-' &&
               !is_null($this->asal_sekolah_universitas) &&
               !is_null($this->jurusan) &&
               !is_null($this->alamat) &&
               !is_null($this->no_telepon);
    }
}
