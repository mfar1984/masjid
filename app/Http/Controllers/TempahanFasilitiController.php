<?php

namespace App\Http\Controllers;

use App\Models\TempahanFasiliti;
use App\Models\SenariFasiliti;
use App\Models\PembayaranSewa;
use App\Models\PergerakanAset;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TempahanFasilitiController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = TempahanFasiliti::with(['masjid', 'senariFasiliti']);

        // Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id ?? 0);
        }

        // Filters
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_tempahan', 'like', "%{$search}%")
                    ->orWhere('nama_penyewa', 'like', "%{$search}%");
            });
        }

        // Filter by status pemulangan
        if ($request->filled('status_pemulangan')) {
            $query->where('status_pemulangan', $request->status_pemulangan);
        }

        $tempahanFasiliti = $query->latest()->paginate(25);

        // Stats
        $statsQuery = TempahanFasiliti::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $stats = [
            [
                'title' => 'Total Tempahan',
                'value' => (clone $statsQuery)->count(),
                'icon' => 'event',
                'color' => 'blue'
            ],
            [
                'title' => 'Tempahan Baharu',
                'value' => (clone $statsQuery)->where('status_tempahan', 'Baharu')->count(),
                'icon' => 'fiber_new',
                'color' => 'blue'
            ],
            [
                'title' => 'Tempahan Lulus',
                'value' => (clone $statsQuery)->where('status_tempahan', 'Lulus')->count(),
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Belum Pulang',
                'value' => (clone $statsQuery)->where('status_tempahan', 'Lulus')->where('status_pemulangan', 'Belum Pulang')->count(),
                'icon' => 'pending',
                'color' => 'orange'
            ],
            [
                'title' => 'Lewat Pulang',
                'value' => (clone $statsQuery)->where('status_pemulangan', 'Lewat')->count(),
                'icon' => 'warning',
                'color' => 'red'
            ],
        ];

        $fasilitiList = SenariFasiliti::where('masjid_id', $user->masjid_id)->tersedia()->get();

        return view('tempahan-fasiliti.index', compact('tempahanFasiliti', 'stats', 'fasilitiList'));
    }

    public function create()
    {
        $user = Auth::user();
        $fasilitiList = SenariFasiliti::where('masjid_id', $user->masjid_id)->tersedia()->get();
        
        // Get fasiliti IDs that are currently under maintenance
        $fasilitiUnderMaintenance = $this->getFasilitiUnderMaintenance($user->masjid_id);
        
        return view('tempahan-fasiliti.create', compact('fasilitiList', 'fasilitiUnderMaintenance'));
    }
    
    /**
     * Get list of fasiliti IDs that are currently under maintenance
     */
    private function getFasilitiUnderMaintenance($masjidId)
    {
        return \App\Models\KerjaPenyelenggaraan::where('masjid_id', $masjidId)
            ->whereIn('status', ['Dirancang', 'Sedang Berjalan'])
            ->whereNotNull('senarai_fasiliti_id')
            ->pluck('senarai_fasiliti_id')
            ->unique()
            ->toArray();
    }

    /**
     * Check availability for specific fasiliti on date/time range (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $fasilitiId = $request->fasiliti_id;
        $tarikhMula = $request->tarikh_mula;
        $tarikhTamat = $request->tarikh_tamat;
        $excludeTempahanId = $request->exclude_tempahan_id;

        $fasiliti = SenariFasiliti::find($fasilitiId);
        
        if (!$fasiliti) {
            return response()->json(['error' => 'Fasiliti not found'], 404);
        }

        // Check if fasiliti is under maintenance
        $maintenanceInfo = $this->checkMaintenanceStatus($fasilitiId, $tarikhMula, $tarikhTamat);
        
        if ($maintenanceInfo['is_under_maintenance']) {
            return response()->json([
                'available' => 0,
                'total' => $fasiliti->kuantiti_total,
                'booked' => $fasiliti->kuantiti_total,
                'is_countable' => $fasiliti->is_countable,
                'nama_fasiliti' => $fasiliti->nama_fasiliti,
                'jenis_fasiliti' => $fasiliti->jenis_fasiliti,
                'is_under_maintenance' => true,
                'maintenance_info' => $maintenanceInfo['details'],
            ]);
        }

        $available = $fasiliti->checkAvailability($tarikhMula, $tarikhTamat, $excludeTempahanId);

        return response()->json([
            'available' => $available,
            'total' => $fasiliti->kuantiti_total,
            'booked' => $fasiliti->kuantiti_total - $available,
            'is_countable' => $fasiliti->is_countable,
            'nama_fasiliti' => $fasiliti->nama_fasiliti,
            'jenis_fasiliti' => $fasiliti->jenis_fasiliti,
            'is_under_maintenance' => false,
        ]);
    }
    
    /**
     * Check if fasiliti is under maintenance during the specified date range
     */
    private function checkMaintenanceStatus($fasilitiId, $tarikhMula, $tarikhTamat)
    {
        $maintenance = \App\Models\KerjaPenyelenggaraan::where('senarai_fasiliti_id', $fasilitiId)
            ->whereIn('status', ['Dirancang', 'Sedang Berjalan'])
            ->where(function ($query) use ($tarikhMula, $tarikhTamat) {
                // Check if maintenance period overlaps with booking period
                $query->where(function ($q) use ($tarikhMula, $tarikhTamat) {
                    $q->whereDate('tarikh_kerja', '<=', $tarikhTamat)
                      ->whereDate('tarikh_kerja', '>=', $tarikhMula);
                });
            })
            ->first();
        
        if ($maintenance) {
            return [
                'is_under_maintenance' => true,
                'details' => [
                    'no_kerja' => $maintenance->no_kerja,
                    'jenis_kerja' => $maintenance->jenis_kerja,
                    'tarikh_kerja' => $maintenance->tarikh_kerja->format('d/m/Y'),
                    'status' => $maintenance->status,
                    'penerangan' => $maintenance->penerangan_kerja,
                ],
            ];
        }
        
        return ['is_under_maintenance' => false, 'details' => null];
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.fasiliti_id' => 'required|exists:senarai_fasiliti,id',
            'items.*.quantity' => 'required|integer|min:1',
            'nama_penyewa' => 'required|max:255',
            'no_ic_penyewa' => 'required|size:12',
            'no_telefon_penyewa' => 'required|max:20',
            'emel_penyewa' => 'nullable|email|max:255',
            'alamat_penyewa_1' => 'required|max:255',
            'alamat_penyewa_2' => 'nullable|max:255',
            'poskod_penyewa' => 'required|max:10',
            'bandar_penyewa' => 'required|max:100',
            'negeri_penyewa' => 'required|max:100',
            'organisasi_penyewa' => 'nullable|max:255',
            'tarikh_tempahan' => 'required|date',
            'tarikh_mula' => 'required|date',
            'tarikh_tamat' => 'required|date|after:tarikh_mula',
            'tempoh_sewa' => 'required|integer|min:1',
            'unit_tempoh' => 'required|in:Jam,Separuh Hari,Hari',
            'tujuan_tempahan' => 'required|string',
            'jenis_acara' => 'nullable|max:255',
            'bilangan_jangka_peserta' => 'nullable|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'jumlah_bayaran' => 'required|numeric|min:0',
            // Lokasi Destinasi validation
            'is_lokasi_luaran' => 'nullable|boolean',
            'lokasi_destinasi' => 'nullable|max:255',
            'nama_tempat_luaran' => 'nullable|max:255',
            'alamat_luaran_1' => 'nullable|max:255',
            'alamat_luaran_2' => 'nullable|max:255',
            'poskod_luaran' => 'nullable|max:10',
            'bandar_luaran' => 'nullable|max:100',
            'negeri_luaran' => 'nullable|max:100',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $masjidId = $user->isSuperAdmin() ? $request->masjid_id : $user->masjid_id;
            
            // Create tempahan
            $tempahan = TempahanFasiliti::create([
                'masjid_id' => $masjidId,
                'no_tempahan' => TempahanFasiliti::generateNoTempahan($masjidId),
                'nama_penyewa' => $validated['nama_penyewa'],
                'no_ic_penyewa' => $validated['no_ic_penyewa'],
                'no_telefon_penyewa' => $validated['no_telefon_penyewa'],
                'emel_penyewa' => $validated['emel_penyewa'],
                'alamat_penyewa_1' => $validated['alamat_penyewa_1'],
                'alamat_penyewa_2' => $validated['alamat_penyewa_2'],
                'poskod_penyewa' => $validated['poskod_penyewa'],
                'bandar_penyewa' => $validated['bandar_penyewa'],
                'negeri_penyewa' => $validated['negeri_penyewa'],
                'organisasi_penyewa' => $validated['organisasi_penyewa'],
                'tarikh_tempahan' => $validated['tarikh_tempahan'],
                'tarikh_mula' => $validated['tarikh_mula'],
                'tarikh_tamat' => $validated['tarikh_tamat'],
                'tempoh_sewa' => $validated['tempoh_sewa'],
                'unit_tempoh' => $validated['unit_tempoh'],
                'tujuan_tempahan' => $validated['tujuan_tempahan'],
                'jenis_acara' => $validated['jenis_acara'],
                'bilangan_jangka_peserta' => $validated['bilangan_jangka_peserta'],
                'harga_sewa' => $validated['harga_sewa'],
                'deposit' => $validated['deposit'],
                'jumlah_bayaran' => $validated['jumlah_bayaran'],
                // Lokasi Destinasi
                'is_lokasi_luaran' => $request->boolean('is_lokasi_luaran'),
                'lokasi_destinasi' => $validated['lokasi_destinasi'] ?? null,
                'nama_tempat_luaran' => $validated['nama_tempat_luaran'] ?? null,
                'alamat_luaran_1' => $validated['alamat_luaran_1'] ?? null,
                'alamat_luaran_2' => $validated['alamat_luaran_2'] ?? null,
                'poskod_luaran' => $validated['poskod_luaran'] ?? null,
                'bandar_luaran' => $validated['bandar_luaran'] ?? null,
                'negeri_luaran' => $validated['negeri_luaran'] ?? null,
                'status_pemulangan' => 'Belum Pulang',
                'catatan' => $validated['catatan'],
                'status_tempahan' => 'Baharu',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Add items
            foreach ($validated['items'] as $item) {
                $fasiliti = SenariFasiliti::find($item['fasiliti_id']);
                $quantity = $item['quantity'];

                // Check if fasiliti is under maintenance
                $maintenanceInfo = $this->checkMaintenanceStatus($fasiliti->id, $validated['tarikh_mula'], $validated['tarikh_tamat']);
                if ($maintenanceInfo['is_under_maintenance']) {
                    $info = $maintenanceInfo['details'];
                    throw new \Exception("Fasiliti '{$fasiliti->nama_fasiliti}' sedang dalam penyelenggaraan ({$info['no_kerja']} - {$info['jenis_kerja']}) pada tarikh {$info['tarikh_kerja']}. Sila pilih tarikh lain atau fasiliti lain.");
                }

                // Check availability
                $available = $fasiliti->checkAvailability($validated['tarikh_mula'], $validated['tarikh_tamat']);
                if ($available < $quantity) {
                    throw new \Exception("Kuantiti tidak mencukupi untuk {$fasiliti->nama_fasiliti}. Tersedia: {$available}, Diminta: {$quantity}");
                }

                // Calculate price
                $hargaPerUnit = $fasiliti->getPriceByUnit($validated['unit_tempoh']);
                $subtotal = $hargaPerUnit * $quantity * $validated['tempoh_sewa'];

                // Create item
                \App\Models\TempahanFasilitiItem::create([
                    'tempahan_fasiliti_id' => $tempahan->id,
                    'senarai_fasiliti_id' => $fasiliti->id,
                    'quantity' => $quantity,
                    'harga_per_unit' => $hargaPerUnit,
                    'subtotal' => $subtotal,
                    'status_item' => 'Aktif',
                ]);
            }

            DB::commit();
            return redirect()->route('tempahan-fasiliti.index')
                ->with('success', 'Tempahan berjaya didaftarkan dengan ' . count($validated['items']) . ' item.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat tempahan: ' . $e->getMessage());
        }
    }

    public function show(TempahanFasiliti $tempahanFasiliti)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $tempahanFasiliti->masjid_id !== $user->masjid_id) {
            abort(403);
        }
        $tempahanFasiliti->load(['senariFasiliti', 'pembayaranSewa', 'items.senariFasiliti', 'activeItems.senariFasiliti']);
        return view('tempahan-fasiliti.show', compact('tempahanFasiliti'));
    }

    public function edit(TempahanFasiliti $tempahanFasiliti)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $tempahanFasiliti->masjid_id !== $user->masjid_id) {
            abort(403);
        }
        $fasilitiList = SenariFasiliti::where('masjid_id', $tempahanFasiliti->masjid_id)->tersedia()->get();
        return view('tempahan-fasiliti.edit', compact('tempahanFasiliti', 'fasilitiList'));
    }

    public function update(Request $request, TempahanFasiliti $tempahanFasiliti)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $tempahanFasiliti->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.fasiliti_id' => 'required|exists:senarai_fasiliti,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.item_id' => 'nullable|exists:tempahan_fasiliti_items,id',
            'nama_penyewa' => 'required|max:255',
            'no_ic_penyewa' => 'required|size:12',
            'no_telefon_penyewa' => 'required|max:20',
            'emel_penyewa' => 'nullable|email|max:255',
            'alamat_penyewa_1' => 'required|max:255',
            'alamat_penyewa_2' => 'nullable|max:255',
            'poskod_penyewa' => 'required|max:10',
            'bandar_penyewa' => 'required|max:100',
            'negeri_penyewa' => 'required|max:100',
            'organisasi_penyewa' => 'nullable|max:255',
            'tarikh_tempahan' => 'required|date',
            'tarikh_mula' => 'required|date',
            'tarikh_tamat' => 'required|date|after:tarikh_mula',
            'tempoh_sewa' => 'required|integer|min:1',
            'unit_tempoh' => 'required|in:Jam,Separuh Hari,Hari',
            'tujuan_tempahan' => 'required|string',
            'jenis_acara' => 'nullable|max:255',
            'bilangan_jangka_peserta' => 'nullable|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'jumlah_bayaran' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Update tempahan basic info
            $tempahanFasiliti->update([
                'nama_penyewa' => $validated['nama_penyewa'],
                'no_ic_penyewa' => $validated['no_ic_penyewa'],
                'no_telefon_penyewa' => $validated['no_telefon_penyewa'],
                'emel_penyewa' => $validated['emel_penyewa'],
                'alamat_penyewa_1' => $validated['alamat_penyewa_1'],
                'alamat_penyewa_2' => $validated['alamat_penyewa_2'],
                'poskod_penyewa' => $validated['poskod_penyewa'],
                'bandar_penyewa' => $validated['bandar_penyewa'],
                'negeri_penyewa' => $validated['negeri_penyewa'],
                'organisasi_penyewa' => $validated['organisasi_penyewa'],
                'tarikh_tempahan' => $validated['tarikh_tempahan'],
                'tarikh_mula' => $validated['tarikh_mula'],
                'tarikh_tamat' => $validated['tarikh_tamat'],
                'tempoh_sewa' => $validated['tempoh_sewa'],
                'unit_tempoh' => $validated['unit_tempoh'],
                'tujuan_tempahan' => $validated['tujuan_tempahan'],
                'jenis_acara' => $validated['jenis_acara'],
                'bilangan_jangka_peserta' => $validated['bilangan_jangka_peserta'],
                'harga_sewa' => $validated['harga_sewa'],
                'deposit' => $validated['deposit'],
                'jumlah_bayaran' => $validated['jumlah_bayaran'],
                'catatan' => $validated['catatan'],
                'updated_by' => $user->id,
            ]);

            // Track existing item IDs
            $existingItemIds = [];

            // Update or create items
            foreach ($validated['items'] as $itemData) {
                $fasiliti = SenariFasiliti::find($itemData['fasiliti_id']);
                $quantity = $itemData['quantity'];

                // Check availability (exclude current tempahan)
                $available = $fasiliti->checkAvailability($validated['tarikh_mula'], $validated['tarikh_tamat'], $tempahanFasiliti->id);
                if ($available < $quantity) {
                    throw new \Exception("Kuantiti tidak mencukupi untuk {$fasiliti->nama_fasiliti}. Tersedia: {$available}, Diminta: {$quantity}");
                }

                // Calculate price
                $hargaPerUnit = $fasiliti->getPriceByUnit($validated['unit_tempoh']);
                $subtotal = $hargaPerUnit * $quantity * $validated['tempoh_sewa'];

                if (isset($itemData['item_id']) && $itemData['item_id']) {
                    // Update existing item
                    $item = \App\Models\TempahanFasilitiItem::find($itemData['item_id']);
                    if ($item && $item->tempahan_fasiliti_id == $tempahanFasiliti->id) {
                        $item->update([
                            'senarai_fasiliti_id' => $fasiliti->id,
                            'quantity' => $quantity,
                            'harga_per_unit' => $hargaPerUnit,
                            'subtotal' => $subtotal,
                        ]);
                        $existingItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = \App\Models\TempahanFasilitiItem::create([
                        'tempahan_fasiliti_id' => $tempahanFasiliti->id,
                        'senarai_fasiliti_id' => $fasiliti->id,
                        'quantity' => $quantity,
                        'harga_per_unit' => $hargaPerUnit,
                        'subtotal' => $subtotal,
                        'status_item' => 'Aktif',
                    ]);
                    $existingItemIds[] = $newItem->id;
                }
            }

            // Delete items that were removed
            \App\Models\TempahanFasilitiItem::where('tempahan_fasiliti_id', $tempahanFasiliti->id)
                ->whereNotIn('id', $existingItemIds)
                ->delete();

            DB::commit();
            return redirect()->route('tempahan-fasiliti.index')
                ->with('success', 'Tempahan berjaya dikemaskini dengan ' . count($validated['items']) . ' item.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mengemas kini tempahan: ' . $e->getMessage());
        }
    }

    public function destroy(TempahanFasiliti $tempahanFasiliti)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $tempahanFasiliti->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        if ($tempahanFasiliti->status_tempahan === 'Lulus') {
            return back()->with('error', 'Tempahan yang telah diluluskan tidak boleh dipadam.');
        }

        $tempahanFasiliti->update(['deleted_by' => $user->id]);
        $tempahanFasiliti->delete();

        return redirect()->route('tempahan-fasiliti.index')
            ->with('success', 'Tempahan berjaya dipadam.');
    }


    // Workflow Actions
    public function semak(Request $request, $id)
    {
        $tempahan = TempahanFasiliti::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $tempahan->update([
            'status_tempahan' => 'Dalam Semakan',
            'disemak_oleh' => $user->id,
            'tarikh_disemak' => now(),
            'catatan_semakan' => $request->catatan_semakan,
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Tempahan telah disemak.');
    }

    public function lulus(Request $request, $id)
    {
        $tempahan = TempahanFasiliti::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Update tempahan status
            $tempahan->update([
                'status_tempahan' => 'Lulus',
                'diluluskan_oleh' => $user->id,
                'tarikh_diluluskan' => now(),
                'catatan_kelulusan' => $request->catatan_kelulusan,
                'status_pemulangan' => 'Belum Pulang',
                'updated_by' => $user->id,
            ]);

            // Auto-create Pembayaran Sewa (use first item's fasiliti for reference)
            $firstItem = $tempahan->activeItems->first();
            PembayaranSewa::create([
                'masjid_id' => $tempahan->masjid_id,
                'no_pembayaran' => PembayaranSewa::generateNoPembayaran($tempahan->masjid_id),
                'tempahan_fasiliti_id' => $tempahan->id,
                'senarai_fasiliti_id' => $firstItem ? $firstItem->senarai_fasiliti_id : null,
                'tarikh_pembayaran' => now(),
                'jumlah_sewa' => $tempahan->harga_sewa,
                'jumlah_deposit' => $tempahan->deposit,
                'jumlah_bayaran' => $tempahan->jumlah_bayaran,
                'kaedah_bayaran' => 'Tunai',
                'status_pembayaran' => 'Belum Bayar',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Auto-create Pergerakan Aset using InventoryService
            $pergerakanRecords = $this->inventoryService->createPergerakanFromTempahan($tempahan);

            // Update aset status for items with linked aset
            foreach ($tempahan->activeItems as $item) {
                $fasiliti = $item->senariFasiliti;
                if ($fasiliti && $fasiliti->senarai_aset_id && $fasiliti->senariAset) {
                    $fasiliti->senariAset->update([
                        'status_aset' => 'Disewa',
                        'lokasi_semasa' => 'Disewa - ' . $tempahan->nama_penyewa,
                    ]);
                }
            }

            DB::commit();
            $pergerakanCount = $pergerakanRecords->count();
            $message = 'Tempahan telah diluluskan dengan ' . $tempahan->activeItems->count() . ' item.';
            if ($pergerakanCount > 0) {
                $message .= ' ' . $pergerakanCount . ' rekod pergerakan aset dicipta.';
            }
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal meluluskan tempahan: ' . $e->getMessage());
        }
    }

    public function tolak(Request $request, $id)
    {
        $tempahan = TempahanFasiliti::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $tempahan->update([
            'status_tempahan' => 'Ditolak',
            'ditolak_oleh' => $user->id,
            'tarikh_ditolak' => now(),
            'sebab_tolak' => $request->sebab_tolak,
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Tempahan telah ditolak.');
    }

    public function batal(Request $request, $id)
    {
        $tempahan = TempahanFasiliti::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $tempahan->update([
            'status_tempahan' => 'Dibatalkan',
            'dibatalkan_oleh' => $user->id,
            'tarikh_dibatalkan' => now(),
            'sebab_batal' => $request->sebab_batal,
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Tempahan telah dibatalkan.');
    }

    public function selesai(Request $request, $id)
    {
        $tempahan = TempahanFasiliti::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $tempahan->update([
                'status_tempahan' => 'Selesai',
                'updated_by' => $user->id,
            ]);

            // Update Pergerakan Aset for all items (if exists)
            foreach ($tempahan->activeItems as $item) {
                $fasiliti = $item->senariFasiliti;
                if ($fasiliti->jenis_fasiliti === 'Aset' && $fasiliti->senarai_aset_id) {
                    $pergerakan = PergerakanAset::where('senarai_aset_id', $fasiliti->senarai_aset_id)
                        ->where('nama_peminjam', $tempahan->nama_penyewa)
                        ->where('status_pulangan', 'Belum Pulang')
                        ->first();

                    if ($pergerakan) {
                        $pergerakan->update([
                            'tarikh_sebenar_pulangan' => now(),
                            'status_pulangan' => 'Sudah Pulang',
                            'kondisi_selepas' => 'Baik',
                        ]);

                        // Update aset status back to Aktif
                        $fasiliti->senariAset->update([
                            'status_aset' => 'Aktif',
                            'lokasi_semasa' => $pergerakan->lokasi_asal,
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Tempahan telah selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menandakan selesai: ' . $e->getMessage());
        }
    }

    /**
     * Process return for tempahan (bulk return - all items at once)
     */
    public function pulang(Request $request, $id)
    {
        $tempahan = TempahanFasiliti::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        if ($tempahan->status_tempahan !== 'Lulus') {
            return back()->with('error', 'Hanya tempahan yang diluluskan boleh dipulangkan.');
        }

        if ($tempahan->status_pemulangan === 'Sudah Pulang') {
            return back()->with('error', 'Tempahan ini sudah dipulangkan.');
        }

        $request->validate([
            'kondisi_selepas' => 'required|in:Baik,Sederhana,Teruk,Rosak',
            'catatan_pulangan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Use bulk return from InventoryService
            $result = $this->inventoryService->processBulkReturn(
                $tempahan,
                $request->kondisi_selepas,
                $request->catatan_pulangan
            );

            if (!$result['success']) {
                throw new \Exception($result['message']);
            }

            // Update aset status back to Aktif
            foreach ($tempahan->activeItems as $item) {
                $fasiliti = $item->senariFasiliti;
                if ($fasiliti && $fasiliti->senarai_aset_id && $fasiliti->senariAset) {
                    $fasiliti->senariAset->update([
                        'status_aset' => 'Aktif',
                        'kondisi_aset' => $request->kondisi_selepas,
                        'lokasi_semasa' => 'Masjid',
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', $result['message'] . '. Tempahan telah selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pemulangan: ' . $e->getMessage());
        }
    }

    /**
     * Get return status for tempahan (AJAX)
     */
    public function getReturnStatus($id)
    {
        $tempahan = TempahanFasiliti::with(['activeItems.senariFasiliti', 'pergerakanAset'])->findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $items = $tempahan->activeItems->map(function ($item) {
            $pergerakan = $item->pergerakanAset;
            return [
                'id' => $item->id,
                'nama_fasiliti' => $item->senariFasiliti->nama_fasiliti ?? '-',
                'kuantiti_asal' => $item->quantity,
                'kuantiti_dipulangkan' => $item->kuantiti_dipulangkan,
                'kuantiti_hilang' => $item->kuantiti_hilang,
                'baki_belum_pulang' => $item->quantity - $item->kuantiti_dipulangkan,
                'status_pulangan' => $item->status_pulangan,
                'pergerakan_id' => $pergerakan ? $pergerakan->id : null,
                'no_pergerakan' => $pergerakan ? $pergerakan->no_pergerakan : null,
            ];
        });

        return response()->json([
            'no_tempahan' => $tempahan->no_tempahan,
            'nama_penyewa' => $tempahan->nama_penyewa,
            'status_pemulangan' => $tempahan->status_pemulangan,
            'total_items' => $items->count(),
            'items' => $items,
        ]);
    }

    /**
     * Cancel individual item in a tempahan
     */
    public function batalItem(Request $request, $tempahanId, $itemId)
    {
        $tempahan = TempahanFasiliti::findOrFail($tempahanId);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $tempahan->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $item = \App\Models\TempahanFasilitiItem::where('id', $itemId)
            ->where('tempahan_fasiliti_id', $tempahanId)
            ->firstOrFail();

        if ($item->status_item === 'Dibatalkan') {
            return back()->with('error', 'Item ini sudah dibatalkan.');
        }

        $request->validate([
            'sebab_batal_item' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $item->cancelItem($user->id, $request->sebab_batal_item);

            DB::commit();
            return back()->with('success', 'Item berjaya dibatalkan. Total tempahan telah dikemas kini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan item: ' . $e->getMessage());
        }
    }
}
