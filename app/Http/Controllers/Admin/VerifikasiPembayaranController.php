<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with([
            'periksa.daftarPoli.pasien',
            'periksa.daftarPoli.jadwalPeriksa.dokter.poli'
        ])
            ->latest()
            ->get();

        return view('admin.verifikasi-pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => 'lunas',
            'tgl_pembayaran' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi menjadi LUNAS.');
    }
}