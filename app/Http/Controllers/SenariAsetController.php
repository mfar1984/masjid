<?php

namespace App\Http\Controllers;

use App\Models\SenariAset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SenariAsetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $query = SenariAset::with(['masjid', 'kategoriAset']);

        // WAJIB: Multi-Masjid Data Isolation
        if ($isSuperAdmin) {
            // Super Admin can see all aset
            // Filter by masjid if specified
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            // Admin Masjid can ONLY see aset from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $query->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no aset
                $query->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Filters
        if ($request->filled('kategori_aset_id')) {
            $query->where('kategori_aset_id', $request->kategori_aset_id);
        }

        if ($request->filled('status_aset')) {
            $query->where('status_aset', $request->status_aset);
        }

        if ($request->filled('kondisi_aset')) {
            $query->where('kondisi_aset', $request->kondisi_aset);
        }

        if ($request->filled('lokasi_semasa')) {
            $query->where('lokasi_semasa', $request->lokasi_semasa);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_aset', 'like', "%{$search}%")
                    ->orWhere('nama_aset', 'like', "%{$search}%")
                    ->orWhere('kod_aset', 'like', "%{$search}%")
                    ->orWhere('no_siri', 'like', "%{$search}%");
            });
        }

        $senariAset = $query->latest()->paginate(25);

        // For Super Admin: Get masjid list for filter dropdown
        $masjidList = null;
        if ($isSuperAdmin) {
            $masjidList = \App\Models\Masjid::orderBy('nama')->get();
        }

        // Stats - SEPARATE query for statistics (not affected by search/filter)
        $statsQuery = SenariAset::query();

        // Apply masjid isolation for stats (but NOT search/filter)
        if (!$isSuperAdmin) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalAset = (clone $statsQuery)->count();
        $aktifAset = (clone $statsQuery)->where('status_aset', 'Aktif')->count();
        $rosakAset = (clone $statsQuery)->where('status_aset', 'Rosak')->count();
        $nilaiTotal = (clone $statsQuery)->sum('harga_perolehan');

        $stats = [
            ['title' => 'Total Aset', 'value' => $totalAset, 'icon' => 'inventory_2', 'color' => 'blue'],
            ['title' => 'Aset Aktif', 'value' => $aktifAset, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Aset Rosak', 'value' => $rosakAset, 'icon' => 'warning', 'color' => 'red'],
            ['title' => 'Nilai Total', 'value' => 'RM ' . number_format($nilaiTotal, 2), 'icon' => 'payments', 'color' => 'purple'],
        ];

        // Get kategori for filter
        $kategoriList = KategoriAset::query();
        if (!$isSuperAdmin) {
            $kategoriList->where('masjid_id', $user->masjid_id);
        }
        $kategoriList = $kategoriList->aktif()->orderBy('urutan')->get();

        return view('senarai-aset.index', compact('senariAset', 'stats', 'kategoriList', 'isSuperAdmin', 'masjidList'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get kategori aset
        $kategoriAset = KategoriAset::where('masjid_id', $masjidId)
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('senarai-aset.create', compact('kategoriAset'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'kuantiti' => 'required|integer|min:1|max:1000',
            'kategori_aset_id' => 'required|exists:kategori_aset,id',
            'nama_aset' => 'required|max:255',
            'kod_aset_prefix' => 'required|max:50',
            'auto_generate_kod' => 'nullable',
            'jenis_aset' => 'nullable|max:255',
            'tarikh_perolehan' => 'required|date',
            'cara_perolehan' => 'required|in:Pembelian,Derma,Hibah,Wakaf,Pinjaman,Lain-lain',
            'pembekal' => 'nullable|max:255',
            'no_invois' => 'nullable|max:100',
            'harga_per_unit' => 'required|numeric|min:0',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'jenama' => 'nullable|max:255',
            'model' => 'nullable|max:255',
            'no_siri' => 'nullable|max:255',
            'warna' => 'nullable|max:100',
            'saiz' => 'nullable|max:100',
            'spesifikasi' => 'nullable|string',
            'lokasi_semasa' => 'required|max:255',
            'lokasi_terperinci' => 'nullable|string',
            'tempoh_jaminan' => 'nullable|integer|min:0',
            'no_polisi_insurans' => 'nullable|max:100',
            'syarikat_insurans' => 'nullable|max:255',
            'tarikh_tamat_insurans' => 'nullable|date',
            'status_aset' => 'required|in:Aktif,Dalam Penyelenggaraan,Rosak,Dilupuskan,Hilang,Dipinjam,Disewa',
            'kondisi_aset' => 'required|in:Baru,Baik,Sederhana,Teruk,Rosak',
            'catatan' => 'nullable|string',
            // File uploads (optional)
            'gambar_aset.*' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'invois_path' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'warranty_card_path' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'manual_path' => 'nullable|file|mimes:pdf|max:5120',
            'insurans_path' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'dokumen_lain.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        // WAJIB: Auto-assign masjid_id for data isolation
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            // Super Admin can specify masjid_id or leave null
            $validated['masjid_id'] = $request->masjid_id;
        }

        $kuantiti = $validated['kuantiti'];
        $masjidId = $validated['masjid_id'];
        $hargaPerUnit = $validated['harga_per_unit'];
        $autoGenerateKod = $request->boolean('auto_generate_kod');
        $kodAsetPrefix = $validated['kod_aset_prefix'] ?? '';

        // Handle file uploads (only once, will be shared by all assets if kuantiti > 1)
        $fileFields = ['invois_path', 'warranty_card_path', 'manual_path', 'insurans_path'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('aset', 'public');
            }
        }

        // Handle multiple file uploads
        if ($request->hasFile('gambar_aset')) {
            $gambarPaths = [];
            foreach ($request->file('gambar_aset') as $file) {
                $gambarPaths[] = $file->store('aset/gambar', 'public');
            }
            $validated['gambar_aset'] = json_encode($gambarPaths);
        }

        if ($request->hasFile('dokumen_lain')) {
            $dokumenPaths = [];
            foreach ($request->file('dokumen_lain') as $file) {
                $dokumenPaths[] = $file->store('aset/dokumen', 'public');
            }
            $validated['dokumen_lain'] = json_encode($dokumenPaths);
        }

        \DB::beginTransaction();
        try {
            $createdAssets = [];
            
            // Use user-provided prefix (e.g., PUTRA-KERUSI-2025-)
            // Ensure prefix ends with dash for proper numbering
            $kodPrefix = strtoupper(trim($kodAsetPrefix));
            if (!str_ends_with($kodPrefix, '-')) {
                $kodPrefix .= '-';
            }
            
            // Get starting number for kod_aset based on user prefix
            $kodStartNumber = SenariAset::getNextKodAsetNumber($masjidId, $kodPrefix);
            
            for ($i = 1; $i <= $kuantiti; $i++) {
                $asetData = $validated;
                
                // Generate unique kod_aset for each (this is now the primary identifier)
                $asetData['kod_aset'] = $kodPrefix . str_pad($kodStartNumber + $i - 1, 4, '0', STR_PAD_LEFT);
                
                // no_aset is same as kod_aset (for backward compatibility)
                $asetData['no_aset'] = $asetData['kod_aset'];
                
                // Set harga_perolehan per unit (each asset gets the per-unit price)
                $asetData['harga_perolehan'] = $hargaPerUnit;
                
                // Add number suffix to nama_aset if kuantiti > 1
                if ($kuantiti > 1) {
                    $asetData['nama_aset'] = $validated['nama_aset'] . " #{$i}";
                }
                
                $asetData['created_by'] = $user->id;
                $asetData['updated_by'] = $user->id;

                // Calculate tarikh_tamat_jaminan if tempoh_jaminan provided
                if ($request->filled('tempoh_jaminan') && $request->filled('tarikh_perolehan')) {
                    $asetData['tarikh_tamat_jaminan'] = \Carbon\Carbon::parse($request->tarikh_perolehan)
                        ->addMonths($request->tempoh_jaminan);
                }

                // Remove fields not in database
                unset($asetData['kuantiti']);
                unset($asetData['harga_per_unit']);
                unset($asetData['kod_aset_prefix']);
                unset($asetData['auto_generate_kod']);

                $aset = SenariAset::create($asetData);
                $createdAssets[] = $aset;
            }

            \DB::commit();
            
            $firstKod = $kodPrefix . str_pad($kodStartNumber, 4, '0', STR_PAD_LEFT);
            $lastKod = $kodPrefix . str_pad($kodStartNumber + $kuantiti - 1, 4, '0', STR_PAD_LEFT);
            
            $message = $kuantiti == 1 
                ? "Aset berjaya didaftarkan. Kod: {$firstKod}" 
                : "{$kuantiti} aset berjaya didaftarkan. Kod: {$firstKod} hingga {$lastKod}";
            
            return redirect()->route('senarai-aset.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambah aset: ' . $e->getMessage());
        }
    }

    public function show(SenariAset $senariAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $senariAset->load(['masjid', 'kategoriAset', 'pergerakanAset', 'createdBy', 'updatedBy']);

        return view('senarai-aset.show', compact('senariAset'));
    }

    public function edit(SenariAset $senariAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Get kategori aset
        $kategoriAset = KategoriAset::where('masjid_id', $senariAset->masjid_id)
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('senarai-aset.edit', compact('senariAset', 'kategoriAset'));
    }

    public function update(Request $request, SenariAset $senariAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $validated = $request->validate([
            'kategori_aset_id' => 'required|exists:kategori_aset,id',
            'nama_aset' => 'required|max:255',
            'kod_aset' => 'nullable|max:50',
            'jenis_aset' => 'nullable|max:255',
            'tarikh_perolehan' => 'required|date',
            'cara_perolehan' => 'required|in:Pembelian,Derma,Hibah,Wakaf,Pinjaman,Lain-lain',
            'pembekal' => 'nullable|max:255',
            'no_invois' => 'nullable|max:100',
            'harga_perolehan' => 'required|numeric|min:0',
            'jenama' => 'nullable|max:255',
            'model' => 'nullable|max:255',
            'no_siri' => 'nullable|max:255',
            'warna' => 'nullable|max:100',
            'saiz' => 'nullable|max:100',
            'spesifikasi' => 'nullable|string',
            'lokasi_semasa' => 'required|max:255',
            'lokasi_terperinci' => 'nullable|string',
            'tempoh_jaminan' => 'nullable|integer|min:0',
            'no_polisi_insurans' => 'nullable|max:100',
            'syarikat_insurans' => 'nullable|max:255',
            'tarikh_tamat_insurans' => 'nullable|date',
            'status_aset' => 'required|in:Aktif,Dalam Penyelenggaraan,Rosak,Dilupuskan,Hilang,Dipinjam,Disewa',
            'kondisi_aset' => 'required|in:Baru,Baik,Sederhana,Teruk,Rosak',
            'catatan' => 'nullable|string',
            // File uploads (optional)
            'gambar_aset.*' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'invois_path' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'warranty_card_path' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'manual_path' => 'nullable|file|mimes:pdf|max:5120',
            'insurans_path' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'dokumen_lain.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $validated['updated_by'] = $user->id;

        // Calculate tarikh_tamat_jaminan if tempoh_jaminan provided
        if ($request->filled('tempoh_jaminan') && $request->filled('tarikh_perolehan')) {
            $validated['tarikh_tamat_jaminan'] = \Carbon\Carbon::parse($request->tarikh_perolehan)
                ->addMonths($request->tempoh_jaminan);
        }

        // Handle file uploads
        $fileFields = ['invois_path', 'warranty_card_path', 'manual_path', 'insurans_path'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($senariAset->$field) {
                    Storage::disk('public')->delete($senariAset->$field);
                }
                $validated[$field] = $request->file($field)->store('aset', 'public');
            }
        }

        // Handle multiple file uploads - gambar_aset
        if ($request->hasFile('gambar_aset')) {
            // Delete old files if exists
            if ($senariAset->gambar_aset) {
                $oldGambar = json_decode($senariAset->gambar_aset, true) ?? [];
                foreach ($oldGambar as $oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $gambarPaths = [];
            foreach ($request->file('gambar_aset') as $file) {
                $gambarPaths[] = $file->store('aset/gambar', 'public');
            }
            $validated['gambar_aset'] = json_encode($gambarPaths);
        }

        // Handle multiple file uploads - dokumen_lain
        if ($request->hasFile('dokumen_lain')) {
            // Delete old files if exists
            if ($senariAset->dokumen_lain) {
                $oldDokumen = json_decode($senariAset->dokumen_lain, true) ?? [];
                foreach ($oldDokumen as $oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $dokumenPaths = [];
            foreach ($request->file('dokumen_lain') as $file) {
                $dokumenPaths[] = $file->store('aset/dokumen', 'public');
            }
            $validated['dokumen_lain'] = json_encode($dokumenPaths);
        }

        $senariAset->update($validated);

        return redirect()->route('senarai-aset.index')
            ->with('success', 'Aset berjaya dikemaskini.');
    }

    public function destroy(SenariAset $senariAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Check if aset has pergerakan
        if ($senariAset->pergerakanAset()->count() > 0) {
            return back()->with('error', 'Aset tidak boleh dipadam kerana mempunyai rekod pergerakan.');
        }

        $senariAset->update(['deleted_by' => $user->id]);
        $senariAset->delete();

        return redirect()->route('senarai-aset.index')
            ->with('success', 'Aset berjaya dipadam.');
    }
}
