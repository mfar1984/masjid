<?php

namespace App\Http\Controllers;

use App\Models\PergerakanAset;
use App\Models\SenariAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemindahanAsetController extends Controller
{
    /**
     * Display a listing of pemindahan aset.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PergerakanAset::with(['masjid', 'senariAset'])
            ->whereIn('jenis_pergerakan', ['Pemindahan Dalaman', 'Pemindahan Luaran']);

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('senarai_aset_id')) {
            $query->where('senarai_aset_id', $request->senarai_aset_id);
        }

        if ($request->filled('jenis_pemindahan')) {
            $query->where('jenis_pergerakan', $request->jenis_pemindahan);
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
                    ->orWhere('lokasi_asal', 'like', "%{$search}%")
                    ->orWhere('lokasi_destinasi', 'like', "%{$search}%");
            });
        }

        $pemindahanAset = $query->latest()->paginate(25);

        // Stats
        $statsQuery = PergerakanAset::whereIn('jenis_pergerakan', ['Pemindahan Dalaman', 'Pemindahan Luaran']);
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalPemindahan = (clone $statsQuery)->count();
        $pemindahanDalaman = (clone $statsQuery)->where('jenis_pergerakan', 'Pemindahan Dalaman')->count();
        $pemindahanLuaran = (clone $statsQuery)->where('jenis_pergerakan', 'Pemindahan Luaran')->count();
        $bulanIni = (clone $statsQuery)->whereMonth('tarikh_pergerakan', now()->month)
            ->whereYear('tarikh_pergerakan', now()->year)->count();

        $stats = [
            ['title' => 'Jumlah Pemindahan', 'value' => $totalPemindahan, 'icon' => 'swap_horiz', 'color' => 'blue'],
            ['title' => 'Dalaman', 'value' => $pemindahanDalaman, 'icon' => 'home', 'color' => 'green'],
            ['title' => 'Luaran', 'value' => $pemindahanLuaran, 'icon' => 'location_on', 'color' => 'orange'],
            ['title' => 'Bulan Ini', 'value' => $bulanIni, 'icon' => 'calendar_today', 'color' => 'purple'],
        ];

        // Get aset list for filter
        $asetList = SenariAset::where('masjid_id', $user->masjid_id)
            ->aktif()
            ->orderBy('nama_aset')
            ->get();

        return view('pemindahan-aset.index', compact('pemindahanAset', 'stats', 'asetList'));
    }

    /**
     * Show the form for creating a new pemindahan.
     */
    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $senariAset = SenariAset::where('masjid_id', $masjidId)
            ->aktif()
            ->with('kategoriAset')
            ->orderBy('nama_aset')
            ->get();

        // Get unique locations from existing aset
        $lokasiList = SenariAset::where('masjid_id', $masjidId)
            ->whereNotNull('lokasi_semasa')
            ->distinct()
            ->pluck('lokasi_semasa')
            ->filter()
            ->values();

        return view('pemindahan-aset.create', compact('senariAset', 'lokasiList'));
    }

    /**
     * Store a newly created pemindahan.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'senarai_aset_id' => 'required|exists:senarai_aset,id',
            'tarikh_pergerakan' => 'required|date',
            'jenis_pergerakan' => 'required|in:Pemindahan Dalaman,Pemindahan Luaran',
            'lokasi_destinasi' => 'required|max:255',
            'sebab_pergerakan' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Get aset info
        $aset = SenariAset::findOrFail($request->senarai_aset_id);
        
        $masjidId = $user->isSuperAdmin() ? ($request->masjid_id ?? $aset->masjid_id) : $user->masjid_id;

        DB::beginTransaction();
        try {
            $pemindahan = PergerakanAset::create([
                'masjid_id' => $masjidId,
                'no_pergerakan' => PergerakanAset::generateNoPergerakan($masjidId),
                'senarai_aset_id' => $validated['senarai_aset_id'],
                'tarikh_pergerakan' => $validated['tarikh_pergerakan'],
                'jenis_pergerakan' => $validated['jenis_pergerakan'],
                'lokasi_asal' => $aset->lokasi_semasa,
                'lokasi_destinasi' => $validated['lokasi_destinasi'],
                'is_lokasi_luaran' => $validated['jenis_pergerakan'] === 'Pemindahan Luaran',
                'kondisi_sebelum' => $aset->kondisi_aset ?? 'Baik',
                'kondisi_selepas' => $aset->kondisi_aset ?? 'Baik',
                'status_pulangan' => 'Tidak Berkaitan',
                'sebab_pergerakan' => $validated['sebab_pergerakan'],
                'catatan' => $validated['catatan'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Update aset location immediately (pemindahan doesn't need return)
            $aset->update([
                'lokasi_semasa' => $validated['lokasi_destinasi'],
                'updated_by' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('pemindahan-aset.index')
                ->with('success', 'Pemindahan aset berjaya direkodkan. Lokasi aset telah dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ralat: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified pemindahan.
     */
    public function show(PergerakanAset $pemindahanAset)
    {
        $user = Auth::user();

        // Data isolation check
        if (!$user->isSuperAdmin() && $pemindahanAset->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $pemindahanAset->load(['masjid', 'senariAset.kategoriAset', 'createdBy', 'updatedBy']);

        return view('pemindahan-aset.show', compact('pemindahanAset'));
    }

    /**
     * Show the form for editing the specified pemindahan.
     */
    public function edit(PergerakanAset $pemindahanAset)
    {
        $user = Auth::user();

        // Data isolation check
        if (!$user->isSuperAdmin() && $pemindahanAset->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $senariAset = SenariAset::where('masjid_id', $pemindahanAset->masjid_id)
            ->aktif()
            ->with('kategoriAset')
            ->orderBy('nama_aset')
            ->get();

        $lokasiList = SenariAset::where('masjid_id', $pemindahanAset->masjid_id)
            ->whereNotNull('lokasi_semasa')
            ->distinct()
            ->pluck('lokasi_semasa')
            ->filter()
            ->values();

        return view('pemindahan-aset.edit', compact('pemindahanAset', 'senariAset', 'lokasiList'));
    }

    /**
     * Update the specified pemindahan.
     */
    public function update(Request $request, PergerakanAset $pemindahanAset)
    {
        $user = Auth::user();

        // Data isolation check
        if (!$user->isSuperAdmin() && $pemindahanAset->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'tarikh_pergerakan' => 'required|date',
            'jenis_pergerakan' => 'required|in:Pemindahan Dalaman,Pemindahan Luaran',
            'lokasi_destinasi' => 'required|max:255',
            'sebab_pergerakan' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $validated['is_lokasi_luaran'] = $validated['jenis_pergerakan'] === 'Pemindahan Luaran';
        $validated['updated_by'] = $user->id;

        DB::beginTransaction();
        try {
            // If lokasi_destinasi changed, update aset location too
            if ($pemindahanAset->lokasi_destinasi !== $validated['lokasi_destinasi']) {
                $aset = $pemindahanAset->senariAset;
                if ($aset) {
                    $aset->update([
                        'lokasi_semasa' => $validated['lokasi_destinasi'],
                        'updated_by' => $user->id,
                    ]);
                }
            }

            $pemindahanAset->update($validated);

            DB::commit();

            return redirect()->route('pemindahan-aset.index')
                ->with('success', 'Pemindahan aset berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ralat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified pemindahan.
     */
    public function destroy(PergerakanAset $pemindahanAset)
    {
        $user = Auth::user();

        // Data isolation check
        if (!$user->isSuperAdmin() && $pemindahanAset->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $pemindahanAset->update(['deleted_by' => $user->id]);
        $pemindahanAset->delete();

        return redirect()->route('pemindahan-aset.index')
            ->with('success', 'Rekod pemindahan aset berjaya dipadam.');
    }
}
