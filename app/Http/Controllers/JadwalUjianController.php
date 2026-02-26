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

        // Merge kelompok_kelas into print page groups (same schedule = 1 table)
        // Kelas I & II -> 1 page 1 table, Kelas III -> 1 page, Kelas IV, V, VI -> 1 page 1 table
        $printGroups = collect();
        $mergeRules = [
            ['keys' => ['Kelas I', 'Kelas II'], 'label' => 'Kelas I & II'],
            ['keys' => ['Kelas III'], 'label' => 'Kelas III'],
            ['keys' => ['Kelas IV', 'Kelas V', 'Kelas VI'], 'label' => 'Kelas IV, V & VI'],
        ];

        $usedKeys = [];
        foreach ($mergeRules as $rule) {
            foreach ($rule['keys'] as $key) {
                if ($jadwalGrouped->has($key)) {
                    // Take jadwal from first matching kelompok (same schedule)
                    $printGroups->put($rule['label'], $jadwalGrouped->get($key));
                    $usedKeys = array_merge($usedKeys, $rule['keys']);
                    break;
                }
            }
        }

        // Add any remaining kelompok that didn't match merge rules
        foreach ($jadwalGrouped as $key => $value) {
            if (!in_array($key, $usedKeys)) {
                $printGroups->put($key, $value);
            }
        }

        return view('print.jadwal-ujian', compact('kegiatanUjian', 'printGroups', 'schoolSettings'));
    }
}
