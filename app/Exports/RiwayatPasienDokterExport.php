<?php

namespace App\Exports;

use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RiwayatPasienDokterExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        $dokterId = Auth::id();
        return Periksa::with(['daftarPoli.pasien'])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Periksa', 'Nama Pasien', 'Keluhan Awal', 'Catatan Dokter', 'Biaya (Rp)'];
    }

    public function map($periksa): array
    {
        static $no = 1;
        return [
            $no++,
            \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i'),
            $periksa->daftarPoli->pasien->nama ?? '-',
            $periksa->daftarPoli->keluhan ?? '-',
            $periksa->catatan ?? '-',
            number_format($periksa->biaya_periksa, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}