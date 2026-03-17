<?php

namespace App\Http\Controllers;

use App\Models\SenaraiPenceramah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SenaraiPenceramahController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SenaraiPenceramah::with(['masjid']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_telefon', 'like', "%{$search}%")
                    ->orWhere('no_sijil_tauliah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('negara')) {
            $query->where('negara', $request->negara);
        }

        $penceramahList = $query->latest()->paginate(25);

        $stats = [
            ['title' => 'Jumlah Penceramah', 'value' => SenaraiPenceramah::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id))->count(), 'icon' => 'record_voice_over', 'color' => 'blue'],
            ['title' => 'Aktif', 'value' => SenaraiPenceramah::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id))->where('status', 'Aktif')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Malaysia', 'value' => SenaraiPenceramah::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id))->where('negara', 'Malaysia')->count(), 'icon' => 'flag', 'color' => 'purple'],
            ['title' => 'Luar Negara', 'value' => SenaraiPenceramah::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id))->where('negara', 'Luar Negara')->count(), 'icon' => 'public', 'color' => 'orange'],
        ];

        return view('senarai-penceramah.index', compact('penceramahList', 'stats'));
    }

    public function create()
    {
        return view('senarai-penceramah.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => 'nullable|string|max:20',
            'no_telefon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'negara' => 'required|in:Malaysia,Luar Negara',
            'negeri' => 'nullable|string|max:100',
            'no_sijil_tauliah' => 'nullable|string|max:100',
            'tarikh_tamat_tauliah' => 'nullable|date',
            'pihak_pengeluar' => 'nullable|string|max:255',
            'bidang_kepakaran' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;

        SenaraiPenceramah::create($validated);

        return redirect()->route('senarai-penceramah.index')
            ->with('success', 'Penceramah berjaya ditambah.');
    }

    public function show(SenaraiPenceramah $senaraiPenceramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiPenceramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $senaraiPenceramah->load(['masjid', 'jadualCeramah' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('senarai-penceramah.show', compact('senaraiPenceramah'));
    }

    public function edit(SenaraiPenceramah $senaraiPenceramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiPenceramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        return view('senarai-penceramah.edit', compact('senaraiPenceramah'));
    }

    public function update(Request $request, SenaraiPenceramah $senaraiPenceramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiPenceramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => 'nullable|string|max:20',
            'no_telefon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'negara' => 'required|in:Malaysia,Luar Negara',
            'negeri' => 'nullable|string|max:100',
            'no_sijil_tauliah' => 'nullable|string|max:100',
            'tarikh_tamat_tauliah' => 'nullable|date',
            'pihak_pengeluar' => 'nullable|string|max:255',
            'bidang_kepakaran' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'catatan' => 'nullable|string',
        ]);

        $senaraiPenceramah->update($validated);

        return redirect()->route('senarai-penceramah.index')
            ->with('success', 'Penceramah berjaya dikemaskini.');
    }

    public function destroy(SenaraiPenceramah $senaraiPenceramah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiPenceramah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $senaraiPenceramah->delete();

        return redirect()->route('senarai-penceramah.index')
            ->with('success', 'Penceramah berjaya dipadam.');
    }
}
