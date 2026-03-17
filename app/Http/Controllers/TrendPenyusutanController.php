<?php

namespace App\Http\Controllers;

use App\Models\SenariAset;
use App\Models\JadualPenyusutan;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrendPenyusutanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->isSuperAdmin() ? $request->masjid_id : $user->masjid_id;

        // Get jadual penyusutan
        $jadualPenyusutan = JadualPenyusutan::with('kategoriAset')
            ->where('status', 'Aktif')
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->get()
            ->keyBy('kategori_aset_id');

        // Get all aset
        $asetQuery = SenariAset::with(['kategoriAset']);
        if ($masjidId) {
            $asetQuery->where('masjid_id', $masjidId);
        }
        $allAset = $asetQuery->get();

        // Calculate trend data by year (last 5 years)
        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 4, $currentYear);
        $trendData = [];

        foreach ($years as $year) {
            $nilaiAsal = 0;
            $nilaiSemasa = 0;

            foreach ($allAset as $aset) {
                $tarikhPerolehan = $aset->tarikh_perolehan ? Carbon::parse($aset->tarikh_perolehan) : null;
                if (!$tarikhPerolehan || $tarikhPerolehan->year > $year) continue;

                $nilaiAsal += $aset->harga_perolehan ?? 0;
                $kadarSusut = $jadualPenyusutan[$aset->kategori_aset_id]->kadar_susut_tahunan ?? 10;
                $tahunDiguna = $year - $tarikhPerolehan->year;
                $susut = ($aset->harga_perolehan * $kadarSusut / 100) * $tahunDiguna;
                $nilaiSemasa += max(0, $aset->harga_perolehan - $susut);
            }

            $trendData[] = [
                'year' => $year,
                'nilai_asal' => round($nilaiAsal, 2),
                'nilai_semasa' => round($nilaiSemasa, 2),
                'susut_nilai' => round($nilaiAsal - $nilaiSemasa, 2),
            ];
        }

        // Trend by kategori
        $trendByKategori = [];
        $kategoriList = KategoriAset::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->where('status', 'Aktif')
            ->get();

        foreach ($kategoriList as $kategori) {
            $asetKategori = $allAset->where('kategori_aset_id', $kategori->id);
            $nilaiAsal = $asetKategori->sum('harga_perolehan');
            $kadarSusut = $jadualPenyusutan[$kategori->id]->kadar_susut_tahunan ?? 10;
            
            $nilaiSemasa = 0;
            foreach ($asetKategori as $aset) {
                $tarikhPerolehan = $aset->tarikh_perolehan ? Carbon::parse($aset->tarikh_perolehan) : null;
                if (!$tarikhPerolehan) {
                    $nilaiSemasa += $aset->harga_perolehan ?? 0;
                    continue;
                }
                $tahunDiguna = Carbon::now()->year - $tarikhPerolehan->year;
                $susut = ($aset->harga_perolehan * $kadarSusut / 100) * $tahunDiguna;
                $nilaiSemasa += max(0, $aset->harga_perolehan - $susut);
            }

            $trendByKategori[] = [
                'kategori' => $kategori->nama_kategori,
                'jumlah_aset' => $asetKategori->count(),
                'nilai_asal' => round($nilaiAsal, 2),
                'nilai_semasa' => round($nilaiSemasa, 2),
                'susut_nilai' => round($nilaiAsal - $nilaiSemasa, 2),
                'kadar_susut' => $kadarSusut,
            ];
        }

        // Stats
        $totalNilaiAsal = $allAset->sum('harga_perolehan');
        $totalNilaiSemasa = collect($trendByKategori)->sum('nilai_semasa');
        $totalSusutNilai = $totalNilaiAsal - $totalNilaiSemasa;
        $avgKadarSusut = $jadualPenyusutan->avg('kadar_susut_tahunan') ?? 10;

        $stats = [
            ['title' => 'Nilai Asal', 'value' => 'RM ' . number_format($totalNilaiAsal, 2), 'icon' => 'payments', 'color' => 'blue'],
            ['title' => 'Nilai Semasa', 'value' => 'RM ' . number_format($totalNilaiSemasa, 2), 'icon' => 'account_balance', 'color' => 'green'],
            ['title' => 'Susut Nilai', 'value' => 'RM ' . number_format($totalSusutNilai, 2), 'icon' => 'trending_down', 'color' => 'red'],
            ['title' => 'Purata Kadar', 'value' => number_format($avgKadarSusut, 1) . '%', 'icon' => 'percent', 'color' => 'purple'],
        ];

        return view('trend-penyusutan.index', compact('trendData', 'trendByKategori', 'stats', 'years'));
    }
}
