<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriAsetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $query = KategoriAset::with('masjid')->withCount('senariAset');

        // WAJIB: Multi-Masjid Data Isolation
        if ($isSuperAdmin) {
            // Super Admin can see all kategori
            // Filter by masjid if specified
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            // Admin Masjid can ONLY see kategori from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $query->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no kategori
                $query->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Filters
        if ($request->filled('jenis_kategori')) {
            $query->where('jenis_kategori', $request->jenis_kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kod_kategori', 'like', "%{$search}%")
                    ->orWhere('nama_kategori', 'like', "%{$search}%")
                    ->orWhereHas('masjid', function ($masjidQuery) use ($search) {
                        $masjidQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $kategoriAset = $query->orderBy('urutan')->orderBy('nama_kategori')->paginate(25);

        // For Super Admin: Get masjid list for filter dropdown
        $masjidList = null;
        if ($isSuperAdmin) {
            $masjidList = \App\Models\Masjid::orderBy('nama')->get();
        }

        // Stats - SEPARATE query for statistics (not affected by search/filter)
        $statsQuery = KategoriAset::query();

        // Apply masjid isolation for stats (but NOT search/filter)
        if (!$isSuperAdmin) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalKategori = (clone $statsQuery)->count();
        $aktifKategori = (clone $statsQuery)->where('status', 'Aktif')->count();
        $tidakAktifKategori = (clone $statsQuery)->where('status', 'Tidak Aktif')->count();

        $stats = [
            ['title' => 'Total Kategori', 'value' => $totalKategori, 'icon' => 'category', 'color' => 'blue'],
            ['title' => 'Kategori Aktif', 'value' => $aktifKategori, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Tidak Aktif', 'value' => $tidakAktifKategori, 'icon' => 'cancel', 'color' => 'orange'],
            ['title' => 'Total Aset', 'value' => 0, 'icon' => 'inventory_2', 'color' => 'purple'],
        ];

        return view('kategori-aset.index', compact('kategoriAset', 'stats', 'isSuperAdmin', 'masjidList'));
    }

    public function create()
    {
        return view('kategori-aset.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'kod_kategori' => 'required|max:50',
            'nama_kategori' => 'required|max:255',
            'jenis_kategori' => 'required|in:Tanah & Bangunan,Kenderaan,Peralatan,Perabot,Elektronik,Lain-lain',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // WAJIB: Auto-assign masjid_id for data isolation
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            // Super Admin can specify masjid_id or leave null
            $validated['masjid_id'] = $request->masjid_id;
        }

        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;
        $validated['kod_kategori'] = strtoupper($validated['kod_kategori']);

        KategoriAset::create($validated);

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berjaya ditambah.');
    }

    public function show(KategoriAset $kategoriAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kategoriAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $kategoriAset->load('masjid', 'senariAset', 'createdBy', 'updatedBy');

        return view('kategori-aset.show', compact('kategoriAset'));
    }

    public function edit(KategoriAset $kategoriAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kategoriAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        return view('kategori-aset.edit', compact('kategoriAset'));
    }

    public function update(Request $request, KategoriAset $kategoriAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kategoriAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $validated = $request->validate([
            'kod_kategori' => 'required|max:50',
            'nama_kategori' => 'required|max:255',
            'jenis_kategori' => 'required|in:Tanah & Bangunan,Kenderaan,Peralatan,Perabot,Elektronik,Lain-lain',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['updated_by'] = $user->id;
        $validated['kod_kategori'] = strtoupper($validated['kod_kategori']);

        $kategoriAset->update($validated);

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berjaya dikemaskini.');
    }

    public function destroy(KategoriAset $kategoriAset)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kategoriAset->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Check if kategori has assets
        if ($kategoriAset->senariAset()->count() > 0) {
            return back()->with('error', 'Kategori tidak boleh dipadam kerana masih mempunyai aset.');
        }

        $kategoriAset->update(['deleted_by' => $user->id]);
        $kategoriAset->delete();

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berjaya dipadam.');
    }
}
