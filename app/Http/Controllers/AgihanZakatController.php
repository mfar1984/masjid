<?php

namespace App\Http\Controllers;

use App\Models\AgihanZakat;
use App\Models\PermohonanZakat;
use App\Models\Asnaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgihanZakatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships
        $baseQuery = AgihanZakat::with(['permohonanZakat', 'asnaf', 'masjid', 'dibayarOleh']);

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all agihan
        } else {
            // Admin Masjid can ONLY see agihan from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                $baseQuery->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('no_agihan', 'like', "%{$search}%")
                  ->orWhereHas('asnaf', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_ic', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $baseQuery->where('status', $request->status);
        }

        // Filter by kaedah bayaran
        if ($request->filled('kaedah_bayaran') && $request->kaedah_bayaran !== '') {
            $baseQuery->where('kaedah_bayaran', $request->kaedah_bayaran);
        }

        // Filter by date range
        if ($request->filled('tarikh_dari')) {
            $baseQuery->where('tarikh_agihan', '>=', $request->tarikh_dari);
        }
        if ($request->filled('tarikh_hingga')) {
            $baseQuery->where('tarikh_agihan', '<=', $request->tarikh_hingga);
        }

        // Get settings
        $masjidId = $user->isSuperAdmin() ? ($request->masjid_id ?? $user->masjid_id) : $user->masjid_id;
        $recordsPerPage = \App\Models\TetapanAsnaf::get('records_per_page', 10, $masjidId);
        
        // Get paginated results
        $agihan = $baseQuery->orderBy('created_at', 'desc')->paginate($recordsPerPage);

        // Build stats array - SEPARATE query for statistics
        $statsQuery = AgihanZakat::query();

        // Apply masjid isolation for stats
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalAgihan = (clone $statsQuery)->count();
        $belumBayar = (clone $statsQuery)->where('status', 'Belum Bayar')->count();
        $sudahBayar = (clone $statsQuery)->where('status', 'Sudah Bayar')->count();
        $dibatalkan = (clone $statsQuery)->where('status', 'Dibatalkan')->count();
        $totalDiagihkan = (clone $statsQuery)->where('status', 'Sudah Bayar')->sum('jumlah_diagihkan');

        // Always show all 5 cards
        $stats = [
            [
                'title' => 'Jumlah Agihan',
                'value' => $totalAgihan,
                'icon' => 'payments',
                'color' => 'blue'
            ],
            [
                'title' => 'Belum Bayar',
                'value' => $belumBayar,
                'icon' => 'pending',
                'color' => 'orange'
            ],
            [
                'title' => 'Sudah Bayar',
                'value' => $sudahBayar,
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Dibatalkan',
                'value' => $dibatalkan,
                'icon' => 'cancel',
                'color' => 'red'
            ],
            [
                'title' => 'Total Diagihkan',
                'value' => 'RM ' . number_format($totalDiagihkan, 2),
                'icon' => 'account_balance_wallet',
                'color' => 'purple'
            ]
        ];

        return view('agihan-zakat.index', compact('agihan', 'stats'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Get approved permohonan that haven't been distributed yet
        $query = PermohonanZakat::with('asnaf')
            ->where('status', 'Diluluskan')
            ->whereDoesntHave('agihanZakat'); // Only permohonan without agihan
        
        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }
        
        $permohonanList = $query->orderBy('created_at', 'desc')->get();

        return view('agihan-zakat.create', compact('permohonanList'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation
        $validated = $request->validate([
            'permohonan_zakat_id' => 'required|exists:permohonan_zakat,id',
            'tarikh_agihan' => 'required|date',
            'jumlah_diagihkan' => 'required|numeric|min:0',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Bank Transfer,E-Wallet',
            'no_rujukan' => 'required_if:kaedah_bayaran,Cek,Bank Transfer,E-Wallet',
            'nama_bank' => 'nullable|string|max:255',
            'no_akaun' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        // Get permohonan to get asnaf_id
        $permohonan = PermohonanZakat::findOrFail($validated['permohonan_zakat_id']);

        // WAJIB: Auto-assign masjid_id for data isolation
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            $validated['masjid_id'] = $permohonan->masjid_id;
        }

        $validated['asnaf_id'] = $permohonan->asnaf_id;
        $validated['no_agihan'] = AgihanZakat::generateNoAgihan($validated['masjid_id']);
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;
        $validated['status'] = 'Belum Bayar';

        AgihanZakat::create($validated);

        return redirect()->route('agihan-zakat.index')
            ->with('success', 'Agihan zakat berjaya dicipta.');
    }

    public function show(AgihanZakat $agihanZakat)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($agihanZakat->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $agihanZakat->load(['permohonanZakat', 'asnaf', 'masjid', 'createdBy', 'dibayarOleh']);
        
        return view('agihan-zakat.show', compact('agihanZakat'));
    }

    public function edit(AgihanZakat $agihanZakat)
    {
        $user = auth()->user();
        
        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($agihanZakat->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }
        
        if (!$agihanZakat->canBeEdited()) {
            return redirect()->route('agihan-zakat.show', $agihanZakat)
                ->with('error', 'Agihan tidak boleh diedit.');
        }

        return view('agihan-zakat.edit', compact('agihanZakat'));
    }

    public function update(Request $request, AgihanZakat $agihanZakat)
    {
        $user = auth()->user();
        
        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($agihanZakat->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }
        
        if (!$agihanZakat->canBeEdited()) {
            return redirect()->route('agihan-zakat.show', $agihanZakat)
                ->with('error', 'Agihan tidak boleh diedit.');
        }

        // Validation
        $validated = $request->validate([
            'tarikh_agihan' => 'required|date',
            'jumlah_diagihkan' => 'required|numeric|min:0',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Bank Transfer,E-Wallet',
            'no_rujukan' => 'required_if:kaedah_bayaran,Cek,Bank Transfer,E-Wallet',
            'nama_bank' => 'nullable|string|max:255',
            'no_akaun' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $agihanZakat->update($validated);

        return redirect()->route('agihan-zakat.show', $agihanZakat)
            ->with('success', 'Agihan zakat berjaya dikemaskini.');
    }

    public function destroy(AgihanZakat $agihanZakat)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($agihanZakat->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if (!$agihanZakat->canBeEdited()) {
            return redirect()->route('agihan-zakat.index')
                ->with('error', 'Agihan tidak boleh dipadamkan.');
        }

        // Delete bukti bayaran file if exists
        if ($agihanZakat->bukti_bayaran_path) {
            Storage::disk('public')->delete($agihanZakat->bukti_bayaran_path);
        }

        $agihanZakat->delete();

        return redirect()->route('agihan-zakat.index')
            ->with('success', 'Agihan zakat berjaya dipadamkan.');
    }

    public function bayar(Request $request, AgihanZakat $agihanZakat)
    {
        $user = auth()->user();
        
        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($agihanZakat->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }
        
        if (!$agihanZakat->canBePaid()) {
            return redirect()->route('agihan-zakat.show', $agihanZakat)
                ->with('error', 'Agihan tidak boleh dibayar.');
        }

        $validated = $request->validate([
            'tarikh_bayaran' => 'required|date',
            'bukti_bayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan_bayaran' => 'nullable|string',
        ]);

        // Handle file upload
        $path = $request->file('bukti_bayaran')->store('agihan-zakat/bukti', 'public');

        $agihanZakat->update([
            'status' => 'Sudah Bayar',
            'tarikh_bayaran' => $validated['tarikh_bayaran'],
            'bukti_bayaran_path' => $path,
            'catatan' => $validated['catatan_bayaran'] ?? $agihanZakat->catatan,
            'dibayar_oleh' => $user->id,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('agihan-zakat.show', $agihanZakat)
            ->with('success', 'Agihan berjaya ditandakan sebagai sudah bayar.');
    }

    public function batal(Request $request, AgihanZakat $agihanZakat)
    {
        $user = auth()->user();
        
        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($agihanZakat->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }
        
        if (!$agihanZakat->canBeCancelled()) {
            return redirect()->route('agihan-zakat.show', $agihanZakat)
                ->with('error', 'Agihan tidak boleh dibatalkan.');
        }

        $validated = $request->validate([
            'sebab_pembatalan' => 'required|string',
        ]);

        $agihanZakat->update([
            'status' => 'Dibatalkan',
            'catatan' => 'Dibatalkan: ' . $validated['sebab_pembatalan'],
            'updated_by' => $user->id,
        ]);

        return redirect()->route('agihan-zakat.show', $agihanZakat)
            ->with('success', 'Agihan berjaya dibatalkan.');
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $query = AgihanZakat::with(['permohonanZakat', 'asnaf', 'masjid']);

        // Apply masjid isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_agihan', 'like', "%{$search}%")
                  ->orWhereHas('asnaf', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_ic', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('kaedah_bayaran') && $request->kaedah_bayaran !== '') {
            $query->where('kaedah_bayaran', $request->kaedah_bayaran);
        }

        if ($request->filled('tarikh_dari')) {
            $query->where('tarikh_agihan', '>=', $request->tarikh_dari);
        }
        if ($request->filled('tarikh_hingga')) {
            $query->where('tarikh_agihan', '<=', $request->tarikh_hingga);
        }

        $agihan = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'agihan_zakat_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($agihan) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'No Agihan',
                'Tarikh Agihan',
                'Nama Asnaf',
                'No IC',
                'Kategori Asnaf',
                'No Permohonan',
                'Jumlah Diagihkan (RM)',
                'Kaedah Bayaran',
                'No Rujukan',
                'Status',
                'Tarikh Bayaran'
            ]);

            // Add data
            foreach ($agihan as $row) {
                fputcsv($file, [
                    $row->no_agihan,
                    $row->tarikh_agihan->format('d/m/Y'),
                    $row->asnaf->nama,
                    $row->asnaf->no_ic,
                    $row->asnaf->kategori_asnaf,
                    $row->permohonanZakat->no_permohonan,
                    number_format($row->jumlah_diagihkan, 2),
                    $row->kaedah_bayaran,
                    $row->no_rujukan ?? '-',
                    $row->status,
                    $row->tarikh_bayaran ? $row->tarikh_bayaran->format('d/m/Y') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function laporan(Request $request)
    {
        $user = auth()->user();
        $query = AgihanZakat::with(['permohonanZakat.asnaf', 'masjid']);

        // Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $query->where('masjid_id', $request->masjid_id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_bantuan')) {
            $query->whereHas('permohonanZakat', function($q) use ($request) {
                $q->where('jenis_bantuan', $request->jenis_bantuan);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tarikh_agihan', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tarikh_agihan', '<=', $request->date_to);
        }

        $agihan = $query->get();

        // Statistics
        $baseQuery = AgihanZakat::query();
        if (!$user->isSuperAdmin()) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $totalAgihan = (clone $baseQuery)->count();
        $selesai = (clone $baseQuery)->where('status', 'Sudah Bayar')->count();
        $menunggu = (clone $baseQuery)->where('status', 'Belum Bayar')->count();
        $dibatalkan = (clone $baseQuery)->where('status', 'Dibatalkan')->count();
        $jumlahDiagih = (clone $baseQuery)->where('status', 'Sudah Bayar')->sum('jumlah_diagihkan');
        $jumlahBelumBayar = (clone $baseQuery)->where('status', 'Belum Bayar')->sum('jumlah_diagihkan');

        $stats = [
            ['title' => 'Jumlah Agihan', 'value' => $totalAgihan, 'icon' => 'payments', 'color' => 'blue'],
            ['title' => 'Sudah Bayar', 'value' => $selesai, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Belum Bayar', 'value' => $menunggu, 'icon' => 'pending', 'color' => 'orange'],
            ['title' => 'Dibatalkan', 'value' => $dibatalkan, 'icon' => 'cancel', 'color' => 'red'],
            ['title' => 'Jumlah Diagihkan', 'value' => 'RM ' . number_format($jumlahDiagih, 2), 'icon' => 'account_balance_wallet', 'color' => 'teal'],
            ['title' => 'Belum Dibayar', 'value' => 'RM ' . number_format($jumlahBelumBayar, 2), 'icon' => 'hourglass_empty', 'color' => 'purple'],
        ];

        // By Status
        $byStatus = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // By Jenis Bantuan
        $byJenisBantuan = (clone $baseQuery)
            ->join('permohonan_zakat', 'agihan_zakat.permohonan_zakat_id', '=', 'permohonan_zakat.id')
            ->selectRaw('permohonan_zakat.jenis_bantuan, COUNT(*) as count')
            ->groupBy('permohonan_zakat.jenis_bantuan')
            ->get();

        // By Kaedah Bayaran
        $byKaedahBayaran = (clone $baseQuery)
            ->where('status', 'Sudah Bayar')
            ->selectRaw('kaedah_bayaran, COUNT(*) as count')
            ->groupBy('kaedah_bayaran')
            ->get();

        // Recent Agihan (30 days)
        $recentAgihan = (clone $baseQuery)
            ->with(['permohonanZakat.asnaf'])
            ->where('tarikh_agihan', '>=', now()->subDays(30))
            ->orderBy('tarikh_agihan', 'desc')
            ->limit(10)
            ->get();

        // Upcoming Bayaran (next 7 days)
        $upcomingBayaran = (clone $baseQuery)
            ->with(['permohonanZakat.asnaf'])
            ->where('status', 'Belum Bayar')
            ->where('tarikh_agihan', '<=', now()->addDays(7))
            ->orderBy('tarikh_agihan', 'asc')
            ->limit(10)
            ->get();

        // Average Jumlah Agihan
        $avgJumlah = (clone $baseQuery)->where('status', 'Sudah Bayar')->avg('jumlah_diagihkan') ?? 0;

        return view('agihan-zakat.laporan', compact(
            'stats',
            'byStatus',
            'byJenisBantuan',
            'byKaedahBayaran',
            'recentAgihan',
            'upcomingBayaran',
            'avgJumlah'
        ));
    }

    public function laporanExport(Request $request)
    {
        $user = auth()->user();
        $query = AgihanZakat::with(['permohonanZakat.asnaf', 'masjid']);

        // Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $query->where('masjid_id', $request->masjid_id);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_bantuan')) {
            $query->whereHas('permohonanZakat', function($q) use ($request) {
                $q->where('jenis_bantuan', $request->jenis_bantuan);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tarikh_agihan', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tarikh_agihan', '<=', $request->date_to);
        }

        $agihan = $query->orderBy('tarikh_agihan', 'desc')->get();

        // Generate CSV
        $filename = 'laporan_agihan_zakat_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($agihan) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'No Agihan',
                'Tarikh Agihan',
                'Nama Asnaf',
                'No IC',
                'Kategori Asnaf',
                'No Permohonan',
                'Jenis Bantuan',
                'Jumlah Diagihkan (RM)',
                'Kaedah Bayaran',
                'No Rujukan',
                'Status',
                'Tarikh Bayaran'
            ]);

            // Add data
            foreach ($agihan as $row) {
                fputcsv($file, [
                    $row->no_agihan,
                    $row->tarikh_agihan->format('d/m/Y'),
                    $row->permohonanZakat->asnaf->nama ?? '-',
                    $row->permohonanZakat->asnaf->no_ic ?? '-',
                    $row->permohonanZakat->asnaf->kategori_asnaf ?? '-',
                    $row->permohonanZakat->no_permohonan,
                    $row->permohonanZakat->jenis_bantuan,
                    number_format($row->jumlah_agihan, 2),
                    $row->kaedah_bayaran ?? '-',
                    $row->no_rujukan ?? '-',
                    $row->status,
                    $row->tarikh_bayaran ? $row->tarikh_bayaran->format('d/m/Y') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
