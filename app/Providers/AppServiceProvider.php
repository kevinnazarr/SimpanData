<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\Laporan;
use App\Models\LaporanAkhir;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('pagination::tailwind');

        Password::defaults(function () {
            return Password::min(8);
        });

        View::composer('partials.navbar', function ($view) {
            if (Auth::check() && Auth::user()->role === 'peserta' && Auth::user()->peserta) {
                $pesertaId = Auth::user()->peserta->id;
                
                $harianRevisions = Laporan::where('peserta_id', $pesertaId)
                    ->where('status', 'Revisi')
                    ->latest('tanggal_laporan')
                    ->get();
                    
                $akhirRevision = LaporanAkhir::where('peserta_id', $pesertaId)
                    ->where('status', 'Revisi')
                    ->latest()
                    ->first();
                    
                $view->with('navbarNotifications', [
                    'harian' => $harianRevisions,
                    'akhir' => $akhirRevision,
                    'total_count' => $harianRevisions->count() + ($akhirRevision ? 1 : 0)
                ]);
            }
        });
    }
}
