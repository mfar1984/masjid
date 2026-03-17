<?php

namespace App\Http\Controllers;

use App\Models\JadualCeramah;
use App\Models\JadualImamBilal;
use App\Models\SenaraiPenceramah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanTugasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->isSuperAdmin() ? $request->masjid_id : $user->masjid_id;

        // Stats
        $ceramahQuery = JadualCeramah::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId));
        $imamBilalQuery = JadualImamBilal::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId));

        $stats = [
            ['title' => 'Jadual Ceramah', 'value' => (clone $ceramahQuery)->count(), 'icon' => 'record_voice_over', 'color' => 'blue'],
            ['title' => 'Jadual Imam & Bilal', 'value' => (clone $imamBilalQuery)->count(), 'icon' => 'people', 'color' => 'green'],
            ['title' => 'Selesai', 'value' => (clone $imamBilalQuery)->where('status_imam', 'Selesai')->count(), 'icon' => 'check_circle', 'color' => 'purple'],
            ['title' => 'Jumlah Bayaran', 'value' => 'RM ' . number_format((clone $ceramahQuery)->sum('kadar_bayaran'), 2), 'icon' => 'payments', 'color' => 'orange'],
        ];

        // Filter by date range
        $tarikhMula = $request->tarikh_mula;
        $tarikhAkhir = $request->tarikh_akhir;

        // Ceramah list
        $ceramahList = JadualCeramah::with(['penceramah'])
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->when($tarikhMula, fn($q) => $q->whereDate('tarikh', '>=', $tarikhMula))
            ->when($tarikhAkhir, fn($q) => $q->whereDate('tarikh', '<=', $tarikhAkhir))
            ->latest('tarikh')
            ->take(10)
            ->get();

        // Imam & Bilal list (combined)
        $imamBilalList = JadualImamBilal::with(['imamAjk', 'bilalAjk'])
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->when($tarikhMula, fn($q) => $q->whereDate('tarikh', '>=', $tarikhMula))
            ->when($tarikhAkhir, fn($q) => $q->whereDate('tarikh', '<=', $tarikhAkhir))
            ->latest('tarikh')
            ->take(10)
            ->get();

        return view('laporan-tugas.index', compact('stats', 'ceramahList', 'imamBilalList'));
    }
}
