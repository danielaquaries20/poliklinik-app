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

        // Mengambil seluruh histori pendaftaran pasien dengan Eager Loading (Cegah N+1 Query)
        $riwayats = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksas'
        ])
            ->where('id_pasien', $pasienId)
            ->latest()
            ->get();

        return view('pasien.riwayat-pendaftaran.index', compact('riwayats'));
    }

    public function show($id)
    {
        $pasienId = Auth::id();

        // Mengambil detail pendaftaran spesifik milik pasien yang sedang login
        $daftarPoli = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksas.detailPeriksas.obat'
        ])
            ->where('id_pasien', $pasienId)
            ->findOrFail($id);

        $periksa = $daftarPoli->periksas->first();

        return view('pasien.riwayat-pendaftaran.show', compact('daftarPoli', 'periksa'));
    }
}