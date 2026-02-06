<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        // Find kelas by name or tingkat+nama combination
        $kelas = null;
        if (!empty($row['kelas'])) {
            $kelas = Kelas::where('nama', $row['kelas'])
                ->orWhereRaw("CONCAT(tingkat, ' ', nama) = ?", [$row['kelas']])
                ->first();
        }

        return new Siswa([
            'nisn' => $row['nisn'],
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'jenis_kelamin' => strtoupper(substr($row['jenis_kelamin'] ?? 'L', 0, 1)),
            'kelas_id' => $kelas?->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'nisn' => 'required|string|unique:siswas,nisn',
            'nis' => 'required|string|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string',
            'kelas' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
        ];
    }
}
