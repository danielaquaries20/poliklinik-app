<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataDokterExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return User::where('role', 'dokter')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Dokter', 'Email', 'Alamat / No. HP'];
    }

    public function map($dokter): array
    {
        static $no = 1;
        return [
            $no++,
            'Dr. ' . $dokter->nama,
            $dokter->email,
            $dokter->alamat ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}