<?php

namespace App\Http\Controllers;

use App\Models\JadualPenyusutan;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadualPenyusutanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = JadualPenyusutan::with(['masjid', 'kategoriAset']);

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('kaedah_susut')) {
            $query->where('kaedah_susut', $request->kaedah_susut);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kategoriAset', function ($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%");
            });
        }

        $jadualPenyusutan = $query->latest()->paginate(25);

        // Stats
        $statsQuery = JadualPenyusutan::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalJadual = (clone $statsQuery)->count();
        $jadualAktif = (clone $statsQuery)->where('status', 'Aktif')->count();
        $jadualTidakAktif = (clone $statsQuery)->where('status', 'Tidak Aktif')->count();
        $avgKadar = (clone $statsQuery)->avg('kadar_susut_tahunan') ?? 0;

        $stats = [
            ['title' => 'Jumlah Jadual', 'value' => $totalJadual, 'icon' => 'trending_down', 'color' => 'blue'],
            ['title' => 'Aktif', 'value' => $jadualAktif, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Tidak Aktif', 'value' => $jadualTidakAktif, 'icon' => 'cancel', 'color' => 'gray'],
            ['title' => 'Purata Kadar', 'value' => number_format($avgKadar, 1) . '%', 'icon' => 'percent', 'color' => 'purple'],
        ];

        return view('jadual-penyusutan.index', compact('jadualPenyusutan', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategoriAset = KategoriAset::where('masjid_id', $masjidId)
            ->where('status', 'Aktif')
            ->orderBy('nama_kategori')
            ->get();

        // Get existing kategori that already have jadual
        $existingKategori = JadualPenyusutan::where('masjid_id', $masjidId)
            ->pluck('kategori_aset_id')
            ->toArray();

        return view('jadual-penyusutan.create', compact('kategoriAset', 'existingKategori'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'kategori_aset_id' => 'required|exists:kategori_aset,id|unique:jadual_penyusutan,kategori_aset_id,NULL,id,masjid_id,' . $user->masjid_id,
            'kadar_susut_tahunan' => 'required|numeric|min:0|max:100',
            'kaedah_susut' => 'required|in:Garis Lurus,Baki Berkurangan',
            'tempoh_guna_tahun' => 'required|integer|min:1|max:50',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        JadualPenyusutan::create($validated);

        return redirect()->route('jadual-penyusutan.index')
            ->with('success', 'Jadual penyusutan berjaya ditambah.');
    }

    public function show(JadualPenyusutan $jadualPenyusutan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $jadualPenyusutan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $jadualPenyusutan->load(['masjid', 'kategoriAset', 'createdBy', 'updatedBy']);

        return view('jadual-penyusutan.show', compact('jadualPenyusutan'));
    }

    public function edit(JadualPenyusutan $jadualPenyusutan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $jadualPenyusutan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $kategoriAset = KategoriAset::where('masjid_id', $jadualPenyusutan->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama_kategori')
            ->get();

        return view('jadual-penyusutan.edit', compact('jadualPenyusutan', 'kategoriAset'));
    }

    public function update(Request $request, JadualPenyusutan $jadualPenyusutan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $jadualPenyusutan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'kategori_aset_id' => 'required|exists:kategori_aset,id|unique:jadual_penyusutan,kategori_aset_id,' . $jadualPenyusutan->id . ',id,masjid_id,' . $jadualPenyusutan->masjid_id,
            'kadar_susut_tahunan' => 'required|numeric|min:0|max:100',
            'kaedah_susut' => 'required|in:Garis Lurus,Baki Berkurangan',
            'tempoh_guna_tahun' => 'required|integer|min:1|max:50',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $jadualPenyusutan->update($validated);

        return redirect()->route('jadual-penyusutan.index')
            ->with('success', 'Jadual penyusutan berjaya dikemaskini.');
    }

    public function destroy(JadualPenyusutan $jadualPenyusutan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $jadualPenyusutan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $jadualPenyusutan->delete();

        return redirect()->route('jadual-penyusutan.index')
            ->with('success', 'Jadual penyusutan berjaya dipadam.');
    }
}
