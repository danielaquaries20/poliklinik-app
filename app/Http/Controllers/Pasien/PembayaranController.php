<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();

        // Ambil data tagihan periksa milik pasien yang sedang login
        $tagihans = Periksa::with(['daftarPoli.jadwalPeriksa.dokter.poli', 'pembayaran'])
            ->whereHas('daftarPoli', function ($q) use ($pasienId) {
                $q->where('id_pasien', $pasienId);
            })
            ->latest()
            ->get();

        return view('pasien.pembayaran.index', compact('tagihans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_periksa' => 'required|exists:periksa,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar (JPEG, PNG, JPG).',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $file = $request->file('bukti_pembayaran');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

        Pembayaran::updateOrCreate(
            ['id_periksa' => $request->id_periksa],
            [
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'pending',
                'tgl_pembayaran' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }
}