<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['1234567890', '12345', 'Ahmad Siswa', 'L', 'X IPA-1'],
            ['1234567891', '12346', 'Siti Siswi', 'P', 'X IPA-1'],
        ];
    }

    public function headings(): array
    {
        return ['nisn', 'nis', 'nama', 'jenis_kelamin', 'kelas'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
