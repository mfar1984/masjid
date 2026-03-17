<?php

namespace App\Http\Controllers;

use App\Models\SenaraiProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SenaraiProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SenaraiProgram::with(['masjid']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_program', 'like', "%{$search}%")
                    ->orWhere('kod_program', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_program')) {
            $query->where('jenis_program', $request->jenis_program);
        }

        $programList = $query->latest()->paginate(25);

        $baseQuery = SenaraiProgram::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id));
        $stats = [
            ['title' => 'Jumlah Program', 'value' => (clone $baseQuery)->count(), 'icon' => 'school', 'color' => 'blue'],
            ['title' => 'Aktif', 'value' => (clone $baseQuery)->where('status', 'Aktif')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Selesai', 'value' => (clone $baseQuery)->where('status', 'Selesai')->count(), 'icon' => 'done_all', 'color' => 'purple'],
            ['title' => 'Tidak Aktif', 'value' => (clone $baseQuery)->where('status', 'Tidak Aktif')->count(), 'icon' => 'cancel', 'color' => 'red'],
        ];

        return view('senarai-program.index', compact('programList', 'stats'));
    }

    public function create()
    {
        return view('senarai-program.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_program' => 'required|string|max:255',
            'kod_program' => 'nullable|string|max:50',
            'jenis_program' => 'required|in:Kuliah,Ceramah,Kursus,Bengkel,Seminar,Kem,Lain-lain',
            'kategori' => 'required|in:Dewasa,Remaja,Kanak-kanak,Wanita,Umum',
            'penerangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'kapasiti' => 'nullable|integer|min:1',
            'yuran' => 'nullable|numeric|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif,Selesai',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;

        SenaraiProgram::create($validated);

        return redirect()->route('senarai-program.index')
            ->with('success', 'Program berjaya ditambah.');
    }

    public function show(SenaraiProgram $senaraiProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $senaraiProgram->load(['masjid', 'jadual', 'peserta']);

        return view('senarai-program.show', compact('senaraiProgram'));
    }

    public function edit(SenaraiProgram $senaraiProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        return view('senarai-program.edit', compact('senaraiProgram'));
    }

    public function update(Request $request, SenaraiProgram $senaraiProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_program' => 'required|string|max:255',
            'kod_program' => 'nullable|string|max:50',
            'jenis_program' => 'required|in:Kuliah,Ceramah,Kursus,Bengkel,Seminar,Kem,Lain-lain',
            'kategori' => 'required|in:Dewasa,Remaja,Kanak-kanak,Wanita,Umum',
            'penerangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'kapasiti' => 'nullable|integer|min:1',
            'yuran' => 'nullable|numeric|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif,Selesai',
        ]);

        $senaraiProgram->update($validated);

        return redirect()->route('senarai-program.index')
            ->with('success', 'Program berjaya dikemaskini.');
    }

    public function destroy(SenaraiProgram $senaraiProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $senaraiProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $senaraiProgram->delete();

        return redirect()->route('senarai-program.index')
            ->with('success', 'Program berjaya dipadam.');
    }
}
