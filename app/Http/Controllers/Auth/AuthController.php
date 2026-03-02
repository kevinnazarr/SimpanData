<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\User;
use App\Models\OtpCode;
use App\Mail\OtpMail;
use App\Mail\ResetPasswordOtpMail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $remember = $request->has('remember');

        if (Auth::attempt([
            $fieldType => $request->login,
            'password' => $request->password,
        ], $remember)) {

            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect('/admin/dashboard')
                : redirect('/peserta/dashboard');
        }

        return back()->withErrors([
            'login' => 'Email / Username atau password salah',
        ])->withInput();
    }

    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Format email tidak valid',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->role === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak terdaftar',
            ], 404);
        }

        session()->forget('reset_verified');
        session(['reset_email' => $user->email]);

        return response()->json([
            'status' => true,
            'message' => 'Email terverifikasi',
        ]);
    }


    public function sendForgotPasswordOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,email'
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = $request->email;

        $user = User::where('email', $email)->first();
        if ($user && $user->role === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Reset password admin hanya bisa dilakukan oleh admin',
            ], 403);
        }

        $email = $request->email;

        session()->forget('reset_verified');
        session(['reset_email' => $email]);

        $otp = random_int(100000, 999999);

        OtpCode::updateOrCreate(
            ['email' => $email],
            [
                'code' => $otp,
                'expired_at' => Carbon::now()->addMinutes(5),
            ]
        );

        try {
            Mail::to($email)->send(new ResetPasswordOtpMail($otp, $email));

            Log::info('Reset password OTP sent to: ' . $email);

            return response()->json([
                'status' => true,
                'message' => 'Kode OTP berhasil dikirim ke email',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send reset password OTP email to ' . $email . ': ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengirim email OTP. Silakan coba lagi.',
            ], 500);
        }
    }

    public function checkUsername(Request $request)
    {
        $exists = User::where('username', $request->username)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username sudah digunakan' : 'Username tersedia'
        ]);
    }

    public function checkEmailAvailability(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email sudah terdaftar' : 'Email tersedia'
        ]);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:user,email',
            'username' => 'required|string|min:3|unique:user,username'
        ], [
            'email.unique' => 'Email sudah terdaftar dalam sistem',
            'username.unique' => 'Username sudah digunakan',
            'username.min' => 'Username minimal 3 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $otp = random_int(100000, 999999);

        OtpCode::updateOrCreate(
            ['email' => $request->email],
            [
                'code' => $otp,
                'expired_at' => Carbon::now()->addMinutes(5),
            ]
        );

        try {
            Mail::to($request->email)->send(new OtpMail($otp));

            Log::info('Registration OTP sent to: ' . $request->email);

            return response()->json([
                'status' => true,
                'message' => 'Kode OTP berhasil dikirim ke email',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send registration OTP email to ' . $request->email . ': ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengirim email OTP. Silakan coba lagi.',
            ], 500);
        }
    }

    public function sendResetOtp(Request $request)
    {
        $email = session('reset_email');

        if (!$email) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi email tidak ditemukan. Silakan ulangi proses.',
            ], 422);
        }

        $otp = random_int(100000, 999999);

        OtpCode::updateOrCreate(
            ['email' => $email],
            [
                'code' => $otp,
                'expired_at' => Carbon::now()->addMinutes(5),
            ]
        );

        try {
            Mail::to($email)->send(new ResetPasswordOtpMail($otp, $email));

            Log::info('Reset password OTP resent to: ' . $email);

            return response()->json([
                'status' => true,
                'message' => 'Kode OTP berhasil dikirim ulang ke email',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resend reset password OTP email to ' . $email . ': ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengirim ulang email OTP. Silakan coba lagi.',
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $otp = OtpCode::where('email', $request->email)
            ->where('code', $request->otp)
            ->first();

        if (!$otp || $otp->isExpired()) {
            return response()->json([
                'status' => false,
                'message' => 'OTP tidak valid atau sudah kedaluwarsa',
            ], 422);
        }

        $otp->delete();

        session(['otp_verified_email' => $request->email]);

        return response()->json([
            'status' => true,
            'message' => 'OTP valid',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:user,username',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if (session('otp_verified_email') !== $request->email) {
            return back()->withErrors([
                'otp' => 'Verifikasi OTP diperlukan. Silakan ulangi proses.',
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'peserta',
            ]);

            \App\Models\Peserta::create([
                'user_id' => $user->id,
                'nama' => $request->username,
                'asal_sekolah_universitas' => '-',
                'jurusan' => '-',
                'jenis_kegiatan' => 'Magang',
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(3),
                'status' => 'Aktif',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
            ])->withInput();
        }

        session()->forget('otp_verified_email');
        OtpCode::where('email', $request->email)->delete();

        Log::info('New user registered: ' . $request->email);

        return redirect('/auth')->with(
            'success',
            'Registrasi berhasil, silakan login dan lengkapi profil Anda'
        );
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('forgot.password.form')
                ->with('error', 'Sesi habis, silakan ulangi proses');
        }

        $otp = OtpCode::where('email', $email)
            ->where('code', $request->otp)
            ->first();

        if (!$otp || $otp->isExpired()) {
            return back()->withErrors([
                'otp' => 'OTP tidak valid atau sudah kedaluwarsa',
            ]);
        }

        $otp->delete();

        session([
            'reset_verified' => true,
        ]);

        Log::info('Reset password OTP verified for: ' . $email);

        return redirect()->route('reset.password.form');
    }

    public function resetPassword(Request $request)
    {
        if (!session('reset_verified') || !session('reset_email')) {
            return redirect()->route('forgot.password.form')
                ->with('error', 'Sesi tidak valid, silakan ulangi proses');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('reset_email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('forgot.password.form')
                ->with('error', 'Akun tidak ditemukan');
        }

        if ($user->role === 'admin') {
            session()->forget(['reset_verified', 'reset_email']);

            return redirect()->route('auth')
                ->with('error', 'Akun admin tidak dapat diubah melalui fitur ini');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget([
            'reset_verified',
            'reset_email',
        ]);

        OtpCode::where('email', $email)->delete();

        Log::info('Password reset successful for: ' . $email);

        return redirect('/auth')
            ->with('success', 'Password berhasil direset, silakan login');
    }


    public function logout(Request $request)
    {
        $userEmail = Auth::user() ? Auth::user()->email : 'Unknown';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out: ' . $userEmail);

        return redirect('/auth');
    }
}
