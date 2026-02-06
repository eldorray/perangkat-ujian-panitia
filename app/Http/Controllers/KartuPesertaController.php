<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\SchoolSetting;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KartuPesertaController extends Controller
{
    public function print(Request $request, int $kegiatanUjianId)
    {
        $kegiatanUjian = KegiatanUjian::with(['tahunAjaran', 'jadwalUjians'])->findOrFail($kegiatanUjianId);
        
        $siswaIds = $request->query('siswa_ids');
        
        if (empty($siswaIds)) {
            return back()->with('error', 'Tidak ada siswa yang dipilih.');
        }
        
        $siswaIdsArray = explode(',', $siswaIds);
        $siswas = Siswa::whereIn('id', $siswaIdsArray)->orderBy('nama_lengkap')->get();
        
        if ($siswas->isEmpty()) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }
        
        // Get room placements for all students in this exam
        $penempatans = PenempatanRuangUjian::where('kegiatan_ujian_id', $kegiatanUjianId)
            ->whereIn('siswa_id', $siswaIdsArray)
            ->with('ruangUjian')
            ->get()
            ->keyBy('siswa_id');
        
        $schoolSettings = SchoolSetting::getAllSettings();
        
        return view('print.kartu-peserta', compact('kegiatanUjian', 'siswas', 'schoolSettings', 'penempatans'));
    }
}

