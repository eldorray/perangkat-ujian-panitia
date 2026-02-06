<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUjian;
use App\Models\SchoolSetting;

class JadwalUjianController extends Controller
{
    public function print(int $id)
    {
        $kegiatanUjian = KegiatanUjian::with(['tahunAjaran', 'jadwalUjians'])->findOrFail($id);
        $schoolSettings = SchoolSetting::getAllSettings();

        // Group jadwal by kelompok_kelas, then by date
        $jadwalGrouped = $kegiatanUjian->jadwalUjians
            ->groupBy(fn($item) => $item->kelompok_kelas ?: 'Semua Kelas')
            ->map(fn($group) => $group->groupBy(fn($item) => $item->tanggal->format('Y-m-d')));

        return view('print.jadwal-ujian', compact('kegiatanUjian', 'jadwalGrouped', 'schoolSettings'));
    }
}
