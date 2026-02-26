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

        // Merge kelompok_kelas into print page groups
        // Kelas I & II -> 1 page, Kelas III -> 1 page, Kelas IV, V, VI -> 1 page
        $printGroups = collect();
        $mergeRules = [
            ['keys' => ['Kelas I', 'Kelas II'], 'label' => 'Kelas I & II'],
            ['keys' => ['Kelas III'], 'label' => 'Kelas III'],
            ['keys' => ['Kelas IV', 'Kelas V', 'Kelas VI'], 'label' => 'Kelas IV, V & VI'],
        ];

        $usedKeys = [];
        foreach ($mergeRules as $rule) {
            $matchedGroups = collect();
            foreach ($rule['keys'] as $key) {
                if ($jadwalGrouped->has($key)) {
                    $matchedGroups->put($key, $jadwalGrouped->get($key));
                    $usedKeys[] = $key;
                }
            }
            if ($matchedGroups->isNotEmpty()) {
                $printGroups->put($rule['label'], $matchedGroups);
            }
        }

        // Add any remaining kelompok that didn't match merge rules
        foreach ($jadwalGrouped as $key => $value) {
            if (!in_array($key, $usedKeys)) {
                $printGroups->put($key, collect([$key => $value]));
            }
        }

        return view('print.jadwal-ujian', compact('kegiatanUjian', 'printGroups', 'schoolSettings'));
    }
}
