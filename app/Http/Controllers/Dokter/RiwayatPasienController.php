<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\RiwayatPasienDokterExport;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatPasienController extends Controller
{
    public function index()
    {
        // 1. Menggunakan paginate(10) agar tombol navigasi aktif
        $riwayatPasien = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) {
            $query->where('id_dokter', Auth::id());
        })->with([
                    'daftarPoli.pasien',
                    'daftarPoli.jadwalPeriksa.dokter',
                    'detailPeriksas.obat'
                ])->orderBy('tgl_periksa', 'desc')
            ->paginate(10);

        return view('dokter.riwayat-pasien.index', compact('riwayatPasien'));
    }

    public function show($id)
    {
        $periksa = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) {
            $query->where('id_dokter', Auth::id());
        })->with([
                    'daftarPoli.pasien',
                    'daftarPoli.jadwalPeriksa.dokter.poli',
                    'detailPeriksas.obat'
                ])->findOrFail($id);

        // 2. Menghitung jumlah obat dari relasi detailPeriksas
        $jumlahObat = $periksa->detailPeriksas ? $periksa->detailPeriksas->count() : 0;

        return view('dokter.riwayat-pasien.show', compact('periksa', 'jumlahObat'));
    }

    public function exportExcel()
    {
        return Excel::download(new RiwayatPasienDokterExport, 'riwayat-pasien-diperiksa-' . now()->format('Y-m-d') . '.xlsx');
    }
}