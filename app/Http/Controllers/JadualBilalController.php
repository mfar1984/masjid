<?php

namespace App\Http\Controllers;

use App\Models\JadualBilal;
use App\Models\Ajk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadualBilalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = JadualBilal::with(['masjid', 'ajk']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_bilal', 'like', "%{$search}%")
                    ->orWhereHas('ajk', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('waktu_solat')) {
            $query->where('waktu_solat', $request->waktu_solat);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jadualList = $query->latest('tarikh')->paginate(25);

        $baseQuery = JadualBilal::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id));
        $stats = [
            ['title' => 'Jumlah Jadual', 'value' => (clone $baseQuery)->count(), 'icon' => 'event', 'color' => 'blue'],
            ['title' => 'Dijadual', 'value' => (clone $baseQuery)->where('status', 'Dijadual')->count(), 'icon' => 'schedule', 'color' => 'orange'],
            ['title' => 'Selesai', 'value' => (clone $baseQuery)->where('status', 'Selesai')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Ganti', 'value' => (clone $baseQuery)->where('status', 'Ganti')->count(), 'icon' => 'swap_horiz', 'color' => 'purple'],
        ];

        return view('jadual-bilal.index', compact('jadualList', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $ajkList = Ajk::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        return view('jadual-bilal.create', compact('ajkList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'ajk_id' => 'nullable|exists:ajk,id',
            'nama_bilal' => 'nullable|string|max:255',
            'tarikh' => 'required|date',
            'waktu_solat' => 'required|in:Subuh,Zohor,Asar,Maghrib,Isyak,Jumaat,Tarawih,Hari Raya',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;
        $validated['status'] = 'Dijadual';

        JadualBilal::create($validated);

        return redirect()->route('jadual-bilal.index')
            ->with('success', 'Jadual bilal berjaya ditambah.');
    }

    public function show(JadualBilal $jadualBilal)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualBilal->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $jadualBilal->load(['masjid', 'ajk']);

        return view('jadual-bilal.show', compact('jadualBilal'));
    }

    public function edit(JadualBilal $jadualBilal)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualBilal->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $ajkList = Ajk::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        return view('jadual-bilal.edit', compact('jadualBilal', 'ajkList'));
    }

    public function update(Request $request, JadualBilal $jadualBilal)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualBilal->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'ajk_id' => 'nullable|exists:ajk,id',
            'nama_bilal' => 'nullable|string|max:255',
            'tarikh' => 'required|date',
            'waktu_solat' => 'required|in:Subuh,Zohor,Asar,Maghrib,Isyak,Jumaat,Tarawih,Hari Raya',
            'status' => 'required|in:Dijadual,Selesai,Ganti,Batal',
            'nama_ganti' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $jadualBilal->update($validated);

        return redirect()->route('jadual-bilal.index')
            ->with('success', 'Jadual bilal berjaya dikemaskini.');
    }

    public function destroy(JadualBilal $jadualBilal)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualBilal->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $jadualBilal->delete();

        return redirect()->route('jadual-bilal.index')
            ->with('success', 'Jadual bilal berjaya dipadam.');
    }
}
