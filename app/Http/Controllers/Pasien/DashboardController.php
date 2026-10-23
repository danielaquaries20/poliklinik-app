<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();

        $antrianAktif = DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', $pasienId)
            ->whereIn('status', ['menunggu', 'diperiksa'])
            ->latest()
            ->first();

        $sedangDilayani = null;

        if ($antrianAktif) {
            $pasienSedangDiperiksa = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->where('status', 'diperiksa')
                ->first();

            $sedangDilayani = $pasienSedangDiperiksa ? $pasienSedangDiperiksa->no_antrian : 0;
        }
        $jadwals = JadwalPeriksa::with(['dokter.poli', 'daftarPolis'])->get()->map(function ($jadwal) {
            $sedangDiperiksa = $jadwal->daftarPolis->where('status', 'diperiksa')->first();
            $jadwal->no_sedang_dilayani = $sedangDiperiksa ? $sedangDiperiksa->no_antrian : 0;
            return $jadwal;
        });

        return view('pasien.dashboard', compact('antrianAktif', 'sedangDilayani', 'jadwals'));
    }
}
