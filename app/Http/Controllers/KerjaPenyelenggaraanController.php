<?php

namespace App\Http\Controllers;

use App\Models\KerjaPenyelenggaraan;
use App\Models\JadualPenyelenggaraan;
use App\Models\SenariAset;
use App\Models\SenariFasiliti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KerjaPenyelenggaraanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = KerjaPenyelenggaraan::with(['masjid', 'senariAset', 'senariFasiliti', 'jadualPenyelenggaraan']);

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('jenis_item')) {
            $query->where('jenis_item', $request->jenis_item);
        }
        if ($request->filled('jenis_kerja')) {
            $query->where('jenis_kerja', $request->jenis_kerja);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tarikh_dari')) {
            $query->whereDate('tarikh_kerja', '>=', $request->tarikh_dari);
        }
        if ($request->filled('tarikh_hingga')) {
            $query->whereDate('tarikh_kerja', '<=', $request->tarikh_hingga);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_kerja', 'like', "%{$search}%")
                    ->orWhere('penerangan_kerja', 'like', "%{$search}%")
                    ->orWhere('vendor_nama', 'like', "%{$search}%");
            });
        }

        $kerjaPenyelenggaraan = $query->latest()->paginate(25);

        // Stats
        $statsQuery = KerjaPenyelenggaraan::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalKerja = (clone $statsQuery)->count();
        $kerjaDirancang = (clone $statsQuery)->where('status', 'Dirancang')->count();
        $kerjaBerjalan = (clone $statsQuery)->where('status', 'Sedang Berjalan')->count();
        $kerjaSelesai = (clone $statsQuery)->where('status', 'Selesai')->count();
        $jumlahKos = (clone $statsQuery)->where('status', 'Selesai')->sum('kos');

        $stats = [
            ['title' => 'Jumlah Kerja', 'value' => $totalKerja, 'icon' => 'build', 'color' => 'blue'],
            ['title' => 'Dirancang', 'value' => $kerjaDirancang, 'icon' => 'schedule', 'color' => 'orange'],
            ['title' => 'Sedang Berjalan', 'value' => $kerjaBerjalan, 'icon' => 'engineering', 'color' => 'yellow'],
            ['title' => 'Selesai', 'value' => $kerjaSelesai, 'icon' => 'check_circle', 'color' => 'green'],
        ];

        return view('kerja-penyelenggaraan.index', compact('kerjaPenyelenggaraan', 'stats', 'jumlahKos'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $jadualPenyelenggaraan = JadualPenyelenggaraan::where('masjid_id', $masjidId)
            ->where('status', 'Aktif')
            ->orderBy('nama_jadual')
            ->get();

        $senariAset = SenariAset::where('masjid_id', $masjidId)
            ->where('status_aset', 'Aktif')
            ->orderBy('nama_aset')
            ->get();

        $senariFasiliti = SenariFasiliti::where('masjid_id', $masjidId)
            ->where('status_fasiliti', 'Tersedia')
            ->orderBy('nama_fasiliti')
            ->get();

        return view('kerja-penyelenggaraan.create', compact('jadualPenyelenggaraan', 'senariAset', 'senariFasiliti'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'jadual_penyelenggaraan_id' => 'nullable|exists:jadual_penyelenggaraan,id',
            'jenis_item' => 'required|in:Aset,Fasiliti',
            'senarai_aset_id' => 'required_if:jenis_item,Aset|nullable|exists:senarai_aset,id',
            'senarai_fasiliti_id' => 'required_if:jenis_item,Fasiliti|nullable|exists:senarai_fasiliti,id',
            'tarikh_kerja' => 'required|date',
            'masa_mula' => 'nullable|date_format:H:i',
            'masa_tamat' => 'nullable|date_format:H:i|after:masa_mula',
            'jenis_kerja' => 'required|in:Penyelenggaraan Berkala,Pembaikan,Pemeriksaan,Servis,Kecemasan',
            'penerangan_kerja' => 'required|string',
            'vendor_nama' => 'nullable|string|max:255',
            'vendor_telefon' => 'nullable|string|max:20',
            'vendor_alamat' => 'nullable|string|max:255',
            'kos' => 'nullable|numeric|min:0',
            'kondisi_sebelum' => 'nullable|in:Baik,Sederhana,Teruk,Rosak',
            'kondisi_selepas' => 'nullable|in:Baik,Sederhana,Teruk,Rosak',
            'status' => 'required|in:Dirancang,Sedang Berjalan,Selesai,Dibatalkan,Tertangguh',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['no_kerja'] = KerjaPenyelenggaraan::generateNoKerja($user->masjid_id);
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        KerjaPenyelenggaraan::create($validated);

        return redirect()->route('kerja-penyelenggaraan.index')
            ->with('success', 'Kerja penyelenggaraan berjaya ditambah.');
    }

    public function show(KerjaPenyelenggaraan $kerjaPenyelenggaraan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $kerjaPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $kerjaPenyelenggaraan->load(['masjid', 'senariAset', 'senariFasiliti', 'jadualPenyelenggaraan', 'transaksiKewangan', 'createdBy', 'updatedBy']);

        return view('kerja-penyelenggaraan.show', compact('kerjaPenyelenggaraan'));
    }

    public function edit(KerjaPenyelenggaraan $kerjaPenyelenggaraan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $kerjaPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $jadualPenyelenggaraan = JadualPenyelenggaraan::where('masjid_id', $kerjaPenyelenggaraan->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama_jadual')
            ->get();

        $senariAset = SenariAset::where('masjid_id', $kerjaPenyelenggaraan->masjid_id)
            ->where('status_aset', 'Aktif')
            ->orderBy('nama_aset')
            ->get();

        $senariFasiliti = SenariFasiliti::where('masjid_id', $kerjaPenyelenggaraan->masjid_id)
            ->where('status_fasiliti', 'Tersedia')
            ->orderBy('nama_fasiliti')
            ->get();

        return view('kerja-penyelenggaraan.edit', compact('kerjaPenyelenggaraan', 'jadualPenyelenggaraan', 'senariAset', 'senariFasiliti'));
    }

    public function update(Request $request, KerjaPenyelenggaraan $kerjaPenyelenggaraan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $kerjaPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'jadual_penyelenggaraan_id' => 'nullable|exists:jadual_penyelenggaraan,id',
            'jenis_item' => 'required|in:Aset,Fasiliti',
            'senarai_aset_id' => 'required_if:jenis_item,Aset|nullable|exists:senarai_aset,id',
            'senarai_fasiliti_id' => 'required_if:jenis_item,Fasiliti|nullable|exists:senarai_fasiliti,id',
            'tarikh_kerja' => 'required|date',
            'masa_mula' => 'nullable|date_format:H:i',
            'masa_tamat' => 'nullable|date_format:H:i|after:masa_mula',
            'jenis_kerja' => 'required|in:Penyelenggaraan Berkala,Pembaikan,Pemeriksaan,Servis,Kecemasan',
            'penerangan_kerja' => 'required|string',
            'vendor_nama' => 'nullable|string|max:255',
            'vendor_telefon' => 'nullable|string|max:20',
            'vendor_alamat' => 'nullable|string|max:255',
            'kos' => 'nullable|numeric|min:0',
            'kondisi_sebelum' => 'nullable|in:Baik,Sederhana,Teruk,Rosak',
            'kondisi_selepas' => 'nullable|in:Baik,Sederhana,Teruk,Rosak',
            'status' => 'required|in:Dirancang,Sedang Berjalan,Selesai,Dibatalkan,Tertangguh',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $kerjaPenyelenggaraan->update($validated);

        return redirect()->route('kerja-penyelenggaraan.index')
            ->with('success', 'Kerja penyelenggaraan berjaya dikemaskini.');
    }

    public function destroy(KerjaPenyelenggaraan $kerjaPenyelenggaraan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $kerjaPenyelenggaraan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $kerjaPenyelenggaraan->update(['deleted_by' => $user->id]);
        $kerjaPenyelenggaraan->delete();

        return redirect()->route('kerja-penyelenggaraan.index')
            ->with('success', 'Kerja penyelenggaraan berjaya dipadam.');
    }
}
