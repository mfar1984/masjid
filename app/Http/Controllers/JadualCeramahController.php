<?php

namespace App\Http\Controllers;

use App\Models\JadualCeramah;
use App\Models\SenaraiPenceramah;
use App\Models\TransaksiKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadualCeramahController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = JadualCeramah::with(['masjid', 'penceramah']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tajuk_ceramah', 'like', "%{$search}%")
                    ->orWhereHas('penceramah', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_bayaran')) {
            $query->where('status_bayaran', $request->status_bayaran);
        }

        if ($request->filled('jenis_ceramah')) {
            $query->where('jenis_ceramah', $request->jenis_ceramah);
        }

        $jadualList = $query->latest('tarikh')->paginate(25);

        $baseQuery = JadualCeramah::query();
        if (!$user->isSuperAdmin()) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        }

        $stats = [
            ['title' => 'Jumlah Jadual', 'value' => (clone $baseQuery)->count(), 'icon' => 'event', 'color' => 'blue'],
            ['title' => 'Belum Bayar', 'value' => (clone $baseQuery)->where('status_bayaran', 'Belum Bayar')->count(), 'icon' => 'pending', 'color' => 'orange'],
            ['title' => 'Sudah Bayar', 'value' => (clone $baseQuery)->where('status_bayaran', 'Sudah Bayar')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Jumlah Kos', 'value' => 'RM ' . number_format((clone $baseQuery)->sum('kadar_bayaran'), 2), 'icon' => 'payments', 'color' => 'purple'],
        ];

        return view('jadual-ceramah.index', compact('jadualList', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $penceramahList = SenaraiPenceramah::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        return view('jadual-ceramah.create', compact('penceramahList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'penceramah_id' => 'required|exists:senarai_penceramah,id',
            'tarikh' => 'required|date',
            'masa_mula' => 'required',
            'masa_tamat' => 'required',
            'tajuk_ceramah' => 'required|string|max:255',
            'jenis_ceramah' => 'required|in:Kuliah Subuh,Kuliah Maghrib,Kuliah Isyak,Ceramah Jumaat,Ceramah Khas,Tazkirah,Lain-lain',
            'lokasi' => 'nullable|string|max:255',
            'jenis_bayaran' => 'required|in:Sekali,Mingguan,Bulanan,Percuma',
            'kadar_bayaran' => 'nullable|numeric|min:0',
            'kos_pengangkutan' => 'nullable|numeric|min:0',
            'kos_penginapan' => 'nullable|numeric|min:0',
            'kos_makan_minum' => 'nullable|numeric|min:0',
            'kos_lain' => 'nullable|numeric|min:0',
            'catatan_kos' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;
        $validated['status'] = 'Dijadual';
        $validated['status_bayaran'] = $validated['jenis_bayaran'] === 'Percuma' ? 'Sudah Bayar' : 'Belum Bayar';

        JadualCeramah::create($validated);

        return redirect()->route('jadual-ceramah.index')
            ->with('success', 'Jadual ceramah berjaya ditambah.');
    }

    public function show(JadualCeramah $jadualCeramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualCeramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $jadualCeramah->load(['masjid', 'penceramah', 'transaksi']);

        return view('jadual-ceramah.show', compact('jadualCeramah'));
    }

    public function edit(JadualCeramah $jadualCeramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualCeramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $penceramahList = SenaraiPenceramah::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        return view('jadual-ceramah.edit', compact('jadualCeramah', 'penceramahList'));
    }

    public function update(Request $request, JadualCeramah $jadualCeramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualCeramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'penceramah_id' => 'required|exists:senarai_penceramah,id',
            'tarikh' => 'required|date',
            'masa_mula' => 'required',
            'masa_tamat' => 'required',
            'tajuk_ceramah' => 'required|string|max:255',
            'jenis_ceramah' => 'required|in:Kuliah Subuh,Kuliah Maghrib,Kuliah Isyak,Ceramah Jumaat,Ceramah Khas,Tazkirah,Lain-lain',
            'lokasi' => 'nullable|string|max:255',
            'jenis_bayaran' => 'required|in:Sekali,Mingguan,Bulanan,Percuma',
            'kadar_bayaran' => 'nullable|numeric|min:0',
            'kos_pengangkutan' => 'nullable|numeric|min:0',
            'kos_penginapan' => 'nullable|numeric|min:0',
            'kos_makan_minum' => 'nullable|numeric|min:0',
            'kos_lain' => 'nullable|numeric|min:0',
            'catatan_kos' => 'nullable|string',
            'status' => 'required|in:Dijadual,Selesai,Batal',
            'catatan' => 'nullable|string',
        ]);

        $jadualCeramah->update($validated);

        return redirect()->route('jadual-ceramah.index')
            ->with('success', 'Jadual ceramah berjaya dikemaskini.');
    }

    public function destroy(JadualCeramah $jadualCeramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualCeramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $jadualCeramah->delete();

        return redirect()->route('jadual-ceramah.index')
            ->with('success', 'Jadual ceramah berjaya dipadam.');
    }

    public function bayar(JadualCeramah $jadualCeramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualCeramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        if ($jadualCeramah->status_bayaran === 'Sudah Bayar') {
            return back()->with('error', 'Bayaran sudah dibuat.');
        }

        DB::transaction(function () use ($jadualCeramah, $user) {
            $jadualCeramah->update([
                'status_bayaran' => 'Sudah Bayar',
                'tarikh_bayaran' => now(),
            ]);
        });

        return back()->with('success', 'Bayaran berjaya direkodkan.');
    }
}
