<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Peserta\LaporanController as PesertaLaporanController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Peserta\ProfilController as PesertaProfilController;
use App\Http\Controllers\Peserta\AbsensiController as PesertaAbsensiController;
use App\Http\Controllers\Peserta\PenilaianController as PesertaPenilaianController;
use App\Http\Controllers\Peserta\FeedbackController as PesertaFeedbackController;
use App\Http\Controllers\Peserta\SettingsController as PesertaSettingsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\PenilaianController as AdminPenilaianController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\ArsipController as AdminArsipController;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/auth', fn() => view('auth.auth'))->name('auth');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:30,1')
    ->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:30,1')
    ->name('register');

Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy.policy');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms.of.service');

Route::get('/help', function () {
    return view('legal.help');
})->name('help');

Route::middleware(['throttle:30,1'])->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/check-username', [AuthController::class, 'checkUsername'])->name('check.username');
    Route::post('/check-email-availability', [AuthController::class, 'checkEmailAvailability'])->name('check.email.availability');
});

Route::get('/forgot-password', fn() => view('auth.forgot-password'))
    ->name('forgot.password.form');

Route::middleware(['throttle:30,1'])->group(function () {
    Route::post('/check-email', [AuthController::class, 'checkEmail'])
        ->name('check.email');

    Route::post('/forgot-password', [AuthController::class, 'sendForgotPasswordOtp'])
        ->name('forgot.password.post');

    Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp'])
        ->name('verify.reset.otp.post');

    Route::post('/send-reset-otp', [AuthController::class, 'sendResetOtp'])
        ->name('send.reset.otp');
});

Route::get('/verify-reset-otp', function () {
    if (!session('reset_email')) {
        return redirect()->route('forgot.password.form')
            ->with('error', 'Silakan masukkan email terlebih dahulu');
    }
    return view('auth.verify-reset-otp');
})->name('verify.reset.otp');

