<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'pesan' => 'required|string',
        ]);

        Feedback::create([
            'peserta_id' => $request->peserta_id,
            'pesan' => $request->pesan,
            'pengirim' => 'Admin',
            'dibaca' => false,
        ]);

        return response()->json(['message' => 'Feedback berhasil dikirim']);
    }

    public function markAsRead($pesertaId)
    {
        Feedback::where('peserta_id', $pesertaId)
            ->where('pengirim', 'Peserta')
            ->update(['dibaca' => true]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json(['success' => true, 'message' => 'Feedback berhasil dihapus']);
    }
}
