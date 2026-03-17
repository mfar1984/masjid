<?php

namespace App\Http\Controllers;

use App\Models\Ajk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AjkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships - exclude archived
        $baseQuery = Ajk::notArchived();

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all AJK
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see AJK from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no AJK
                $baseQuery->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%")
                  ->orWhere('jawatan', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $baseQuery->where('status', $request->status);
        }

        // Filter by jawatan
        if ($request->filled('jawatan') && $request->jawatan !== '') {
            $baseQuery->where('jawatan', $request->jawatan);
        }

        // Get paginated results
        $ajk = $baseQuery->orderBy('nama')->paginate(10);

        // Build stats array - SEPARATE query for statistics (not affected by search/filter)
        $statsQuery = Ajk::query();

        // Apply masjid isolation for stats (but NOT search/filter)
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalAjk = (clone $statsQuery)->count();
        $activeAjk = (clone $statsQuery)->where('status', 'Aktif')->count();
        $pendingAjk = (clone $statsQuery)->where('status', 'Menunggu')->count();
        $rejectedAjk = (clone $statsQuery)->where('status', 'Ditolak')->count();
        $inactiveAjk = (clone $statsQuery)->where('status', 'Tidak Aktif')->count();
        $suspendedAjk = (clone $statsQuery)->where('status', 'Digantung')->count();

        // Always show all 6 cards (consistent design even with 0 data)
        $stats = [
            [
                'title' => 'Jumlah AJK',
                'value' => $totalAjk,
                'icon' => 'people',
                'color' => 'blue'
            ],
            [
                'title' => 'Aktif',
                'value' => $activeAjk,
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Menunggu',
                'value' => $pendingAjk,
                'icon' => 'pending',
                'color' => 'orange'
            ],
            [
                'title' => 'Ditolak',
                'value' => $rejectedAjk,
                'icon' => 'close',
                'color' => 'red'
            ],
            [
                'title' => 'Tidak Aktif',
                'value' => $inactiveAjk,
                'icon' => 'cancel',
                'color' => 'gray'
            ],
            [
                'title' => 'Digantung',
                'value' => $suspendedAjk,
                'icon' => 'pause_circle',
                'color' => 'purple'
            ]
        ];

        return view('ajk.index', compact('ajk', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ajk.create', ['copyFrom' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => 'required|string|size:14|unique:ajk,no_ic',
            'telefon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'jantina' => 'required|in:Lelaki,Perempuan,Tidak Dinyatakan',
            'jawatan' => 'required|string|max:255',
            'jawatan_custom' => 'required_if:jawatan,Ahli Jawatankuasa|nullable|string|max:255',
            'urutan' => 'nullable|integer|min:1|max:9',
            'tarikh_lantikan' => 'required|date',
            'tarikh_tamat' => 'nullable|date|after:tarikh_lantikan',
            'tempoh_jawatan' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Tidak Aktif,Menunggu',
            'masjid_id' => $user->isSuperAdmin() ? 'required|exists:masjids,id' : 'nullable',
            'ic_depan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_belakang' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_lantikan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'gambar' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // WAJIB: Auto-assign masjid_id for data isolation
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            // Super Admin can specify masjid_id
            $validated['masjid_id'] = $request->masjid_id;
        }

        // Handle file uploads
        if ($request->hasFile('ic_depan')) {
            $validated['ic_depan_path'] = $request->file('ic_depan')->store('ajk/ic', 'public');
        }

        if ($request->hasFile('ic_belakang')) {
            $validated['ic_belakang_path'] = $request->file('ic_belakang')->store('ajk/ic', 'public');
        }

        if ($request->hasFile('surat_lantikan')) {
            $validated['surat_lantikan_path'] = $request->file('surat_lantikan')->store('ajk/surat', 'public');
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar_path'] = $request->file('gambar')->store('ajk/gambar', 'public');
        }

        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        Ajk::create($validated);

        return redirect()->route('ajk.index')
            ->with('success', 'Ahli Jawatankuasa berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        return view('ajk.show', compact('ajk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        return view('ajk.edit', compact('ajk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Validation
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => ['required', 'string', 'size:14', Rule::unique('ajk')->ignore($ajk->id)],
            'telefon' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'jantina' => 'required|in:Lelaki,Perempuan,Tidak Dinyatakan',
            'jawatan' => 'required|string|max:255',
            'jawatan_custom' => 'required_if:jawatan,Ahli Jawatankuasa|nullable|string|max:255',
            'urutan' => 'nullable|integer|min:1|max:9',
            'tarikh_lantikan' => 'required|date',
            'tarikh_tamat' => 'nullable|date|after:tarikh_lantikan',
            'tempoh_jawatan' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Tidak Aktif,Menunggu',
            'masjid_id' => 'required|exists:masjids,id',
            'ic_depan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_belakang' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_lantikan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'gambar' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('ic_depan')) {
            $validated['ic_depan_path'] = $request->file('ic_depan')->store('ajk/ic', 'public');
        }

        if ($request->hasFile('ic_belakang')) {
            $validated['ic_belakang_path'] = $request->file('ic_belakang')->store('ajk/ic', 'public');
        }

        if ($request->hasFile('surat_lantikan')) {
            $validated['surat_lantikan_path'] = $request->file('surat_lantikan')->store('ajk/surat', 'public');
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar_path'] = $request->file('gambar')->store('ajk/gambar', 'public');
        }

        $validated['updated_by'] = $user->id;

        $ajk->update($validated);

        return redirect()->route('ajk.index')
            ->with('success', 'Ahli Jawatankuasa berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $ajk->delete();

        return redirect()->route('ajk.index')
            ->with('success', 'Ahli Jawatankuasa berjaya dipadam.');
    }

    /**
     * Approve an AJK member
     */
    public function approve(Request $request, Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $request->validate([
            'catatan_kelulusan' => 'nullable|string',
        ]);

        $ajk->update([
            'status' => 'Aktif',
            'diluluskan_oleh' => $user->id,
            'tarikh_diluluskan' => now(),
            'catatan_kelulusan' => $request->catatan_kelulusan,
            'updated_by' => $user->id,
        ]);

        return redirect()->back()
            ->with('success', 'Ahli Jawatankuasa ' . $ajk->nama . ' berjaya diluluskan.');
    }

    /**
     * Reject an AJK member
     */
    public function reject(Request $request, Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $reason = $request->input('reason', 'Tiada sebab dinyatakan');

        $ajk->update([
            'status' => 'Ditolak',
            'catatan_kelulusan' => 'Ditolak oleh ' . $user->name . '. Sebab: ' . $reason,
            'updated_by' => $user->id,
        ]);

        return redirect()->back()
            ->with('success', 'Permohonan ahli jawatankuasa ' . $ajk->nama . ' telah ditolak.');
    }

    /**
     * Suspend an AJK member
     */
    public function suspend(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if ($ajk->status === 'Digantung') {
            return redirect()->route('ajk.index')
                ->with('error', 'Ahli Jawatankuasa sudah digantung.');
        }

        $ajk->update([
            'status' => 'Digantung',
            'suspended_at' => now(),
            'suspended_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('ajk.index')
            ->with('success', 'Ahli Jawatankuasa ' . $ajk->nama . ' berjaya digantung.');
    }

    /**
     * Reactivate (unsuspend) an AJK member
     */
    public function reactivate(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if ($ajk->status !== 'Digantung') {
            return redirect()->route('ajk.index')
                ->with('error', 'Ahli Jawatankuasa tidak dalam status digantung.');
        }

        $ajk->update([
            'status' => 'Aktif',
            'suspended_at' => null,
            'suspended_by' => null,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('ajk.index')
            ->with('success', 'Ahli Jawatankuasa ' . $ajk->nama . ' berjaya diaktifkan semula.');
    }

    /**
     * Export AJK data
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Ajk::query();

        // Apply masjid isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%")
                  ->orWhere('jawatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jawatan') && $request->jawatan !== '') {
            $query->where('jawatan', $request->jawatan);
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $ajk = $query->orderBy('nama')->get();

        // Generate CSV
        $filename = 'ajk_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($ajk) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Nama',
                'No. IC',
                'Telefon',
                'Email',
                'Jawatan',
                'Tarikh Lantikan',
                'Tarikh Tamat',
                'Status',
                'Tarikh Kemaskini'
            ]);

            // Add data
            foreach ($ajk as $row) {
                fputcsv($file, [
                    $row->nama,
                    $row->no_ic,
                    $row->telefon,
                    $row->email,
                    $row->jawatan,
                    $row->tarikh_lantikan_formatted,
                    $row->tarikh_tamat_formatted,
                    $row->status,
                    $row->tarikh_kemaskini_formatted
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display archived AJK
     */
    public function arkib(Request $request)
    {
        $user = auth()->user();

        // Base query - only archived
        $baseQuery = Ajk::archived();

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all archived AJK
        } else {
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                $baseQuery->whereRaw('1 = 0');
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%")
                  ->orWhere('jawatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jawatan') && $request->jawatan !== '') {
            $baseQuery->where('jawatan', $request->jawatan);
        }

        // Get paginated results
        $ajk = $baseQuery->orderBy('archived_at', 'desc')->paginate(10);

        // Build stats
        $statsQuery = Ajk::archived();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalArkib = $statsQuery->count();
        
        // Stats by jawatan
        $pengerusiArkib = (clone $statsQuery)->where('jawatan', 'Pengerusi')->count();
        $setiausahaArkib = (clone $statsQuery)->where('jawatan', 'Setiausaha')->count();
        $bendahariArkib = (clone $statsQuery)->where('jawatan', 'Bendahari')->count();
        
        // Recent archives (last 30 days)
        $recentArkib = (clone $statsQuery)->where('archived_at', '>=', now()->subDays(30))->count();

        $stats = [
            [
                'title' => 'Jumlah Arkib',
                'value' => $totalArkib,
                'icon' => 'archive',
                'color' => 'gray'
            ],
            [
                'title' => 'Pengerusi',
                'value' => $pengerusiArkib,
                'icon' => 'person',
                'color' => 'blue'
            ],
            [
                'title' => 'Setiausaha',
                'value' => $setiausahaArkib,
                'icon' => 'description',
                'color' => 'green'
            ],
            [
                'title' => 'Bendahari',
                'value' => $bendahariArkib,
                'icon' => 'account_balance_wallet',
                'color' => 'orange'
            ],
            [
                'title' => 'Arkib Baru (30 Hari)',
                'value' => $recentArkib,
                'icon' => 'schedule',
                'color' => 'purple'
            ]
        ];

        return view('ajk.arkib', compact('ajk', 'stats'));
    }

    /**
     * Archive an AJK member (move to arkib)
     */
    public function archive(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if ($ajk->is_archived) {
            return redirect()->back()
                ->with('error', 'AJK sudah berada dalam arkib.');
        }

        $ajk->update([
            'is_archived' => true,
            'archived_at' => now(),
            'archived_by' => $user->id,
            'status' => 'Tidak Aktif',
            'updated_by' => $user->id,
        ]);

        return redirect()->route('ajk.index')
            ->with('success', 'AJK ' . $ajk->nama . ' berjaya diarkibkan.');
    }

    /**
     * Restore AJK from arkib
     */
    public function unarchive(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if (!$ajk->is_archived) {
            return redirect()->back()
                ->with('error', 'AJK tidak berada dalam arkib.');
        }

        $ajk->update([
            'is_archived' => false,
            'archived_at' => null,
            'archived_by' => null,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('ajk.arkib')
            ->with('success', 'AJK ' . $ajk->nama . ' berjaya dipulihkan dari arkib.');
    }

    /**
     * Copy AJK data to create new record
     */
    public function copy(Ajk $ajk)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($ajk->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Pass the AJK data to create form
        return view('ajk.create', ['copyFrom' => $ajk]);
    }

    /**
     * Display AJK reports and analytics
     */
    public function laporan(Request $request)
    {
        $user = auth()->user();

        // Base query
        $baseQuery = Ajk::query();

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all AJK
        } else {
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                $baseQuery->whereRaw('1 = 0');
            }
        }

        // Apply filters
        if ($request->filled('jawatan')) {
            $baseQuery->where('jawatan', $request->jawatan);
        }

        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $baseQuery->whereDate('tarikh_lantikan', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $baseQuery->whereDate('tarikh_lantikan', '<=', $request->date_to);
        }

        if ($request->filled('masjid_id') && $user->isSuperAdmin()) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        // Statistics Cards
        $statsQuery = clone $baseQuery;
        $totalAjk = $statsQuery->count();
        $aktifAjk = (clone $baseQuery)->where('status', 'Aktif')->count();
        $pengerusiCount = (clone $baseQuery)->where('jawatan', 'Pengerusi')->count();
        $setiausahaCount = (clone $baseQuery)->where('jawatan', 'Setiausaha')->count();
        $bendahariCount = (clone $baseQuery)->where('jawatan', 'Bendahari')->count();
        $arkibCount = (clone $baseQuery)->where('is_archived', true)->count();

        $stats = [
            [
                'title' => 'Jumlah AJK',
                'value' => $totalAjk,
                'icon' => 'people',
                'color' => 'blue',
            ],
            [
                'title' => 'AJK Aktif',
                'value' => $aktifAjk,
                'icon' => 'check_circle',
                'color' => 'green',
            ],
            [
                'title' => 'Pengerusi',
                'value' => $pengerusiCount,
                'icon' => 'person',
                'color' => 'purple',
            ],
            [
                'title' => 'Setiausaha',
                'value' => $setiausahaCount,
                'icon' => 'description',
                'color' => 'orange',
            ],
            [
                'title' => 'Bendahari',
                'value' => $bendahariCount,
                'icon' => 'account_balance_wallet',
                'color' => 'yellow',
            ],
            [
                'title' => 'Arkib',
                'value' => $arkibCount,
                'icon' => 'archive',
                'color' => 'gray',
            ],
        ];

        // Summary by Jawatan
        $byJawatan = (clone $baseQuery)
            ->selectRaw('jawatan, COUNT(*) as count')
            ->groupBy('jawatan')
            ->orderBy('count', 'desc')
            ->get();

        // Summary by Status
        $byStatus = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        // Recent AJK (last 30 days)
        $recentAjk = (clone $baseQuery)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // AJK Tamat Tempoh (next 3 months)
        $tamatTempoh = (clone $baseQuery)
            ->whereNotNull('tarikh_tamat')
            ->whereBetween('tarikh_tamat', [now(), now()->addMonths(3)])
            ->orderBy('tarikh_tamat', 'asc')
            ->limit(10)
            ->get();

        // Demographics - By Jantina
        $byJantina = (clone $baseQuery)
            ->selectRaw('jantina, COUNT(*) as count')
            ->groupBy('jantina')
            ->get();

        // Average Tempoh Perkhidmatan (in years)
        $avgTempoh = (clone $baseQuery)
            ->whereNotNull('tarikh_lantikan')
            ->get()
            ->map(function ($ajk) {
                return $ajk->tarikh_lantikan ? now()->diffInYears($ajk->tarikh_lantikan) : 0;
            })
            ->average();

        return view('ajk.laporan', compact(
            'stats',
            'byJawatan',
            'byStatus',
            'recentAjk',
            'tamatTempoh',
            'byJantina',
            'avgTempoh'
        ));
    }

    /**
     * Display organization chart for printing
     */
    public function cartaOrganisasi()
    {
        $user = auth()->user();

        // Base query - only active AJK
        $baseQuery = Ajk::where('status', 'Aktif')->notArchived();

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                $baseQuery->whereRaw('1 = 0');
            }
        }

        // Get AJK grouped by urutan (level)
        $ajkList = $baseQuery->orderBy('urutan')->orderBy('jawatan')->get();

        // Group by level (urutan)
        $levels = $ajkList->groupBy('urutan');

        // Get masjid info
        $masjid = null;
        if (!$user->isSuperAdmin() && $user->masjid_id) {
            $masjid = \App\Models\Masjid::find($user->masjid_id);
        }

        return view('ajk.carta-organisasi', compact('levels', 'masjid'));
    }

    /**
     * Export laporan AJK to Excel
     */
    public function laporanExport(Request $request)
    {
        $user = auth()->user();

        // Base query
        $baseQuery = Ajk::query();

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all AJK
        } else {
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                $baseQuery->whereRaw('1 = 0');
            }
        }

        // Apply same filters as laporan
        if ($request->filled('jawatan')) {
            $baseQuery->where('jawatan', $request->jawatan);
        }

        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $baseQuery->whereDate('tarikh_lantikan', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $baseQuery->whereDate('tarikh_lantikan', '<=', $request->date_to);
        }

        if ($request->filled('masjid_id') && $user->isSuperAdmin()) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $ajkList = $baseQuery->orderBy('jawatan')->get();

        // Generate CSV
        $filename = 'laporan-ajk-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($ajkList, $user) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            $csvHeaders = ['Nama', 'No. IC', 'Jawatan', 'Telefon', 'Email', 'Tarikh Lantikan', 'Tarikh Tamat', 'Tempoh Jawatan', 'Status'];
            if ($user->isSuperAdmin()) {
                array_unshift($csvHeaders, 'Masjid');
            }
            fputcsv($file, $csvHeaders);

            // CSV Data
            foreach ($ajkList as $ajk) {
                $row = [
                    $ajk->nama,
                    $ajk->no_ic,
                    $ajk->jawatan_full,
                    $ajk->telefon,
                    $ajk->email ?? '-',
                    $ajk->tarikh_lantikan_formatted,
                    $ajk->tarikh_tamat ? $ajk->tarikh_tamat->format('d/m/Y') : '-',
                    $ajk->tempoh_jawatan ?? '-',
                    $ajk->status,
                ];

                if ($user->isSuperAdmin()) {
                    array_unshift($row, $ajk->masjid->nama ?? 'Tiada Masjid');
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
