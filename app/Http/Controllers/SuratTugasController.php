<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUjian;
use App\Models\SchoolSetting;
use App\Models\SuratTugas;
use Illuminate\Http\Request;

class SuratTugasController extends Controller
{
    public function print(int $id, int $suratTugasId)
    {
        $kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
        $suratTugas = SuratTugas::with('gurus')->findOrFail($suratTugasId);
        $schoolSettings = SchoolSetting::getAllSettings();

        return view('print.surat-tugas', compact('kegiatanUjian', 'suratTugas', 'schoolSettings'));
    }
}
