<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $peserta = $user->peserta()->with(['penilaian.user', 'penilaian.details.kategori'])->first();

        return view('peserta.penilaian', compact('user', 'peserta'));
    }
}
