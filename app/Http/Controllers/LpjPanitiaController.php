<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUjian;
use App\Models\LpjPanitia;
use App\Models\SchoolSetting;
use App\Models\SuratKeputusan;
use Illuminate\Http\Request;

class LpjPanitiaController extends Controller
{
    public function print(int $id, int $lpjId)
    {
        $kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
        $lpj = LpjPanitia::findOrFail($lpjId);
        $schoolSettings = SchoolSetting::getAllSettings();

        // Get panitia data from SK if available
        $panitiaByJabatan = [];
        $sk = SuratKeputusan::where('kegiatan_ujian_id', $id)->first();
        if ($sk) {
            $sk->load('panitia');
            $panitiaByJabatan = $sk->getPanitiaByJabatan();
        }

        return view('print.lpj-panitia', compact('kegiatanUjian', 'lpj', 'schoolSettings', 'panitiaByJabatan'));
    }
}
