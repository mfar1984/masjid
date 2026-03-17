<?php

namespace App\Http\Controllers;

use App\Models\JadualPenyelenggaraan;
use App\Models\KerjaPenyelenggaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPenyelenggaraanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->isSuperAdmin() && $request->filled('masjid_id') 
            ? $request->masjid_id 
            : $user->masjid_id;

        $masjids = $user->isSuperAdmin() ? \App\Models\Masjid::orderBy('nama')->get() : collect();

        // Date range filter
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? null;

        // ========== STATISTIK UMUM ==========
        $totalJadual = JadualPenyelenggaraan::where('masjid_id', $masjidId)->count();
        $jadualAktif = JadualPenyelenggaraan::where('masjid_id', $masjidId)->where('status', 'Aktif')->count();
        
        $totalKerja = KerjaPenyelenggaraan::where('masjid_id', $masjidId)->count();
        $kerjaSelesai = KerjaPenyelenggaraan::where('masjid_id', $masjidId)->where('status', 'Selesai')->count();
        $kerjaDirancang = KerjaPenyelenggaraan::where('masjid_id', $masjidId)->where('status', 'Dirancang')->count();
        
        $jumlahKos = KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->where('status', 'Selesai')
            ->sum('kos');

        // ========== KERJA BY JENIS ==========
        $kerjaByJenis = KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->select('jenis_kerja', DB::raw('count(*) as total'), DB::raw('sum(kos) as jumlah_kos'))
            ->groupBy('jenis_kerja')
            ->get();

        // ========== KERJA BY STATUS ==========
        $kerjaByStatus = KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // ========== KERJA BY BULAN (untuk tahun semasa) ==========
        $kerjaByBulan = KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->whereYear('tarikh_kerja', $tahun)
            ->select(DB::raw('MONTH(tarikh_kerja) as bulan'), DB::raw('count(*) as total'), DB::raw('sum(kos) as jumlah_kos'))
            ->groupBy(DB::raw('MONTH(tarikh_kerja)'))
            ->orderBy('bulan')
            ->get();

        // ========== KOS BY BULAN ==========
        $kosByBulan = KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->whereYear('tarikh_kerja', $tahun)
            ->where('status', 'Selesai')
            ->select(DB::raw('MONTH(tarikh_kerja) as bulan'), DB::raw('sum(kos) as jumlah_kos'))
            ->groupBy(DB::raw('MONTH(tarikh_kerja)'))
            ->orderBy('bulan')
            ->get();

        // ========== ASET/FASILITI PALING KERAP DISELENGGARA ==========
        $itemKerap = KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->with(['senariAset', 'senariFasiliti'])
            ->select('jenis_item', 'senarai_aset_id', 'senarai_fasiliti_id', DB::raw('count(*) as total'), DB::raw('sum(kos) as jumlah_kos'))
            ->groupBy('jenis_item', 'senarai_aset_id', 'senarai_fasiliti_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ========== SENARAI KERJA TERKINI ==========
        $kerjaTerkini = KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->with(['senariAset', 'senariFasiliti'])
            ->orderByDesc('tarikh_kerja')
            ->limit(10)
            ->get();

        // ========== JADUAL AKAN DATANG ==========
        $jadualAkanDatang = JadualPenyelenggaraan::where('masjid_id', $masjidId)
            ->where('status', 'Aktif')
            ->where('tarikh_penyelenggaraan_seterusnya', '>=', now())
            ->with(['senariAset', 'senariFasiliti'])
            ->orderBy('tarikh_penyelenggaraan_seterusnya')
            ->limit(10)
            ->get();

        return view('laporan-penyelenggaraan.index', compact(
            'masjids',
            'masjidId',
            'tahun',
            'bulan',
            'totalJadual',
            'jadualAktif',
            'totalKerja',
            'kerjaSelesai',
            'kerjaDirancang',
            'jumlahKos',
            'kerjaByJenis',
            'kerjaByStatus',
            'kerjaByBulan',
            'kosByBulan',
            'itemKerap',
            'kerjaTerkini',
            'jadualAkanDatang'
        ));
    }
}
