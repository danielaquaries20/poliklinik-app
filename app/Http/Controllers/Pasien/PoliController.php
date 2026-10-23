<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\AntrianUpdated;

class PoliController extends Controller
{
    public function get()
    {
        $user = Auth::user();
        $polis = Poli::all();
        $jadwal = JadwalPeriksa::with('dokter', 'dokter.poli')->get();

        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwal,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);

        $pasienId = Auth::id();

        // Validasi: Cek apakah pasien masih memiliki antrian aktif (belum selesai)
        $antrianAktif = DaftarPoli::where('id_pasien', $pasienId)
            ->whereIn('status', ['menunggu', 'diperiksa'])
            ->exists();

        if ($antrianAktif) {
            return redirect()->back()->with('error', 'Anda masih memiliki antrian aktif. Selesaikan pemeriksaan terlebih dahulu sebelum mendaftar kembali.');
        }

        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();
        $noAntrianBaru = $jumlahSudahDaftar + 1;

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasienId,
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $noAntrianBaru,
            'status' => 'menunggu',
        ]);

        return redirect()->route('pasien.dashboard')->with('success', 'Berhasil Mendaftar ke Poli');
    }
}