Route::get('/reset-password', function () {
    if (!session('reset_verified')) {
        return redirect()->route('forgot.password.form')
            ->with('error', 'Silakan verifikasi OTP terlebih dahulu');
    }
    return view('auth.reset-password');
})->name('reset.password.form');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('reset.password');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::middleware(['auth', 'role:peserta'])->group(function () {
    Route::get('/peserta/dashboard', [PesertaDashboardController::class, 'index'])
        ->name('peserta.dashboard');

    Route::get('/peserta/profil', [PesertaProfilController::class, 'index'])
        ->name('peserta.profil');

    Route::get('/peserta/profil/print', [PesertaProfilController::class, 'printIdCard'])
        ->name('peserta.profil.print');

    Route::post('/peserta/profil', [PesertaProfilController::class, 'update'])
        ->name('peserta.profil.update');

    Route::get('/peserta/absensi', [PesertaAbsensiController::class, 'index'])
        ->name('peserta.absensi');

    Route::post('/peserta/absensi', [PesertaAbsensiController::class, 'store'])
        ->name('peserta.absensi.store');

    Route::get('/peserta/laporan', [PesertaLaporanController::class, 'index'])
        ->name('peserta.laporan.index');

    Route::post('/peserta/laporan', [PesertaLaporanController::class, 'store'])
        ->name('peserta.laporan.store');

    Route::get('/peserta/laporan/laporan-akhir', [PesertaLaporanController::class, 'laporanAkhir'])
        ->name('peserta.laporan.akhir');
    Route::post('/peserta/laporan/laporan-akhir', [PesertaLaporanController::class, 'laporanAkhirStore'])
        ->name('peserta.laporan.akhir.store');
    Route::get('/peserta/laporan/laporan-akhir/{id}', [PesertaLaporanController::class, 'laporanAkhirShow'])
        ->name('peserta.laporan.akhir.show');
    Route::put('/peserta/laporan/laporan-akhir/{id}', [PesertaLaporanController::class, 'laporanAkhirUpdate'])
        ->name('peserta.laporan.akhir.update');

    Route::get('/peserta/laporan/{id}', [PesertaLaporanController::class, 'show'])
        ->name('peserta.laporan.show');

    Route::get('/peserta/laporan/{id}/edit', [PesertaLaporanController::class, 'edit'])
        ->name('peserta.laporan.edit');

    Route::put('/peserta/laporan/{id}', [PesertaLaporanController::class, 'update'])
        ->name('peserta.laporan.update');

    Route::delete('/peserta/laporan/{id}', [PesertaLaporanController::class, 'destroy'])
        ->name('peserta.laporan.destroy');

    Route::get('/peserta/penilaian', [PesertaPenilaianController::class, 'index'])
        ->name('peserta.penilaian');

    Route::get('/peserta/feedback', [PesertaFeedbackController::class, 'index'])
        ->name('peserta.feedback');

    Route::post('/peserta/feedback', [PesertaFeedbackController::class, 'store'])
        ->name('peserta.feedback.store');

    Route::put('/peserta/feedback/{id}', [PesertaFeedbackController::class, 'update'])
        ->name('peserta.feedback.update');

    Route::delete('/peserta/feedback/{id}', [PesertaFeedbackController::class, 'destroy'])
        ->name('peserta.feedback.destroy');

    Route::get('/peserta/settings', [PesertaSettingsController::class, 'index'])->name('peserta.settings.index');
    Route::post('/peserta/settings', [PesertaSettingsController::class, 'update'])->name('peserta.settings.update');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/laporan/laporan-akhir', [AdminLaporanController::class, 'laporanAkhirIndex'])
        ->name('admin.laporan.akhir.index');
    Route::get('/admin/laporan/laporan-akhir/{id}', [AdminLaporanController::class, 'laporanAkhirShow'])
        ->name('admin.laporan.akhir.show');
    Route::patch('/admin/laporan/laporan-akhir/{id}/approve', [AdminLaporanController::class, 'laporanAkhirApprove'])
        ->name('admin.laporan.akhir.approve');
    Route::patch('/admin/laporan/laporan-akhir/{id}/revisi', [AdminLaporanController::class, 'laporanAkhirRevisi'])
        ->name('admin.laporan.akhir.revisi');

    Route::resource('admin/peserta', PesertaController::class)->names([
        'index' => 'admin.peserta.index',
        'create' => 'admin.peserta.create',
        'store' => 'admin.peserta.store',
        'show' => 'admin.peserta.show',
        'edit' => 'admin.peserta.edit',
        'update' => 'admin.peserta.update',
        'destroy' => 'admin.peserta.destroy',
    ]);

    Route::get('/admin/absensi', [AbsensiController::class, 'index'])
        ->name('admin.absensi.index');

    Route::get('/admin/peserta/{id}/print-id-card', [PesertaController::class, 'printIdCard'])
        ->name('admin.peserta.print');

    Route::resource('admin/user', AdminUserController::class)->only(['index', 'show'])->names([
        'index' => 'admin.user.index',
        'show' => 'admin.user.show',
    ]);

    Route::resource('admin/partners', AdminPartnerController::class)->names([
        'index' => 'admin.partners.index',
        'create' => 'admin.partners.create',
        'store' => 'admin.partners.store',
        'edit' => 'admin.partners.edit',
        'update' => 'admin.partners.update',
        'destroy' => 'admin.partners.destroy',
    ]);

    Route::get('/admin/penilaian', [AdminPenilaianController::class, 'index'])->name('admin.penilaian.index');
    Route::get('/admin/penilaian/peserta-grid', [AdminPenilaianController::class, 'getPesertaGrid'])->name('admin.penilaian.peserta-grid');
    Route::get('/admin/penilaian/{id}', [AdminPenilaianController::class, 'show'])->name('admin.penilaian.show');
    Route::post('/admin/penilaian', [AdminPenilaianController::class, 'store'])->name('admin.penilaian.store');
    Route::put('/admin/penilaian/{id}', [AdminPenilaianController::class, 'update'])->name('admin.penilaian.update');

    Route::get('/admin/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/admin/laporan/{id}', [AdminLaporanController::class, 'show'])->name('admin.laporan.harian.show'); // User naming preference
    Route::patch('/admin/laporan/{id}/approve', [AdminLaporanController::class, 'approve'])->name('admin.laporan.harian.approve');
    Route::patch('/admin/laporan/{id}/revisi', [AdminLaporanController::class, 'revisi'])->name('admin.laporan.harian.revisi');


    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/admin/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');

    Route::get('/admin/arsip', [AdminArsipController::class, 'index'])->name('admin.arsip.index');
    Route::post('/admin/feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'store'])->name('admin.feedback.store');
    Route::delete('/admin/feedback/{id}', [\App\Http\Controllers\Admin\FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
    Route::post('/admin/feedback/{id}/mark-as-read', [\App\Http\Controllers\Admin\FeedbackController::class, 'markAsRead'])->name('admin.feedback.mark-as-read');
    Route::post('/admin/arsip/{id}/pulihkan', [AdminArsipController::class, 'pulihkan'])->name('admin.arsip.pulihkan');
    Route::delete('/admin/arsip/{id}', [AdminArsipController::class, 'destroy'])->name('admin.arsip.destroy');
});
