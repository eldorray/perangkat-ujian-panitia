<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['1234567890123456', 'Budi Santoso, S.Pd', 'L', 'Ya'],
            ['1234567890123457', 'Ani Wijaya, S.Pd', 'P', 'Tidak'],
        ];
    }

    public function headings(): array
    {
        return ['nik', 'nama', 'jenis_kelamin', 'panitia'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
