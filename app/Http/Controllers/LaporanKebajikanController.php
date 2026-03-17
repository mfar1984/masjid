<?php

namespace App\Http\Controllers;

use App\Models\ProgramKebajikan;
use App\Models\PenerimaBantuan;
use App\Models\PermohonanBantuan;
use App\Models\PembayaranBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanKebajikanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        // Base queries with masjid isolation
        $programQuery = ProgramKebajikan::query();
        $penerimaQuery = PenerimaBantuan::query();
        $permohonanQuery = PermohonanBantuan::query();
        $pembayaranQuery = PembayaranBantuan::query();

        // Multi-masjid isolation
        $masjidId = null; // Initialize variable
        
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $masjidId = $request->masjid_id;
                $programQuery->where('program_kebajikan.masjid_id', $masjidId);
                $penerimaQuery->where('penerima_bantuan.masjid_id', $masjidId);
                $permohonanQuery->where('permohonan_bantuan.masjid_id', $masjidId);
                $pembayaranQuery->where('pembayaran_bantuan.masjid_id', $masjidId);
            }
            // If Super Admin doesn't specify masjid_id, show all data (no filter)
        } else {
            $masjidId = $user->masjid_id;
            $programQuery->where('program_kebajikan.masjid_id', $masjidId);
            $penerimaQuery->where('penerima_bantuan.masjid_id', $masjidId);
            $permohonanQuery->where('permohonan_bantuan.masjid_id', $masjidId);
            $pembayaranQuery->where('pembayaran_bantuan.masjid_id', $masjidId);
        }

        // Filters
        if ($request->filled('program_kebajikan_id')) {
            $permohonanQuery->where('program_kebajikan_id', $request->program_kebajikan_id);
            $pembayaranQuery->where('program_kebajikan_id', $request->program_kebajikan_id);
        }

        if ($request->filled('kategori_program')) {
            $programIds = ProgramKebajikan::where('kategori_program', $request->kategori_program)->pluck('id');
            $permohonanQuery->whereIn('program_kebajikan_id', $programIds);
        }

        if ($request->filled('status')) {
            $permohonanQuery->where('status_permohonan', $request->status);
        }

        if ($request->filled('tarikh_dari')) {
            $permohonanQuery->whereDate('tarikh_permohonan', '>=', $request->tarikh_dari);
            $pembayaranQuery->whereDate('tarikh_pembayaran', '>=', $request->tarikh_dari);
        }

        if ($request->filled('tarikh_hingga')) {
            $permohonanQuery->whereDate('tarikh_permohonan', '<=', $request->tarikh_hingga);
            $pembayaranQuery->whereDate('tarikh_pembayaran', '<=', $request->tarikh_hingga);
        }

        // Stats - remove global scope to avoid duplicate masjid_id
        $stats = [
            'total_program' => ProgramKebajikan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->count(),
            'total_penerima' => PenerimaBantuan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->count(),
            'total_permohonan' => PermohonanBantuan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->count(),
            'total_pembayaran' => PembayaranBantuan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->count(),
            'permohonan_lulus' => PermohonanBantuan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->where('status_permohonan', 'Lulus')->count(),
            'permohonan_ditolak' => PermohonanBantuan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->where('status_permohonan', 'Ditolak')->count(),
            'jumlah_dibayar' => PembayaranBantuan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->where('status_pembayaran', 'Sudah Bayar')->sum('jumlah_bayaran'),
            'jumlah_belum_bayar' => PembayaranBantuan::withoutGlobalScope('masjid')->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->where('status_pembayaran', 'Belum Bayar')->sum('jumlah_bayaran'),
        ];

        // Chart data: Permohonan by Status - remove global scope to avoid duplicate masjid_id
        $permohonanByStatus = PermohonanBantuan::withoutGlobalScope('masjid')
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->select('status_permohonan', DB::raw('count(*) as total'))
            ->groupBy('status_permohonan')
            ->get()
            ->pluck('total', 'status_permohonan');

        // Chart data: Pembayaran by Kaedah - remove global scope
        $pembayaranByKaedah = PembayaranBantuan::withoutGlobalScope('masjid')
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->select('kaedah_bayaran', DB::raw('count(*) as total'))
            ->groupBy('kaedah_bayaran')
            ->get()
            ->pluck('total', 'kaedah_bayaran');

        // Chart data: Permohonan by Program (Top 10) - remove global scope
        $permohonanByProgram = PermohonanBantuan::withoutGlobalScope('masjid')
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->select('program_kebajikan_id', DB::raw('count(*) as total'))
            ->groupBy('program_kebajikan_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->with('programKebajikan')
            ->get();

        // Chart data: Trend Bulanan (Last 12 months) - remove global scope
        $trendBulanan = PermohonanBantuan::withoutGlobalScope('masjid')
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->select(DB::raw('DATE_FORMAT(tarikh_permohonan, "%Y-%m") as bulan'), DB::raw('count(*) as total'))
            ->where('tarikh_permohonan', '>=', now()->subMonths(12))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Chart data: Penerima by Kategori - remove global scope and use table prefix
        $penerimaByKategori = PermohonanBantuan::withoutGlobalScope('masjid')
            ->when($masjidId, fn($q) => $q->where('permohonan_bantuan.masjid_id', $masjidId))
            ->join('program_kebajikan', function($join) use ($masjidId) {
                $join->on('permohonan_bantuan.program_kebajikan_id', '=', 'program_kebajikan.id');
                if ($masjidId) {
                    $join->where('program_kebajikan.masjid_id', '=', $masjidId);
                }
            })
            ->select('program_kebajikan.kategori_program', DB::raw('count(DISTINCT permohonan_bantuan.penerima_bantuan_id) as total'))
            ->groupBy('program_kebajikan.kategori_program')
            ->get()
            ->pluck('total', 'kategori_program');

        // Table data
        // Get items per page from settings
        $settings = \App\Models\TetapanKebajikan::getSettings($isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id, ['items_per_page']);
        $perPage = $settings['items_per_page'] ?? 10;

        $permohonan = (clone $permohonanQuery)
            ->with(['penerimaBantuan', 'programKebajikan'])
            ->latest('tarikh_permohonan')
            ->paginate($perPage);

        // Programs for filter
        $programs = ProgramKebajikan::where('status_program', 'Aktif')
            ->when(!$isSuperAdmin, function ($q) use ($user) {
                $q->where('masjid_id', $user->masjid_id);
            })
            ->get();

        return view('laporan-kebajikan.index', compact(
            'stats',
            'permohonanByStatus',
            'pembayaranByKaedah',
            'permohonanByProgram',
            'trendBulanan',
            'penerimaByKategori',
            'permohonan',
            'programs',
            'isSuperAdmin'
        ));
    }

    public function pdf(Request $request)
    {
        // TODO: Implement PDF export
        return redirect()->back()->with('info', 'PDF export akan dilaksanakan tidak lama lagi.');
    }

    public function excel(Request $request)
    {
        // TODO: Implement Excel export
        return redirect()->back()->with('info', 'Excel export akan dilaksanakan tidak lama lagi.');
    }
}
