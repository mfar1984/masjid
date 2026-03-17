<?php

namespace App\Http\Controllers;

use App\Models\JadualPenyelenggaraan;
use App\Models\SenariAset;
use App\Models\SenariFasiliti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadualPenyelenggaraanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = JadualPenyelenggaraan::with(['masjid', 'senariAset', 'senariFasiliti']);

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('jenis_item')) {
            $query->where('jenis_item', $request->jenis_item);
        }
        if ($request->filled('jenis_penyelenggaraan')) {
            $query->where('jenis_penyelenggaraan', $request->jenis_penyelenggaraan);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_jadual', 'like', "%{$search}%")
                    ->orWhere('nama_jadual', 'like', "%{$search}%");
            });
        }

        $jadualPenyelenggaraan = $query->latest()->paginate(25);

        // Stats
        $statsQuery = JadualPenyelenggaraan::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalJadual = (clone $statsQuery)->count();
        $jadualAktif = (clone $statsQuery)->where('status', 'Aktif')->count();
        $jadualSelesai = (clone $statsQuery)->where('status', 'Selesai')->count();
        $jadualTidakAktif = (clone $statsQuery)->where('status', 'Tidak Aktif')->count();

        $stats = [
            ['title' => 'Jumlah Jadual', 'value' => $totalJadual, 'icon' => 'calendar_month', 'color' => 'blue'],
            ['title' => 'Aktif', 'value' => $jadualAktif, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Selesai', 'value' => $jadualSelesai, 'icon' => 'task_alt', 'color' => 'purple'],
            ['title' => 'Tidak Aktif', 'value' => $jadualTidakAktif, 'icon' => 'cancel', 'color' => 'gray'],
        ];

        return view('jadual-penyelenggaraan.index', compact('jadualPenyelenggaraan', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $senariAset = SenariAset::where('masjid_id', $masjidId)
            ->where('status_aset', 'Aktif')
            ->orderBy('nama_aset')
            ->get();

        $senariFasiliti = SenariFasiliti::where('masjid_id', $masjidId)
            ->where('status_fasiliti', 'Tersedia')
            ->orderBy('nama_fasiliti')
            ->get();

        return view('jadual-penyelenggaraan.create', compact('senariAset', 'senariFasiliti'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_jadual' => 'required|string|max:255',
            'jenis_item' => 'required|in:Aset,Fasiliti',
            'senarai_aset_id' => 'required_if:jenis_item,Aset|nullable|exists:senarai_aset,id',
            'senarai_fasiliti_id' => 'required_if:jenis_item,Fasiliti|nullable|exists:senarai_fasiliti,id',
            'jenis_penyelenggaraan' => 'required|in:Berkala,Pembaikan,Pemeriksaan,Servis',
            'kekerapan' => 'required|in:Harian,Mingguan,Bulanan,Suku Tahunan,Tahunan',
            'tarikh_mula' => 'required|date',
            'tarikh_akhir' => 'nullable|date|after:tarikh_mula',
            'skop_kerja' => 'nullable|string',
            'vendor_nama' => 'nullable|string|max:255',
            'vendor_telefon' => 'nullable|string|max:20',
            'anggaran_kos' => 'nullable|numeric|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif,Selesai',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['no_jadual'] = JadualPenyelenggaraan::generateNoJadual($user->masjid_id);
        $validated['tarikh_penyelenggaraan_seterusnya'] = $validated['tarikh_mula'];
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        JadualPenyelenggaraan::create($validated);

        return redirect()->route('jadual-penyelenggaraan.index')
            ->with('success', 'Jadual penyelenggaraan berjaya ditambah.');
    }

    public function show(JadualPenyelenggaraan $jadualPenyelenggaraan)
    {
        $user = Auth::user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin() && $jadualPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $jadualPenyelenggaraan->load(['masjid', 'senariAset', 'senariFasiliti', 'kerjaPenyelenggaraan', 'createdBy', 'updatedBy']);

        return view('jadual-penyelenggaraan.show', compact('jadualPenyelenggaraan'));
    }

    public function edit(JadualPenyelenggaraan $jadualPenyelenggaraan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $jadualPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $senariAset = SenariAset::where('masjid_id', $jadualPenyelenggaraan->masjid_id)
            ->where('status_aset', 'Aktif')
            ->orderBy('nama_aset')
            ->get();

        $senariFasiliti = SenariFasiliti::where('masjid_id', $jadualPenyelenggaraan->masjid_id)
            ->where('status_fasiliti', 'Tersedia')
            ->orderBy('nama_fasiliti')
            ->get();

        return view('jadual-penyelenggaraan.edit', compact('jadualPenyelenggaraan', 'senariAset', 'senariFasiliti'));
    }

    public function update(Request $request, JadualPenyelenggaraan $jadualPenyelenggaraan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $jadualPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'nama_jadual' => 'required|string|max:255',
            'jenis_item' => 'required|in:Aset,Fasiliti',
            'senarai_aset_id' => 'required_if:jenis_item,Aset|nullable|exists:senarai_aset,id',
            'senarai_fasiliti_id' => 'required_if:jenis_item,Fasiliti|nullable|exists:senarai_fasiliti,id',
            'jenis_penyelenggaraan' => 'required|in:Berkala,Pembaikan,Pemeriksaan,Servis',
            'kekerapan' => 'required|in:Harian,Mingguan,Bulanan,Suku Tahunan,Tahunan',
            'tarikh_mula' => 'required|date',
            'tarikh_akhir' => 'nullable|date|after:tarikh_mula',
            'tarikh_penyelenggaraan_seterusnya' => 'nullable|date',
            'skop_kerja' => 'nullable|string',
            'vendor_nama' => 'nullable|string|max:255',
            'vendor_telefon' => 'nullable|string|max:20',
            'anggaran_kos' => 'nullable|numeric|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif,Selesai',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $jadualPenyelenggaraan->update($validated);

        return redirect()->route('jadual-penyelenggaraan.index')
            ->with('success', 'Jadual penyelenggaraan berjaya dikemaskini.');
    }

    public function destroy(JadualPenyelenggaraan $jadualPenyelenggaraan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $jadualPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $jadualPenyelenggaraan->update(['deleted_by' => $user->id]);
        $jadualPenyelenggaraan->delete();

        return redirect()->route('jadual-penyelenggaraan.index')
            ->with('success', 'Jadual penyelenggaraan berjaya dipadam.');
    }
}
