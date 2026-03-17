<?php

namespace App\Http\Controllers;

use App\Models\JadualProgram;
use App\Models\SenaraiProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadualProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = JadualProgram::with(['masjid', 'program']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('program', fn($q) => $q->where('nama_program', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $jadualList = $query->latest('tarikh')->paginate(25);

        $baseQuery = JadualProgram::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id));
        $stats = [
            ['title' => 'Jumlah Jadual', 'value' => (clone $baseQuery)->count(), 'icon' => 'event', 'color' => 'blue'],
            ['title' => 'Dijadual', 'value' => (clone $baseQuery)->where('status', 'Dijadual')->count(), 'icon' => 'schedule', 'color' => 'orange'],
            ['title' => 'Selesai', 'value' => (clone $baseQuery)->where('status', 'Selesai')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Batal', 'value' => (clone $baseQuery)->where('status', 'Batal')->count(), 'icon' => 'cancel', 'color' => 'red'],
        ];

        $programList = SenaraiProgram::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id))
            ->where('status', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        return view('jadual-program.index', compact('jadualList', 'stats', 'programList'));
    }

    public function create()
    {
        $user = Auth::user();
        $programList = SenaraiProgram::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        return view('jadual-program.create', compact('programList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'program_id' => 'required|exists:senarai_program,id',
            'tarikh' => 'required|date',
            'masa_mula' => 'required',
            'masa_tamat' => 'required',
            'lokasi' => 'nullable|string|max:255',
            'penceramah' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;
        $validated['status'] = 'Dijadual';

        JadualProgram::create($validated);

        return redirect()->route('jadual-program.index')
            ->with('success', 'Jadual program berjaya ditambah.');
    }

    public function show(JadualProgram $jadualProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $jadualProgram->load(['masjid', 'program', 'peserta']);

        return view('jadual-program.show', compact('jadualProgram'));
    }

    public function edit(JadualProgram $jadualProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $programList = SenaraiProgram::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        return view('jadual-program.edit', compact('jadualProgram', 'programList'));
    }

    public function update(Request $request, JadualProgram $jadualProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:senarai_program,id',
            'tarikh' => 'required|date',
            'masa_mula' => 'required',
            'masa_tamat' => 'required',
            'lokasi' => 'nullable|string|max:255',
            'penceramah' => 'nullable|string|max:255',
            'status' => 'required|in:Dijadual,Sedang Berlangsung,Selesai,Batal',
            'catatan' => 'nullable|string',
        ]);

        $jadualProgram->update($validated);

        return redirect()->route('jadual-program.index')
            ->with('success', 'Jadual program berjaya dikemaskini.');
    }

    public function destroy(JadualProgram $jadualProgram)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $jadualProgram->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $jadualProgram->delete();

        return redirect()->route('jadual-program.index')
            ->with('success', 'Jadual program berjaya dipadam.');
    }
}
