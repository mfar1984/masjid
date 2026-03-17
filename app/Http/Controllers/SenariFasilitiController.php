<?php

namespace App\Http\Controllers;

use App\Models\SenariFasiliti;
use App\Models\SenariAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SenariFasilitiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SenariFasiliti::with(['masjid', 'senariAset']);

        // Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all
        } else {
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $query->where('masjid_id', $userMasjidId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Filters
        if ($request->filled('jenis_fasiliti')) {
            $query->where('jenis_fasiliti', $request->jenis_fasiliti);
        }

        if ($request->filled('status_fasiliti')) {
            $query->where('status_fasiliti', $request->status_fasiliti);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kod_fasiliti', 'like', "%{$search}%")
                    ->orWhere('nama_fasiliti', 'like', "%{$search}%");
            });
        }

        $senariFasiliti = $query->latest()->paginate(25);

        // Stats
        $statsQuery = SenariFasiliti::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }


        $totalFasiliti = (clone $statsQuery)->count();
        $tersedia = (clone $statsQuery)->where('status_fasiliti', 'Tersedia')->count();
        $tidakTersedia = (clone $statsQuery)->where('status_fasiliti', 'Tidak Tersedia')->count();
        $totalTempahan = $user->isSuperAdmin() 
            ? \App\Models\TempahanFasiliti::whereMonth('created_at', date('m'))->count()
            : \App\Models\TempahanFasiliti::where('masjid_id', $user->masjid_id)->whereMonth('created_at', date('m'))->count();

        $stats = [
            ['title' => 'Total Fasiliti', 'value' => $totalFasiliti, 'icon' => 'business', 'color' => 'blue'],
            ['title' => 'Fasiliti Tersedia', 'value' => $tersedia, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Tidak Tersedia', 'value' => $tidakTersedia, 'icon' => 'cancel', 'color' => 'red'],
            ['title' => 'Tempahan Bulan Ini', 'value' => $totalTempahan, 'icon' => 'event', 'color' => 'purple'],
        ];

        return view('senarai-fasiliti.index', compact('senariFasiliti', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get aset list for dropdown (if jenis = Aset)
        $senariAset = SenariAset::where('masjid_id', $masjidId)
            ->where('status_aset', 'Aktif')
            ->orderBy('nama_aset')
            ->get();

        return view('senarai-fasiliti.create', compact('senariAset'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_fasiliti' => 'required|max:255',
            'jenis_fasiliti' => 'required|in:Dewan,Bilik,Padang,Tempat Letak Kereta,Aset,Lain-lain',
            'kategori_fasiliti' => 'nullable|max:255',
            'senarai_aset_id' => 'nullable|exists:senarai_aset,id',
            'kapasiti_maksimum' => 'nullable|integer|min:0',
            'luas_kawasan' => 'nullable|max:100',
            'kemudahan' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'harga_sewa_sejam' => 'nullable|numeric|min:0',
            'harga_sewa_separuh_hari' => 'nullable|numeric|min:0',
            'harga_sewa_sehari' => 'nullable|numeric|min:0',
            'deposit_diperlukan' => 'nullable|numeric|min:0',
            'syarat_tempahan' => 'nullable|string',
            'peraturan_penggunaan' => 'nullable|string',
            'had_minimum_tempahan' => 'nullable|integer|min:1',
            'had_maksimum_tempahan' => 'nullable|integer|min:1',
            'gambar_fasiliti' => 'nullable|array|max:5',
            'gambar_fasiliti.*' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'dokumen_peraturan' => 'nullable|file|mimes:pdf|max:5120',
            'status_fasiliti' => 'required|in:Tersedia,Tidak Tersedia,Dalam Penyelenggaraan',
            'catatan' => 'nullable|string',
        ]);

        // Auto-assign masjid_id
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            $validated['masjid_id'] = $request->masjid_id;
        }

        $masjidId = $validated['masjid_id'];

        // Auto-generate kod_fasiliti
        $validated['kod_fasiliti'] = SenariFasiliti::generateKodFasiliti($masjidId);

        // Handle file uploads
        if ($request->hasFile('gambar_fasiliti')) {
            $gambarPaths = [];
            foreach ($request->file('gambar_fasiliti') as $file) {
                $gambarPaths[] = $file->store('fasiliti/gambar', 'public');
            }
            $validated['gambar_fasiliti'] = json_encode($gambarPaths);
        }

        if ($request->hasFile('dokumen_peraturan')) {
            $validated['dokumen_peraturan'] = $request->file('dokumen_peraturan')->store('fasiliti/dokumen', 'public');
        }

        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        SenariFasiliti::create($validated);

        return redirect()->route('senarai-fasiliti.index')
            ->with('success', 'Fasiliti berjaya didaftarkan.');
    }


    public function show($id)
    {
        $user = Auth::user();

        // Use withoutGlobalScope to bypass masjid scope for route model binding
        $senariFasiliti = SenariFasiliti::withoutGlobalScope('masjid')->findOrFail($id);

        // Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariFasiliti->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $senariFasiliti->load(['masjid', 'senariAset', 'tempahanFasiliti', 'createdBy', 'updatedBy']);

        return view('senarai-fasiliti.show', compact('senariFasiliti'));
    }

    public function edit($id)
    {
        $user = Auth::user();

        // Use withoutGlobalScope to bypass masjid scope for route model binding
        $senariFasiliti = SenariFasiliti::withoutGlobalScope('masjid')->findOrFail($id);

        // Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariFasiliti->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $senariAset = SenariAset::where('masjid_id', $senariFasiliti->masjid_id)
            ->where('status_aset', 'Aktif')
            ->orderBy('nama_aset')
            ->get();

        return view('senarai-fasiliti.edit', compact('senariFasiliti', 'senariAset'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Use withoutGlobalScope to bypass masjid scope for route model binding
        $senariFasiliti = SenariFasiliti::withoutGlobalScope('masjid')->findOrFail($id);

        // Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariFasiliti->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $validated = $request->validate([
            'nama_fasiliti' => 'required|max:255',
            'jenis_fasiliti' => 'required|in:Dewan,Bilik,Padang,Tempat Letak Kereta,Aset,Lain-lain',
            'kategori_fasiliti' => 'nullable|max:255',
            'senarai_aset_id' => 'nullable|exists:senarai_aset,id',
            'kapasiti_maksimum' => 'nullable|integer|min:0',
            'luas_kawasan' => 'nullable|max:100',
            'kemudahan' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'harga_sewa_sejam' => 'nullable|numeric|min:0',
            'harga_sewa_separuh_hari' => 'nullable|numeric|min:0',
            'harga_sewa_sehari' => 'nullable|numeric|min:0',
            'deposit_diperlukan' => 'nullable|numeric|min:0',
            'syarat_tempahan' => 'nullable|string',
            'peraturan_penggunaan' => 'nullable|string',
            'had_minimum_tempahan' => 'nullable|integer|min:1',
            'had_maksimum_tempahan' => 'nullable|integer|min:1',
            'status_fasiliti' => 'required|in:Tersedia,Tidak Tersedia,Dalam Penyelenggaraan',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $senariFasiliti->update($validated);

        return redirect()->route('senarai-fasiliti.index')
            ->with('success', 'Fasiliti berjaya dikemaskini.');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        // Use withoutGlobalScope to bypass masjid scope for route model binding
        $senariFasiliti = SenariFasiliti::withoutGlobalScope('masjid')->findOrFail($id);

        // Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($senariFasiliti->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access');
            }
        }

        // Check if fasiliti has tempahan
        if ($senariFasiliti->tempahanFasiliti()->count() > 0) {
            return back()->with('error', 'Fasiliti tidak boleh dipadam kerana mempunyai rekod tempahan.');
        }

        $senariFasiliti->update(['deleted_by' => $user->id]);
        $senariFasiliti->delete();

        return redirect()->route('senarai-fasiliti.index')
            ->with('success', 'Fasiliti berjaya dipadam.');
    }
}
