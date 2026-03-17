<?php

namespace App\Http\Controllers;

use App\Models\PergerakanAset;
use App\Models\SenariAset;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PergerakanAsetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PergerakanAset::with(['masjid', 'senariAset']);

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all pergerakan
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see pergerakan from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $query->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no pergerakan
                $query->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Filters
        if ($request->filled('senarai_aset_id')) {
            $query->where('senarai_aset_id', $request->senarai_aset_id);
        }

        if ($request->filled('jenis_pergerakan')) {
            $query->where('jenis_pergerakan', $request->jenis_pergerakan);
        }

        if ($request->filled('status_pulangan')) {
            $query->where('status_pulangan', $request->status_pulangan);
        }

        if ($request->filled('tarikh_dari')) {
            $query->whereDate('tarikh_pergerakan', '>=', $request->tarikh_dari);
        }

        if ($request->filled('tarikh_hingga')) {
            $query->whereDate('tarikh_pergerakan', '<=', $request->tarikh_hingga);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pergerakan', 'like', "%{$search}%")
                    ->orWhere('nama_peminjam', 'like', "%{$search}%");
            });
        }

        $pergerakanAset = $query->latest()->paginate(25);

        // Stats - SEPARATE query for statistics (not affected by search/filter)
        $statsQuery = PergerakanAset::query();

        // Apply masjid isolation for stats (but NOT search/filter)
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalPergerakan = (clone $statsQuery)->count();
        $belumPulang = (clone $statsQuery)->where('status_pulangan', 'Belum Pulang')->count();
        $sebahagian = (clone $statsQuery)->where('status_pulangan', 'Sebahagian')->count();
        $lewatPulang = (clone $statsQuery)->where('status_pulangan', 'Lewat')->count();
        $hilang = (clone $statsQuery)->where('status_pulangan', 'Hilang')->count();

        $stats = [
            ['title' => 'Total Pergerakan', 'value' => $totalPergerakan, 'icon' => 'swap_horiz', 'color' => 'blue'],
            ['title' => 'Belum Pulang', 'value' => $belumPulang, 'icon' => 'schedule', 'color' => 'orange'],
            ['title' => 'Sebahagian', 'value' => $sebahagian, 'icon' => 'hourglass_empty', 'color' => 'yellow'],
            ['title' => 'Lewat Pulang', 'value' => $lewatPulang, 'icon' => 'warning', 'color' => 'red'],
        ];

        // Get aset list for filter
        $asetList = SenariAset::where('masjid_id', $user->masjid_id)
            ->aktif()
            ->orderBy('nama_aset')
            ->get();

        return view('pergerakan-aset.index', compact('pergerakanAset', 'stats', 'asetList'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get aset list
        $senariAset = SenariAset::where('masjid_id', $masjidId)
            ->aktif()
            ->with('kategoriAset')
            ->orderBy('nama_aset')
            ->get();

        return view('pergerakan-aset.create', compact('senariAset'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'senarai_aset_id' => 'required|exists:senarai_aset,id',
            'tarikh_pergerakan' => 'required|date',
            'jenis_pergerakan' => 'required|in:Pemindahan Dalaman,Pemindahan Luaran,Pinjaman,Sewa,Penyelenggaraan,Pulangan',
            'is_lokasi_luaran' => 'required|boolean',
            'lokasi_destinasi' => 'required_if:is_lokasi_luaran,0|nullable|max:255',
            'nama_tempat_luaran' => 'required_if:is_lokasi_luaran,1|nullable|max:255',
            'alamat_luaran_1' => 'required_if:is_lokasi_luaran,1|nullable|max:255',
            'alamat_luaran_2' => 'nullable|max:255',
            'poskod_luaran' => 'required_if:is_lokasi_luaran,1|nullable|size:5',
            'bandar_luaran' => 'required_if:is_lokasi_luaran,1|nullable|max:100',
            'negeri_luaran' => 'required_if:is_lokasi_luaran,1|nullable|max:100',
            'nama_peminjam' => 'nullable|max:255',
            'no_ic_peminjam' => 'nullable|size:12',
            'no_telefon_peminjam' => 'nullable|max:20',
            'organisasi_peminjam' => 'nullable|max:255',
            'tarikh_jangka_pulangan' => 'nullable|date|after:tarikh_pergerakan',
            'kondisi_sebelum' => 'required|in:Baru,Baik,Sederhana,Teruk,Rosak',
            'sebab_pergerakan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        // Get aset info
        $aset = SenariAset::findOrFail($request->senarai_aset_id);
        
        // WAJIB: Auto-assign masjid_id for data isolation
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            // Super Admin can specify masjid_id or leave null
            $validated['masjid_id'] = $request->masjid_id;
        }

        $validated['no_pergerakan'] = PergerakanAset::generateNoPergerakan($validated['masjid_id']);
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;
        $validated['lokasi_asal'] = $aset->lokasi_semasa;
        
        // Set require_approval if lokasi luaran
        if ($request->is_lokasi_luaran) {
            $validated['require_approval'] = true;
        }

        DB::beginTransaction();
        try {
            $pergerakan = PergerakanAset::create($validated);

            // Update aset status if not require approval
            if (!$request->is_lokasi_luaran) {
                $aset->update([
                    'lokasi_semasa' => $request->lokasi_destinasi,
                    'updated_by' => $user->id,
                ]);
            }

            DB::commit();

            return redirect()->route('pergerakan-aset.index')
                ->with('success', 'Pergerakan aset berjaya direkodkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ralat: ' . $e->getMessage());
        }
    }

    public function show(PergerakanAset $pergerakanAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakanAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $pergerakanAset->load(['masjid', 'senariAset.kategoriAset', 'diluluskanOleh', 'createdBy', 'updatedBy']);

        return view('pergerakan-aset.show', compact('pergerakanAset'));
    }

    public function edit(PergerakanAset $pergerakanAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakanAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Get aset list
        $senariAset = SenariAset::where('masjid_id', $pergerakanAset->masjid_id)
            ->aktif()
            ->with('kategoriAset')
            ->orderBy('nama_aset')
            ->get();

        return view('pergerakan-aset.edit', compact('pergerakanAset', 'senariAset'));
    }

    public function update(Request $request, PergerakanAset $pergerakanAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakanAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $validated = $request->validate([
            'tarikh_pergerakan' => 'required|date',
            'jenis_pergerakan' => 'required|in:Pemindahan Dalaman,Pemindahan Luaran,Pinjaman,Sewa,Penyelenggaraan,Pulangan',
            'is_lokasi_luaran' => 'required|boolean',
            'lokasi_destinasi' => 'required_if:is_lokasi_luaran,0|nullable|max:255',
            'nama_tempat_luaran' => 'required_if:is_lokasi_luaran,1|nullable|max:255',
            'alamat_luaran_1' => 'required_if:is_lokasi_luaran,1|nullable|max:255',
            'alamat_luaran_2' => 'nullable|max:255',
            'poskod_luaran' => 'required_if:is_lokasi_luaran,1|nullable|size:5',
            'bandar_luaran' => 'required_if:is_lokasi_luaran,1|nullable|max:100',
            'negeri_luaran' => 'required_if:is_lokasi_luaran,1|nullable|max:100',
            'nama_peminjam' => 'nullable|max:255',
            'no_ic_peminjam' => 'nullable|size:12',
            'no_telefon_peminjam' => 'nullable|max:20',
            'organisasi_peminjam' => 'nullable|max:255',
            'tarikh_jangka_pulangan' => 'nullable|date|after:tarikh_pergerakan',
            'kondisi_selepas' => 'nullable|in:Baru,Baik,Sederhana,Teruk,Rosak',
            'sebab_pergerakan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $pergerakanAset->update($validated);

        return redirect()->route('pergerakan-aset.index')
            ->with('success', 'Pergerakan aset berjaya dikemaskini.');
    }

    public function destroy(PergerakanAset $pergerakanAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakanAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $pergerakanAset->update(['deleted_by' => $user->id]);
        $pergerakanAset->delete();

        return redirect()->route('pergerakan-aset.index')
            ->with('success', 'Pergerakan aset berjaya dipadam.');
    }

    // Workflow Actions
    public function lulus(Request $request, $id)
    {
        $user = Auth::user();
        $pergerakan = PergerakanAset::findOrFail($id);

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakan->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $validated = $request->validate([
            'catatan_kelulusan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pergerakan->update([
                'diluluskan_oleh' => $user->id,
                'tarikh_diluluskan' => now(),
                'catatan_kelulusan' => $validated['catatan_kelulusan'] ?? null,
                'updated_by' => $user->id,
            ]);

            // Update aset location if approved
            $aset = $pergerakan->senariAset;
            if ($pergerakan->is_lokasi_luaran) {
                $aset->update([
                    'lokasi_semasa' => $pergerakan->nama_tempat_luaran,
                    'status_aset' => in_array($pergerakan->jenis_pergerakan, ['Pinjaman', 'Sewa']) 
                        ? ($pergerakan->jenis_pergerakan === 'Pinjaman' ? 'Dipinjam' : 'Disewa')
                        : $aset->status_aset,
                    'updated_by' => $user->id,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Pergerakan aset berjaya diluluskan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ralat: ' . $e->getMessage());
        }
    }

    public function pulang(Request $request, $id)
    {
        $user = Auth::user();
        $pergerakan = PergerakanAset::findOrFail($id);

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakan->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $validated = $request->validate([
            'kondisi_selepas' => 'required|in:Baru,Baik,Sederhana,Teruk,Rosak',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pergerakan->update([
                'tarikh_sebenar_pulangan' => now(),
                'status_pulangan' => 'Sudah Pulang',
                'kondisi_selepas' => $validated['kondisi_selepas'],
                'catatan' => $validated['catatan'] ?? $pergerakan->catatan,
                'updated_by' => $user->id,
            ]);

            // Update aset back to original location and status
            $aset = $pergerakan->senariAset;
            $aset->update([
                'lokasi_semasa' => $pergerakan->lokasi_asal,
                'kondisi_aset' => $validated['kondisi_selepas'],
                'status_aset' => 'Aktif',
                'updated_by' => $user->id,
            ]);

            DB::commit();

            return back()->with('success', 'Aset berjaya ditandakan sebagai pulang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ralat: ' . $e->getMessage());
        }
    }

    public function lewat(Request $request, $id)
    {
        $user = Auth::user();
        $pergerakan = PergerakanAset::findOrFail($id);

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakan->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $pergerakan->update([
            'status_pulangan' => 'Lewat',
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Aset ditandakan sebagai lewat pulang.');
    }

    public function hilang(Request $request, $id)
    {
        $user = Auth::user();
        $pergerakan = PergerakanAset::findOrFail($id);

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakan->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pergerakan->update([
                'status_pulangan' => 'Hilang',
                'catatan' => $validated['catatan'] ?? $pergerakan->catatan,
                'updated_by' => $user->id,
            ]);

            // Update aset status to Hilang
            $aset = $pergerakan->senariAset;
            $aset->update([
                'status_aset' => 'Hilang',
                'updated_by' => $user->id,
            ]);

            DB::commit();

            return back()->with('success', 'Aset ditandakan sebagai hilang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ralat: ' . $e->getMessage());
        }
    }

    /**
     * Process partial return for pergerakan aset
     */
    public function pulangSebahagian(Request $request, $id)
    {
        $user = Auth::user();
        $pergerakan = PergerakanAset::findOrFail($id);

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakan->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Calculate max allowed
        $bakiBelumPulang = $pergerakan->kuantiti - $pergerakan->kuantiti_dipulangkan;

        $validated = $request->validate([
            'kuantiti_pulang' => "required|integer|min:1|max:{$bakiBelumPulang}",
            'kondisi_selepas' => 'required|in:Baru,Baik,Sederhana,Teruk,Rosak',
            'catatan' => 'nullable|string',
            'selesaikan' => 'nullable|boolean',
        ]);

        $inventoryService = new InventoryService();
        $result = $inventoryService->processPartialReturn(
            $pergerakan,
            $validated['kuantiti_pulang'],
            $validated['kondisi_selepas'],
            $validated['catatan'] ?? null,
            $validated['selesaikan'] ?? false
        );

        if ($result['success']) {
            $message = $result['message'];
            if ($result['transaksi_id']) {
                $message .= '. Transaksi ganti rugi telah dicipta.';
            }
            return back()->with('success', $message);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Get return stats for modal (AJAX)
     */
    public function getReturnStats($id)
    {
        $user = Auth::user();
        $pergerakan = PergerakanAset::with('senariAset')->findOrFail($id);

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($pergerakan->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $inventoryService = new InventoryService();
        $stats = $inventoryService->getReturnStats($pergerakan);
        $stats['nama_aset'] = $pergerakan->senariAset->nama_aset ?? '-';
        $stats['no_pergerakan'] = $pergerakan->no_pergerakan;
        $stats['nama_peminjam'] = $pergerakan->nama_peminjam;

        return response()->json($stats);
    }
}
