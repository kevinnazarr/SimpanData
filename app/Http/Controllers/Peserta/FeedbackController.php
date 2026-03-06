<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        $feedbacks = collect();

        if ($peserta) {
            Feedback::where('peserta_id', $peserta->id)
                ->where('pengirim', 'Admin')
                ->where('dibaca', false)
                ->update(['dibaca' => true]);

            $feedbacks = Feedback::where('peserta_id', $peserta->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('peserta.feedback', compact('user', 'peserta', 'feedbacks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            return back()->with('error', 'Lengkapi profil terlebih dahulu sebelum mengirim feedback.');
        }

        Feedback::create([
            'peserta_id' => $peserta->id,
            'pengirim' => 'Peserta',
            'pesan' => $request->pesan,
            'rating' => $request->rating,
            'tampilkan' => true,
            'dibaca' => false,
        ]);

        return back()->with('success', 'Feedback berhasil dikirim!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $feedback = Feedback::findOrFail($id);

        $peserta = Auth::user()->peserta;
        if (!$peserta || $feedback->peserta_id !== $peserta->id || $feedback->pengirim !== 'Peserta') {
            return back()->with('error', 'Tindakan tidak diizinkan.');
        }

        $feedback->update([
            'pesan' => $request->pesan,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Feedback berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);

        $peserta = Auth::user()->peserta;
        if (!$peserta || $feedback->peserta_id !== $peserta->id || $feedback->pengirim !== 'Peserta') {
            return back()->with('error', 'Tindakan tidak diizinkan.');
        }

        $feedback->delete();

        return back()->with('success', 'Feedback berhasil dihapus!');
    }
}
