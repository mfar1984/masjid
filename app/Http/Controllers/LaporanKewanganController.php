<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKewangan;
use App\Models\KutipanDana;
use App\Models\Perbelanjaan;
use App\Models\AkaunBank;
use App\Models\KategoriKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKewanganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        // Check if user has permission to access any TAB under Laporan Kewangan
        if (!$isSuperAdmin) {
            $hasPermission = $user->hasPermission('laporan_kewangan_penyata', 'read') ||
                           $user->hasPermission('laporan_kewangan_pendapatan', 'read') ||
                           $user->hasPermission('laporan_kewangan_perbelanjaan', 'read') ||
                           $user->hasPermission('laporan_kewangan_aliran_tunai', 'read') ||
                           $user->hasPermission('laporan_kewangan_imbangan_duga', 'read') ||
                           $user->hasPermission('laporan_kewangan_perbandingan', 'read') ||
                           $user->hasPermission('laporan_kewangan_kategori', 'read') ||
                           $user->hasPermission('laporan_kewangan_baki_bank', 'read');
            
            if (!$hasPermission) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
            }
        }
        
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;

        // Get all masjids for Super Admin dropdown
        $masjids = $isSuperAdmin ? \App\Models\Masjid::orderBy('nama')->get() : collect();

        // TAB Permissions - check read permission for each TAB
        $tabPermissions = [
            'penyata' => $user->hasPermission('laporan_kewangan_penyata', 'read'),
            'pendapatan' => $user->hasPermission('laporan_kewangan_pendapatan', 'read'),
            'perbelanjaan' => $user->hasPermission('laporan_kewangan_perbelanjaan', 'read'),
            'aliran_tunai' => $user->hasPermission('laporan_kewangan_aliran_tunai', 'read'),
            'imbangan_duga' => $user->hasPermission('laporan_kewangan_imbangan_duga', 'read'),
            'perbandingan' => $user->hasPermission('laporan_kewangan_perbandingan', 'read'),
            'kategori' => $user->hasPermission('laporan_kewangan_kategori', 'read'),
            'baki_bank' => $user->hasPermission('laporan_kewangan_baki_bank', 'read'),
        ];

        // Get date range from request or default based on available data
        if (!$request->filled('tarikh_dari') || !$request->filled('tarikh_hingga')) {
            // Find earliest and latest transaction dates
            $earliestKutipan = KutipanDana::where('masjid_id', $masjidId)->min('tarikh_kutipan');
            $earliestPerbelanjaan = Perbelanjaan::where('masjid_id', $masjidId)->min('tarikh_perbelanjaan');
            $latestKutipan = KutipanDana::where('masjid_id', $masjidId)->max('tarikh_kutipan');
            $latestPerbelanjaan = Perbelanjaan::where('masjid_id', $masjidId)->max('tarikh_perbelanjaan');
            
            $earliestDate = collect([$earliestKutipan, $earliestPerbelanjaan])->filter()->min();
            $latestDate = collect([$latestKutipan, $latestPerbelanjaan])->filter()->max();
            
            // Default to current month if no data
            $tarikhDari = $request->input('tarikh_dari', $earliestDate ?? now()->startOfMonth()->format('Y-m-d'));
            $tarikhHingga = $request->input('tarikh_hingga', $latestDate ?? now()->endOfMonth()->format('Y-m-d'));
        } else {
            $tarikhDari = $request->input('tarikh_dari');
            $tarikhHingga = $request->input('tarikh_hingga');
        }

        // Get bank accounts first to calculate baki awal
        $akaunBank = AkaunBank::withoutGlobalScope('masjid')
            ->where('akaun_bank.masjid_id', $masjidId)
            ->where('status', 'Aktif')
            ->get();
        
        $totalBakiAwal = $akaunBank->sum('baki_awal');
        
        // Stats for Penyata Kewangan - use KutipanDana and Perbelanjaan directly
        $jumlahPendapatan = KutipanDana::withoutGlobalScope('masjid')
            ->where('masjid_id', $masjidId)
            ->whereBetween('tarikh_kutipan', [$tarikhDari, $tarikhHingga])
            ->sum('jumlah');
            
        $jumlahPerbelanjaan = Perbelanjaan::withoutGlobalScope('masjid')
            ->where('masjid_id', $masjidId)
            ->whereBetween('tarikh_perbelanjaan', [$tarikhDari, $tarikhHingga])
            ->sum('jumlah');
        
        // Calculate actual bank balance for each account
        foreach ($akaunBank as $akaun) {
            // Get total pendapatan for this account
            $totalPendapatan = KutipanDana::where('masjid_id', $masjidId)
                ->where('akaun_bank_id', $akaun->id)
                ->sum('jumlah');
            
            // Get total perbelanjaan for this account
            $totalPerbelanjaan = Perbelanjaan::where('masjid_id', $masjidId)
                ->where('akaun_bank_id', $akaun->id)
                ->sum('jumlah');
            
            // Calculate actual balance: baki_awal + pendapatan - perbelanjaan
            $akaun->baki_sebenar = $akaun->baki_awal + $totalPendapatan - $totalPerbelanjaan;
        }
        
        // Baki Bersih = Total Baki Bank Sebenar (not just period transactions)
        $bakiBersih = $akaunBank->sum('baki_sebenar');

        $stats = [
            'baki_awal' => $totalBakiAwal,
            'total_pendapatan' => $jumlahPendapatan,
            'total_perbelanjaan' => $jumlahPerbelanjaan,
            'baki_bersih' => $bakiBersih,
        ];

        // Pendapatan by Kategori
        $pendapatanByKategori = KutipanDana::withoutGlobalScope('masjid')
            ->join('kategori_kewangan', 'kutipan_dana.kategori_kewangan_id', '=', 'kategori_kewangan.id')
            ->where('kutipan_dana.masjid_id', $masjidId)
            ->whereBetween('kutipan_dana.tarikh_kutipan', [$tarikhDari, $tarikhHingga])
            ->selectRaw('kategori_kewangan.nama_kategori, SUM(kutipan_dana.jumlah) as total')
            ->groupBy('kategori_kewangan.nama_kategori')
            ->pluck('total', 'nama_kategori')
            ->toArray();

        // Perbelanjaan by Kategori
        $perbelanjaanByKategori = Perbelanjaan::withoutGlobalScope('masjid')
            ->join('kategori_kewangan', 'perbelanjaan.kategori_kewangan_id', '=', 'kategori_kewangan.id')
            ->where('perbelanjaan.masjid_id', $masjidId)
            ->whereBetween('perbelanjaan.tarikh_perbelanjaan', [$tarikhDari, $tarikhHingga])
            ->selectRaw('kategori_kewangan.nama_kategori, SUM(perbelanjaan.jumlah) as total')
            ->groupBy('kategori_kewangan.nama_kategori')
            ->pluck('total', 'nama_kategori')
            ->toArray();

        // Aliran Tunai Bulanan
        $pendapatanBulanan = KutipanDana::withoutGlobalScope('masjid')
            ->where('masjid_id', $masjidId)
            ->whereBetween('tarikh_kutipan', [$tarikhDari, $tarikhHingga])
            ->selectRaw('DATE_FORMAT(tarikh_kutipan, "%Y-%m") as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $perbelanjaanBulanan = Perbelanjaan::withoutGlobalScope('masjid')
            ->where('masjid_id', $masjidId)
            ->whereBetween('tarikh_perbelanjaan', [$tarikhDari, $tarikhHingga])
            ->selectRaw('DATE_FORMAT(tarikh_perbelanjaan, "%Y-%m") as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // Merge and format aliran tunai data
        $allBulan = collect($pendapatanBulanan->keys()->merge($perbelanjaanBulanan->keys())->unique()->sort()->values());
        $aliranTunaiBulanan = $allBulan->map(function ($bulan) use ($pendapatanBulanan, $perbelanjaanBulanan) {
            $pendapatan = $pendapatanBulanan->get($bulan, 0);
            $perbelanjaan = $perbelanjaanBulanan->get($bulan, 0);
            return [
                'bulan' => date('M Y', strtotime($bulan . '-01')),
                'pendapatan' => $pendapatan,
                'perbelanjaan' => $perbelanjaan,
                'baki' => $pendapatan - $perbelanjaan,
            ];
        })->toArray();

        // ============================================
        // TAB 5: PENYATA PENDAPATAN & PERBELANJAAN (Income & Expenditure Statement)
        // ============================================
        $imbanganDuga = [];
        
        // BAHAGIAN A: PENDAPATAN
        $kategoriPendapatan = KategoriKewangan::where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'kategori_pendapatan')
            ->where('status', 'Aktif')
            ->orderBy('urutan')
            ->get();
        
        $totalPendapatanSection = 0;
        foreach ($kategoriPendapatan as $kategori) {
            $total = KutipanDana::where('masjid_id', $masjidId)
                ->where('kategori_kewangan_id', $kategori->id)
                ->whereBetween('tarikh_kutipan', [$tarikhDari, $tarikhHingga])
                ->sum('jumlah');
            
            if ($total > 0) {
                $imbanganDuga[] = [
                    'kategori' => $kategori->nama_kategori,
                    'jenis' => 'Pendapatan',
                    'jumlah' => $total,
                ];
                $totalPendapatanSection += $total;
            }
        }
        
        // BAHAGIAN B: PERBELANJAAN
        $kategoriPerbelanjaan = KategoriKewangan::where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'kategori_perbelanjaan')
            ->where('status', 'Aktif')
            ->orderBy('urutan')
            ->get();
        
        $totalPerbelanjaanSection = 0;
        foreach ($kategoriPerbelanjaan as $kategori) {
            $total = Perbelanjaan::where('masjid_id', $masjidId)
                ->where('kategori_kewangan_id', $kategori->id)
                ->whereBetween('tarikh_perbelanjaan', [$tarikhDari, $tarikhHingga])
                ->sum('jumlah');
            
            if ($total > 0) {
                $imbanganDuga[] = [
                    'kategori' => $kategori->nama_kategori,
                    'jenis' => 'Perbelanjaan',
                    'jumlah' => $total,
                ];
                $totalPerbelanjaanSection += $total;
            }
        }
        
        // Calculate surplus/deficit
        $totalDebit = $totalPerbelanjaanSection;
        $totalKredit = $totalPendapatanSection;
        $lebihan = $totalPendapatanSection - $totalPerbelanjaanSection;

        // ============================================
        // TAB 6: PERBANDINGAN BULANAN (Monthly Comparison)
        // ============================================
        $perbandinganBulanan = [];
        
        foreach ($allBulan as $bulan) {
            $pendapatan = $pendapatanBulanan->get($bulan, 0);
            $perbelanjaan = $perbelanjaanBulanan->get($bulan, 0);
            $baki = $pendapatan - $perbelanjaan;
            
            $perbandinganBulanan[] = [
                'bulan' => date('M Y', strtotime($bulan . '-01')),
                'bulan_raw' => $bulan,
                'pendapatan' => $pendapatan,
                'perbelanjaan' => $perbelanjaan,
                'baki' => $baki,
                'peratus_perbelanjaan' => $pendapatan > 0 ? ($perbelanjaan / $pendapatan * 100) : 0,
            ];
        }

        // ============================================
        // TAB 7: LAPORAN MENGIKUT KATEGORI (Category Report)
        // ============================================
        
        // Top 5 Pendapatan by Kategori
        $topPendapatan = KutipanDana::withoutGlobalScope('masjid')
            ->join('kategori_kewangan', 'kutipan_dana.kategori_kewangan_id', '=', 'kategori_kewangan.id')
            ->where('kutipan_dana.masjid_id', $masjidId)
            ->whereBetween('kutipan_dana.tarikh_kutipan', [$tarikhDari, $tarikhHingga])
            ->selectRaw('kategori_kewangan.nama_kategori, SUM(kutipan_dana.jumlah) as total, COUNT(*) as bilangan')
            ->groupBy('kategori_kewangan.nama_kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($jumlahPendapatan) {
                return [
                    'kategori' => $item->nama_kategori,
                    'total' => $item->total,
                    'bilangan' => $item->bilangan,
                    'peratus' => $jumlahPendapatan > 0 ? ($item->total / $jumlahPendapatan * 100) : 0,
                ];
            });
        
        // Top 5 Perbelanjaan by Kategori
        $topPerbelanjaan = Perbelanjaan::withoutGlobalScope('masjid')
            ->join('kategori_kewangan', 'perbelanjaan.kategori_kewangan_id', '=', 'kategori_kewangan.id')
            ->where('perbelanjaan.masjid_id', $masjidId)
            ->whereBetween('perbelanjaan.tarikh_perbelanjaan', [$tarikhDari, $tarikhHingga])
            ->selectRaw('kategori_kewangan.nama_kategori, SUM(perbelanjaan.jumlah) as total, COUNT(*) as bilangan')
            ->groupBy('kategori_kewangan.nama_kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($jumlahPerbelanjaan) {
                return [
                    'kategori' => $item->nama_kategori,
                    'total' => $item->total,
                    'bilangan' => $item->bilangan,
                    'peratus' => $jumlahPerbelanjaan > 0 ? ($item->total / $jumlahPerbelanjaan * 100) : 0,
                ];
            });

        return view('laporan-kewangan.index', compact(
            'stats',
            'pendapatanByKategori',
            'perbelanjaanByKategori',
            'aliranTunaiBulanan',
            'akaunBank',
            'tarikhDari',
            'tarikhHingga',
            'isSuperAdmin',
            'masjids',
            'masjidId',
            'tabPermissions',
            'imbanganDuga',
            'totalDebit',
            'totalKredit',
            'lebihan',
            'perbandinganBulanan',
            'topPendapatan',
            'topPerbelanjaan'
        ));
    }

    public function pdf(Request $request)
    {
        // TODO: Implement PDF export
        return back()->with('info', 'Export PDF akan dilaksanakan tidak lama lagi.');
    }

    public function excel(Request $request)
    {
        // TODO: Implement Excel export
        return back()->with('info', 'Export Excel akan dilaksanakan tidak lama lagi.');
    }
}
