<?php

namespace App\Http\Controllers;

use App\Models\UrusanJenazah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKhidmatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->isSuperAdmin() ? $request->masjid_id : $user->masjid_id;

        // Stats
        $jenazahQuery = UrusanJenazah::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId));

        $stats = [
            ['title' => 'Jumlah Jenazah', 'value' => (clone $jenazahQuery)->count(), 'icon' => 'assignment', 'color' => 'blue'],
            ['title' => 'Tahun Ini', 'value' => (clone $jenazahQuery)->whereYear('tarikh_meninggal', now()->year)->count(), 'icon' => 'calendar_today', 'color' => 'green'],
            ['title' => 'Bulan Ini', 'value' => (clone $jenazahQuery)->whereMonth('tarikh_meninggal', now()->month)->count(), 'icon' => 'calendar_month', 'color' => 'purple'],
            ['title' => 'Jumlah Kos', 'value' => 'RM ' . number_format((clone $jenazahQuery)->sum('kos_pengurusan'), 2), 'icon' => 'payments', 'color' => 'orange'],
        ];

        // Jenazah by month (current year)
        $jenazahByMonth = UrusanJenazah::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->whereYear('tarikh_meninggal', now()->year)
            ->selectRaw('MONTH(tarikh_meninggal) as bulan, count(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Jenazah by jantina
        $jenazahByJantina = UrusanJenazah::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->selectRaw('jantina, count(*) as total')
            ->groupBy('jantina')
            ->get();

        // Filter by date range
        $tarikhMula = $request->tarikh_mula;
        $tarikhAkhir = $request->tarikh_akhir;

        // Jenazah list
        $jenazahList = UrusanJenazah::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->when($tarikhMula, fn($q) => $q->whereDate('tarikh_meninggal', '>=', $tarikhMula))
            ->when($tarikhAkhir, fn($q) => $q->whereDate('tarikh_meninggal', '<=', $tarikhAkhir))
            ->latest('tarikh_meninggal')
            ->take(20)
            ->get();

        return view('laporan-khidmat.index', compact('stats', 'jenazahList'));
    }
}
