<?php

namespace App\Http\Controllers;

use App\Models\SenariAset;
use App\Models\JadualPenyusutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NilaiSemasaAsetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = SenariAset::with(['masjid', 'kategoriAset']);

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('kategori_aset_id')) {
            $query->where('kategori_aset_id', $request->kategori_aset_id);
        }
        if ($request->filled('status_aset')) {
            $query->where('status_aset', $request->status_aset);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_siri', 'like', "%{$search}%")
                    ->orWhere('nama_aset', 'like', "%{$search}%");
            });
        }

        $asetList = $query->latest()->paginate(25);

        // Calculate nilai semasa for each aset
        $masjidId = $user->isSuperAdmin() ? null : $user->masjid_id;
        $jadualPenyusutan = JadualPenyusutan::where('status', 'Aktif')
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->pluck('kadar_susut_tahunan', 'kategori_aset_id')
            ->toArray();

        foreach ($asetList as $aset) {
            $aset->nilai_semasa = $this->calculateNilaiSemasa($aset, $jadualPenyusutan);
            $aset->susut_nilai = $aset->harga_perolehan - $aset->nilai_semasa;
            $aset->peratus_susut = $aset->harga_perolehan > 0 
                ? round(($aset->susut_nilai / $aset->harga_perolehan) * 100, 2) 
                : 0;
        }

        // Stats
        $statsQuery = SenariAset::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalAset = (clone $statsQuery)->count();
        $jumlahNilaiAsal = (clone $statsQuery)->sum('harga_perolehan');
        
        // Calculate total nilai semasa
        $allAset = (clone $statsQuery)->get();
        $jumlahNilaiSemasa = 0;
        foreach ($allAset as $aset) {
            $jumlahNilaiSemasa += $this->calculateNilaiSemasa($aset, $jadualPenyusutan);
        }
        $jumlahSusutNilai = $jumlahNilaiAsal - $jumlahNilaiSemasa;

        $stats = [
            ['title' => 'Jumlah Aset', 'value' => $totalAset, 'icon' => 'inventory_2', 'color' => 'blue'],
            ['title' => 'Nilai Asal', 'value' => 'RM ' . number_format($jumlahNilaiAsal, 2), 'icon' => 'payments', 'color' => 'green'],
            ['title' => 'Nilai Semasa', 'value' => 'RM ' . number_format($jumlahNilaiSemasa, 2), 'icon' => 'account_balance', 'color' => 'purple'],
            ['title' => 'Susut Nilai', 'value' => 'RM ' . number_format($jumlahSusutNilai, 2), 'icon' => 'trending_down', 'color' => 'red'],
        ];

        return view('nilai-semasa-aset.index', compact('asetList', 'stats'));
    }

    private function calculateNilaiSemasa($aset, $jadualPenyusutan)
    {
        $nilaiAsal = $aset->harga_perolehan ?? 0;
        $tarikhPerolehan = $aset->tarikh_perolehan ? Carbon::parse($aset->tarikh_perolehan) : null;
        
        if (!$tarikhPerolehan || $nilaiAsal <= 0) {
            return $nilaiAsal;
        }

        $kadarSusut = $jadualPenyusutan[$aset->kategori_aset_id] ?? 10; // Default 10%
        $tahunDiguna = $tarikhPerolehan->diffInYears(Carbon::now());
        
        // Garis Lurus method
        $susutNilai = ($nilaiAsal * $kadarSusut / 100) * $tahunDiguna;
        $nilaiSemasa = max(0, $nilaiAsal - $susutNilai);
        
        return round($nilaiSemasa, 2);
    }
}
