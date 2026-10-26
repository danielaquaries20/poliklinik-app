<?php

namespace App\Exports;

use App\Models\Periksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPemeriksaanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        // Ambil seluruh data pemeriksaan beserta relasi pasien, dokter, poli, dan pembayaran
        return Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'pembayaran'
        ])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Periksa',
            'Nama Pasien',
            'Poliklinik',
            'Dokter Pemeriksa',
            'Keluhan Pasien',
            'Catatan Diagnosa',
            'Total Biaya (Rp)',
            'Status Pembayaran',
        ];
    }

    public function map($periksa): array
    {
        static $no = 1;

        return [
            $no++,
            \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i'),
            $periksa->daftarPoli->pasien->nama ?? '-',
            $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-',
            'Dr. ' . ($periksa->daftarPoli->jadwalPeriksa->dokter->nama ?? '-'),
            $periksa->daftarPoli->keluhan ?? '-',
            $periksa->catatan ?? '-',
            number_format($periksa->biaya_periksa, 0, ',', '.'),
            strtoupper($periksa->pembayaran->status_pembayaran ?? 'BELUM BAYAR'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 (Header Tabel) dicetak tebal
            1 => ['font' => ['bold' => true]],
        ];
    }
}