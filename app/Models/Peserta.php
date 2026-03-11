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
    
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($peserta) {
            if ($peserta->wasChanged('jenis_kegiatan')) {
                $user = $peserta->user;
                if ($user) {
                    $oldId = $user->id;
                    $newId = \App\Helpers\IdGenerator::generate('peserta', $peserta->jenis_kegiatan);
                    
                    \Illuminate\Support\Facades\DB::table('user')
                        ->where('id', $oldId)
                        ->update(['id' => $newId]);
                        
                    \Illuminate\Support\Facades\DB::table('peserta')
                        ->where('id', $oldId)
                        ->update(['id' => $newId]);
                        
                    if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $oldId) {
                        $updatedUser = \App\Models\User::find($newId);
                        if ($updatedUser) {
                            \Illuminate\Support\Facades\Auth::login($updatedUser);
                            
                            request()->session()->put('password_hash_' . \Illuminate\Support\Facades\Auth::getName(), $updatedUser->getAuthPassword());
                        }
                    }
                    
                    $peserta->load('user');
                }
            }
        });
    }

    protected $appends = ['is_lengkap'];

    protected $table = 'peserta';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama',
        'asal_sekolah_universitas',
        'jurusan',
        'alamat',
        'no_telepon',
        'jenis_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'nim_nis',
        'tugas',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
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

    public function kategoriPenilaians()
    {
        return $this->hasMany(KategoriPenilaian::class, 'peserta_id');
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
        return !empty($this->asal_sekolah_universitas) &&
               $this->asal_sekolah_universitas !== '-' &&
               !empty($this->jurusan) &&
               $this->jurusan !== '-' &&
               !empty($this->alamat) &&
               $this->alamat !== '-' &&
               !empty($this->no_telepon) &&
               $this->no_telepon !== '-';
    }

    /**
     * Scope untuk peserta yang aktif (bukan arsip).
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'Arsip');
    }

    /**
     * Sinkronisasi data peserta yang sudah melewati tanggal selesai ke status Arsip.
     */
    public static function syncArchive()
    {
        $expired = self::whereIn('status', ['Aktif', 'Selesai'])
            ->whereDate('tanggal_selesai', '<', \Carbon\Carbon::today())
            ->get();

        if ($expired->isEmpty()) return;

        \Illuminate\Support\Facades\DB::transaction(function () use ($expired) {
            foreach ($expired as $peserta) {
                $peserta->update(['status' => 'Arsip']);
                
                \App\Models\Arsip::firstOrCreate(
                    ['peserta_id' => $peserta->id],
                    ['diarsipkan_pada' => \Carbon\Carbon::today()->toDateString()]
                );
            }
        });
    }
}
