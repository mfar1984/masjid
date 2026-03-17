<?php

namespace App\Http\Controllers;

use App\Models\TempahanFasiliti;
use App\Models\PembayaranSewa;
use App\Models\SenariFasiliti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanTempahanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Apply filters
        $query = TempahanFasiliti::with(['senariFasiliti']);
        
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $masjidId);
        }

        if ($request->filled('senarai_fasiliti_id')) {
            $query->where('senarai_fasiliti_id', $request->senarai_fasiliti_id);
        }

        if ($request->filled('status_tempahan')) {
            $query->where('status_tempahan', $request->status_tempahan);
        }

        if ($request->filled('tarikh_dari')) {
            $query->whereDate('tarikh_mula', '>=', $request->tarikh_dari);
        }

        if ($request->filled('tarikh_hingga')) {
            $query->whereDate('tarikh_tamat', '<=', $request->tarikh_hingga);
        }

        $tempahanList = $query->latest()->paginate(25);

        // Stats
        $statsQuery = TempahanFasiliti::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $masjidId);
        }

        $totalFasiliti = SenariFasiliti::where('masjid_id', $masjidId)->count();
        $totalTempahan = (clone $statsQuery)->count();
        $totalPembayaran = PembayaranSewa::where('masjid_id', $masjidId)->count();
        $jumlahPendapatan = PembayaranSewa::where('masjid_id', $masjidId)->sudahBayar()->sum('jumlah_bayaran');
        $tempahanLulus = (clone $statsQuery)->where('status_tempahan', 'Lulus')->count();
        $tempahanDitolak = (clone $statsQuery)->where('status_tempahan', 'Ditolak')->count();
        $tempahanSelesai = (clone $statsQuery)->where('status_tempahan', 'Selesai')->count();
        $kadarKelulusan = $totalTempahan > 0 ? round(($tempahanLulus / $totalTempahan) * 100, 2) : 0;

        $stats = [
            'total_fasiliti' => $totalFasiliti,
            'total_tempahan' => $totalTempahan,
            'total_pembayaran' => $totalPembayaran,
            'jumlah_pendapatan' => $jumlahPendapatan,
            'tempahan_lulus' => $tempahanLulus,
            'tempahan_ditolak' => $tempahanDitolak,
            'tempahan_selesai' => $tempahanSelesai,
            'kadar_kelulusan' => $kadarKelulusan,
        ];

        // Chart data
        $statusData = [
            'Baharu' => (clone $statsQuery)->where('status_tempahan', 'Baharu')->count(),
            'Dalam Semakan' => (clone $statsQuery)->where('status_tempahan', 'Dalam Semakan')->count(),
            'Lulus' => $tempahanLulus,
            'Ditolak' => $tempahanDitolak,
            'Dibatalkan' => (clone $statsQuery)->where('status_tempahan', 'Dibatalkan')->count(),
            'Selesai' => $tempahanSelesai,
        ];

        // Payment method data for chart
        $kaedahData = PembayaranSewa::where('masjid_id', $masjidId)
            ->select('kaedah_bayaran', \DB::raw('count(*) as total'))
            ->groupBy('kaedah_bayaran')
            ->pluck('total', 'kaedah_bayaran')
            ->toArray();

        // Top facilities data for chart
        $fasilitiData = TempahanFasiliti::where('masjid_id', $masjidId)
            ->select('senarai_fasiliti_id', \DB::raw('count(*) as total'))
            ->groupBy('senarai_fasiliti_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->mapWithKeys(function ($item) {
                $fasiliti = SenariFasiliti::find($item->senarai_fasiliti_id);
                return [$fasiliti->nama_fasiliti ?? 'Unknown' => $item->total];
            })
            ->toArray();

        // Trend data for chart (last 6 months)
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('M Y');
            $count = TempahanFasiliti::where('masjid_id', $masjidId)
                ->whereYear('tarikh_tempahan', $month->year)
                ->whereMonth('tarikh_tempahan', $month->month)
                ->count();
            $trendData[$monthKey] = $count;
        }

        // Pendapatan data for chart (last 6 months)
        $pendapatanData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('M Y');
            $jumlah = PembayaranSewa::where('masjid_id', $masjidId)
                ->where('status_pembayaran', 'Sudah Bayar')
                ->whereYear('tarikh_pembayaran', $month->year)
                ->whereMonth('tarikh_pembayaran', $month->month)
                ->sum('jumlah_bayaran');
            $pendapatanData[$monthKey] = $jumlah;
        }

        $chartData = [
            'status_labels' => array_keys($statusData),
            'status_values' => array_values($statusData),
            'kaedah_labels' => array_keys($kaedahData),
            'kaedah_values' => array_values($kaedahData),
            'fasiliti_labels' => array_keys($fasilitiData),
            'fasiliti_values' => array_values($fasilitiData),
            'trend_labels' => array_keys($trendData),
            'trend_values' => array_values($trendData),
            'pendapatan_labels' => array_keys($pendapatanData),
            'pendapatan_values' => array_values($pendapatanData),
        ];

        $fasilitiList = SenariFasiliti::where('masjid_id', $masjidId)->get();

        return view('laporan-tempahan.index', compact('tempahanList', 'stats', 'statusData', 'chartData', 'fasilitiList'));
    }

    public function pdf(Request $request)
    {
        // TODO: Implement PDF export
        return back()->with('info', 'PDF export akan diimplementasikan kemudian.');
    }

    public function excel(Request $request)
    {
        // TODO: Implement Excel export
        return back()->with('info', 'Excel export akan diimplementasikan kemudian.');
    }
}
