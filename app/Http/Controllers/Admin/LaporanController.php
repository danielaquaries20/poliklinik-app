<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LaporanPemeriksaanExport;
use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'pembayaran'
        ])
            ->latest()
            ->get();

        return view('admin.laporan.index', compact('laporans'));
    }

    public function exportExcel()
    {
        $filename = 'laporan-pemeriksaan-poliklinik-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new LaporanPemeriksaanExport, $filename);
    }
}