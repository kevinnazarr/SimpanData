<?php

namespace App\Console\Commands;

use App\Models\Arsip;
use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AutoArchivePeserta extends Command
{
    protected $signature   = 'peserta:auto-archive';
    protected $description = 'Pindahkan peserta yang sudah melewati tanggal_selesai ke Arsip, lalu hapus arsip yang sudah > 1 bulan.';

    public function handle(): int
    {
        $this->info('[Auto-Archive] Mulai proses...');

        $this->archiveExpired();
        $this->purgeOldArchives();

        $this->info('[Auto-Archive] Selesai.');
        return Command::SUCCESS;
    }

    private function archiveExpired(): void
    {
        $archived = 0;

        Peserta::whereIn('status', ['Aktif', 'Selesai'])
            ->whereDate('tanggal_selesai', '<', Carbon::today())
            ->chunk(50, function ($batch) use (&$archived) {
                foreach ($batch as $peserta) {
                    DB::beginTransaction();
                    try {
                        $peserta->update(['status' => 'Arsip']);

                        Arsip::firstOrCreate(
                            ['peserta_id' => $peserta->id],
                            ['diarsipkan_pada' => Carbon::today()->toDateString()]
                        );

                        DB::commit();
                        $archived++;
                        $this->line("  ✔ Diarsipkan: [{$peserta->id}] {$peserta->nama}");
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        Log::error('[AutoArchive] Gagal arsipkan peserta', [
                            'peserta_id' => $peserta->id,
                            'error'      => $e->getMessage(),
                        ]);
                        $this->warn("  ✘ Gagal arsipkan: [{$peserta->id}] {$peserta->nama} — {$e->getMessage()}");
                    }
                }
            });

        if ($archived === 0) {
            $this->line('  → Tidak ada peserta baru yang perlu diarsipkan.');
        } else {
            $this->info("  → {$archived} peserta dipindahkan ke Arsip.");
        }
    }

    private function purgeOldArchives(): void
    {
        $cutoff  = Carbon::today()->subMonth();
        $deleted = 0;

        Arsip::where('diarsipkan_pada', '<=', $cutoff)
            ->with('peserta.laporans')
            ->chunk(50, function ($batch) use (&$deleted) {
                foreach ($batch as $arsip) {
                    $peserta = $arsip->peserta;
                    if (! $peserta) {
                        $arsip->delete();
                        continue;
                    }

                    DB::beginTransaction();
                    try {
                        foreach ($peserta->laporans as $laporan) {
                            if ($laporan->file_path && Storage::disk('private')->exists($laporan->file_path)) {
                                Storage::disk('private')->delete($laporan->file_path);
                            }
                        }

                        if ($peserta->foto && Storage::disk('public')->exists($peserta->foto)) {
                            Storage::disk('public')->delete($peserta->foto);
                        }
                        $peserta->absensis()->delete();
                        $peserta->laporans()->delete();
                        $peserta->feedbacks()->delete();

                        if ($peserta->penilaian) {
                            $peserta->penilaian->delete();
                        }

                        $peserta->laporanAkhir()->delete();
                        $arsip->delete();

                        $userId      = $peserta->user_id;
                        $namaPeserta = $peserta->nama;

                        $peserta->delete();
                        User::where('id', $userId)->delete();

                        DB::commit();
                        $deleted++;
                        $this->line("  ✔ Terhapus permanen: [{$peserta->id}] {$namaPeserta} (diarsipkan: {$arsip->diarsipkan_pada})");
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        Log::error('[AutoArchive] Gagal hapus arsip', [
                            'peserta_id' => $peserta->id,
                            'error'      => $e->getMessage(),
                        ]);
                        $this->warn("  ✘ Gagal hapus: [{$peserta->id}] {$peserta->nama} — {$e->getMessage()}");
                    }
                }
            });

        if ($deleted === 0) {
            $this->line('  → Tidak ada arsip yang perlu dihapus.');
        } else {
            $this->info("  → {$deleted} peserta dihapus permanen dari database.");
        }
    }
}
