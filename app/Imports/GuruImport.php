<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        // Handle is_panitia - accept various formats
        $isPanitia = false;
        if (!empty($row['panitia'])) {
            $val = strtolower(trim($row['panitia']));
            $isPanitia = in_array($val, ['ya', 'yes', '1', 'true', 'y']);
        }

        return new Guru([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'jenis_kelamin' => strtoupper(substr($row['jenis_kelamin'] ?? 'L', 0, 1)),
            'is_panitia' => $isPanitia,
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|string|unique:gurus,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string',
            'panitia' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
        ];
    }
}
