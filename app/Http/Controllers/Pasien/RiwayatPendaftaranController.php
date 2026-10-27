<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();

        // 1. Menggunakan paginate(10) sebagai pengganti get()
        $riwayats = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksas'
        ])
            ->where('id_pasien', $pasienId)
            ->latest()
            ->paginate(10);

        return view('pasien.riwayat-pendaftaran.index', compact('riwayats'));
    }

    public function show($id)
    {
        $pasienId = Auth::id();

        $daftarPoli = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksas.detailPeriksas.obat'
        ])
            ->where('id_pasien', $pasienId)
            ->findOrFail($id);

        $periksa = $daftarPoli->periksas->first();

        // 2. Menghitung jumlah obat yang diresepkan dari relasi detailPeriksas menggunakan count()
        $jumlahObat = $periksa ? $periksa->detailPeriksas->count() : 0;

        return view('pasien.riwayat-pendaftaran.show', compact('daftarPoli', 'periksa', 'jumlahObat'));
    }
}