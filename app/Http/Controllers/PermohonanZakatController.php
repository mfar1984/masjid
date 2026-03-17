<?php

namespace App\Http\Controllers;

use App\Models\PermohonanZakat;
use App\Models\Asnaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermohonanZakatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships
        $baseQuery = PermohonanZakat::with(['asnaf', 'disemakOleh', 'diluluskanOleh']);

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all permohonan
        } else {
            // Admin Masjid can ONLY see permohonan from their own masjid
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
                $q->where('no_permohonan', 'like', "%{$search}%")
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

        // Filter by jenis bantuan
        if ($request->filled('jenis_bantuan') && $request->jenis_bantuan !== '') {
            $baseQuery->where('jenis_bantuan', $request->jenis_bantuan);
        }

        // Get settings
        $masjidId = $user->isSuperAdmin() ? ($request->masjid_id ?? $user->masjid_id) : $user->masjid_id;
        $recordsPerPage = \App\Models\TetapanAsnaf::get('records_per_page', 10, $masjidId);
        
        // Get paginated results
        $permohonan = $baseQuery->orderBy('created_at', 'desc')->paginate($recordsPerPage);

        // Build stats array - SEPARATE query for statistics (not affected by search/filter)
        $statsQuery = PermohonanZakat::query();

        // Apply masjid isolation for stats (but NOT search/filter)
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalPermohonan = (clone $statsQuery)->count();
        $menunggu = (clone $statsQuery)->where('status', 'Menunggu')->count();
        $dalamSemakan = (clone $statsQuery)->where('status', 'Dalam Semakan')->count();
        $diluluskan = (clone $statsQuery)->where('status', 'Diluluskan')->count();
        $ditolak = (clone $statsQuery)->where('status', 'Ditolak')->count();

        // Always show all 5 cards (consistent design even with 0 data)
        $stats = [
            [
                'title' => 'Jumlah Permohonan',
                'value' => $totalPermohonan,
                'icon' => 'description',
                'color' => 'blue'
            ],
            [
                'title' => 'Menunggu',
                'value' => $menunggu,
                'icon' => 'pending',
                'color' => 'orange'
            ],
            [
                'title' => 'Dalam Semakan',
                'value' => $dalamSemakan,
                'icon' => 'rate_review',
                'color' => 'blue'
            ],
            [
                'title' => 'Diluluskan',
                'value' => $diluluskan,
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Ditolak',
                'value' => $ditolak,
                'icon' => 'cancel',
                'color' => 'red'
            ]
        ];

        return view('permohonan-zakat.index', compact('permohonan', 'stats'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Get Asnaf list with masjid isolation
        // Only show Asnaf yang sudah Diluluskan (eligible untuk mohon bantuan)
        $query = Asnaf::where('status', 'Diluluskan');
        
        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }
        
        $asnafList = $query->orderBy('nama')->get();

        // Get workflow settings
        $masjidId = $user->masjid_id;
        $settings = [
            'require_mesyuarat_attachment' => \App\Models\TetapanAsnaf::get('require_mesyuarat_attachment', true, $masjidId),
            'require_supporting_docs' => \App\Models\TetapanAsnaf::get('require_supporting_docs', true, $masjidId),
            'max_file_size_mb' => \App\Models\TetapanAsnaf::get('max_file_size_mb', 5, $masjidId),
            'allowed_file_types' => \App\Models\TetapanAsnaf::get('allowed_file_types', ['pdf','jpg','jpeg','png'], $masjidId),
        ];

        return view('permohonan-zakat.create', compact('asnafList', 'settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asnaf_id' => 'required|exists:asnaf,id',
            'tarikh_permohonan' => 'required|date',
            'jenis_bantuan' => 'required|in:Tunai,Barangan,Pendidikan,Perubatan,Kecemasan',
            'kategori_bantuan' => 'required|in:Bulanan,Sekali,Khas',
            'jumlah_dipohon' => 'required|numeric|min:0',
            'sebab_permohonan' => 'required|string',
            'dokumen_sokongan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $masjidId = auth()->user()->masjid_id;
        $validated['masjid_id'] = $masjidId;
        $validated['no_permohonan'] = PermohonanZakat::generateNoPermohonan($masjidId);
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'Menunggu';

        // Handle file upload
        if ($request->hasFile('dokumen_sokongan')) {
            $path = $request->file('dokumen_sokongan')->store('permohonan-zakat/dokumen', 'public');
            $validated['dokumen_sokongan_path'] = $path;
        }

        PermohonanZakat::create($validated);

        return redirect()->route('permohonan-zakat.index')
            ->with('success', 'Permohonan zakat berjaya dicipta.');
    }

    public function show(PermohonanZakat $permohonanZakat)
    {
        $permohonanZakat->load(['asnaf', 'disemakOleh', 'diluluskanOleh', 'createdBy']);
        return view('permohonan-zakat.show', compact('permohonanZakat'));
    }

    public function edit(PermohonanZakat $permohonanZakat)
    {
        $user = auth()->user();
        
        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($permohonanZakat->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }
        
        if (!$permohonanZakat->canBeEdited()) {
            return redirect()->route('permohonan-zakat.show', $permohonanZakat)
                ->with('error', 'Permohonan tidak boleh diedit.');
        }

        // Get Asnaf list with masjid isolation
        // Only show Asnaf yang sudah Diluluskan (eligible untuk mohon bantuan)
        $query = Asnaf::where('status', 'Diluluskan');
        
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }
        
        $asnafList = $query->orderBy('nama')->get();

        return view('permohonan-zakat.edit', compact('permohonanZakat', 'asnafList'));
    }

    public function update(Request $request, PermohonanZakat $permohonanZakat)
    {
        if (!$permohonanZakat->canBeEdited()) {
            return redirect()->route('permohonan-zakat.show', $permohonanZakat)
                ->with('error', 'Permohonan tidak boleh diedit.');
        }

        $validated = $request->validate([
            'asnaf_id' => 'required|exists:asnaf,id',
            'tarikh_permohonan' => 'required|date',
            'jenis_bantuan' => 'required|in:Tunai,Barangan,Pendidikan,Perubatan,Kecemasan',
            'kategori_bantuan' => 'required|in:Bulanan,Sekali,Khas',
            'jumlah_dipohon' => 'required|numeric|min:0',
            'sebab_permohonan' => 'required|string',
            'dokumen_sokongan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['updated_by'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('dokumen_sokongan')) {
            // Delete old file
            if ($permohonanZakat->dokumen_sokongan_path) {
                Storage::disk('public')->delete($permohonanZakat->dokumen_sokongan_path);
            }
            $path = $request->file('dokumen_sokongan')->store('permohonan-zakat/dokumen', 'public');
            $validated['dokumen_sokongan_path'] = $path;
        }

        $permohonanZakat->update($validated);

        return redirect()->route('permohonan-zakat.show', $permohonanZakat)
            ->with('success', 'Permohonan zakat berjaya dikemaskini.');
    }

    public function destroy(PermohonanZakat $permohonanZakat)
    {
        if (!$permohonanZakat->canBeEdited()) {
            return redirect()->route('permohonan-zakat.index')
                ->with('error', 'Permohonan tidak boleh dipadamkan.');
        }

        // Delete files
        if ($permohonanZakat->dokumen_sokongan_path) {
            Storage::disk('public')->delete($permohonanZakat->dokumen_sokongan_path);
        }

        $permohonanZakat->delete();

        return redirect()->route('permohonan-zakat.index')
            ->with('success', 'Permohonan zakat berjaya dipadamkan.');
    }

    public function approve(Request $request, PermohonanZakat $permohonanZakat)
    {
        if (!$permohonanZakat->canBeApproved()) {
            return redirect()->route('permohonan-zakat.show', $permohonanZakat)
                ->with('error', 'Permohonan tidak boleh diluluskan.');
        }

        $validated = $request->validate([
            'jumlah_diluluskan' => 'required|numeric|min:0',
            'tarikh_mesyuarat' => 'required|date',
            'no_mesyuarat' => 'required|string',
            'minit_mesyuarat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan_kelulusan' => 'nullable|string',
        ]);

        // Handle minit mesyuarat upload
        $path = $request->file('minit_mesyuarat')->store('permohonan-zakat/minit-mesyuarat', 'public');

        $permohonanZakat->update([
            'status' => 'Diluluskan',
            'tarikh_kelulusan' => now(),
            'diluluskan_oleh' => auth()->id(),
            'jumlah_diluluskan' => $validated['jumlah_diluluskan'],
            'tarikh_mesyuarat' => $validated['tarikh_mesyuarat'],
            'no_mesyuarat' => $validated['no_mesyuarat'],
            'minit_mesyuarat_path' => $path,
            'catatan_kelulusan' => $validated['catatan_kelulusan'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('permohonan-zakat.show', $permohonanZakat)
            ->with('success', 'Permohonan berjaya diluluskan.');
    }

    public function reject(Request $request, PermohonanZakat $permohonanZakat)
    {
        if (!$permohonanZakat->canBeRejected()) {
            return redirect()->route('permohonan-zakat.show', $permohonanZakat)
                ->with('error', 'Permohonan tidak boleh ditolak.');
        }

        $validated = $request->validate([
            'sebab_penolakan' => 'required|string',
        ]);

        $permohonanZakat->update([
            'status' => 'Ditolak',
            'tarikh_penolakan' => now(),
            'sebab_penolakan' => $validated['sebab_penolakan'],
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('permohonan-zakat.show', $permohonanZakat)
            ->with('success', 'Permohonan berjaya ditolak.');
    }

    /**
     * Export permohonan zakat data
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = PermohonanZakat::with(['asnaf', 'masjid']);

        // Apply masjid isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_permohonan', 'like', "%{$search}%")
                  ->orWhereHas('asnaf', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_ic', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_bantuan') && $request->jenis_bantuan !== '') {
            $query->where('jenis_bantuan', $request->jenis_bantuan);
        }

        $permohonan = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'permohonan_zakat_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($permohonan) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'No Permohonan',
                'Tarikh Permohonan',
                'Nama Asnaf',
                'No IC Asnaf',
                'Jenis Bantuan',
                'Kategori Bantuan',
                'Jumlah Dipohon (RM)',
                'Jumlah Diluluskan (RM)',
                'Status',
                'Sebab Permohonan',
                'Tarikh Kelulusan',
                'Tarikh Penolakan'
            ]);

            // Add data
            foreach ($permohonan as $row) {
                fputcsv($file, [
                    $row->no_permohonan,
                    $row->tarikh_permohonan->format('d/m/Y'),
                    $row->asnaf->nama,
                    $row->asnaf->no_ic,
                    $row->jenis_bantuan,
                    $row->kategori_bantuan,
                    number_format($row->jumlah_dipohon, 2),
                    $row->jumlah_diluluskan ? number_format($row->jumlah_diluluskan, 2) : '-',
                    $row->status,
                    $row->sebab_permohonan,
                    $row->tarikh_kelulusan ? $row->tarikh_kelulusan->format('d/m/Y') : '-',
                    $row->tarikh_penolakan ? $row->tarikh_penolakan->format('d/m/Y') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
