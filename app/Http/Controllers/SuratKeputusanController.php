<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUjian;
use App\Models\SchoolSetting;
use App\Models\SuratKeputusan;
use Illuminate\Http\Request;

class SuratKeputusanController extends Controller
{
    public function print(int $id, int $suratKeputusanId)
    {
        $kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
        $suratKeputusan = SuratKeputusan::with('panitia')->findOrFail($suratKeputusanId);
        $schoolSettings = SchoolSetting::getAllSettings();
        $panitiaByJabatan = $suratKeputusan->getPanitiaByJabatan();

        return view('print.surat-keputusan', compact('kegiatanUjian', 'suratKeputusan', 'schoolSettings', 'panitiaByJabatan'));
    }
}
