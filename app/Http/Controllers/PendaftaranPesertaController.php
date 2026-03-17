<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPeserta;
use App\Models\SenaraiProgram;
use App\Models\JadualProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranPesertaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PendaftaranPeserta::with(['masjid', 'program', 'jadual']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peserta', 'like', "%{$search}%")
                    ->orWhere('no_telefon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('status_bayaran')) {
            $query->where('status_bayaran', $request->status_bayaran);
        }

        $pesertaList = $query->latest()->paginate(25);

        $baseQuery = PendaftaranPeserta::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id));
        $stats = [
            ['title' => 'Jumlah Peserta', 'value' => (clone $baseQuery)->count(), 'icon' => 'people', 'color' => 'blue'],
            ['title' => 'Sudah Bayar', 'value' => (clone $baseQuery)->where('status_bayaran', 'Sudah Bayar')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Belum Bayar', 'value' => (clone $baseQuery)->where('status_bayaran', 'Belum Bayar')->count(), 'icon' => 'pending', 'color' => 'orange'],
            ['title' => 'Hadir', 'value' => (clone $baseQuery)->where('status_kehadiran', 'Hadir')->count(), 'icon' => 'how_to_reg', 'color' => 'purple'],
        ];

        $programList = SenaraiProgram::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id))
            ->where('status', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        return view('pendaftaran-peserta.index', compact('pesertaList', 'stats', 'programList'));
    }

    public function create()
    {
        $user = Auth::user();
        $programList = SenaraiProgram::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        return view('pendaftaran-peserta.create', compact('programList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'program_id' => 'required|exists:senarai_program,id',
            'jadual_id' => 'nullable|exists:jadual_program,id',
            'nama_peserta' => 'required|string|max:255',
            'no_ic' => 'nullable|string|max:20',
            'no_telefon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status_bayaran' => 'required|in:Belum Bayar,Sudah Bayar,Percuma',
            'jumlah_bayaran' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;
        $validated['tarikh_daftar'] = now();

        PendaftaranPeserta::create($validated);

        return redirect()->route('pendaftaran-peserta.index')
            ->with('success', 'Peserta berjaya didaftarkan.');
    }

    public function show(PendaftaranPeserta $pendaftaranPeserta)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pendaftaranPeserta->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $pendaftaranPeserta->load(['masjid', 'program', 'jadual']);

        return view('pendaftaran-peserta.show', compact('pendaftaranPeserta'));
    }

    public function edit(PendaftaranPeserta $pendaftaranPeserta)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pendaftaranPeserta->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $programList = SenaraiProgram::where('masjid_id', $user->masjid_id)
            ->where('status', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        return view('pendaftaran-peserta.edit', compact('pendaftaranPeserta', 'programList'));
    }

    public function update(Request $request, PendaftaranPeserta $pendaftaranPeserta)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pendaftaranPeserta->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:senarai_program,id',
            'jadual_id' => 'nullable|exists:jadual_program,id',
            'nama_peserta' => 'required|string|max:255',
            'no_ic' => 'nullable|string|max:20',
            'no_telefon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status_bayaran' => 'required|in:Belum Bayar,Sudah Bayar,Percuma',
            'jumlah_bayaran' => 'nullable|numeric|min:0',
            'status_kehadiran' => 'required|in:Belum Hadir,Hadir,Tidak Hadir',
            'catatan' => 'nullable|string',
        ]);

        $pendaftaranPeserta->update($validated);

        return redirect()->route('pendaftaran-peserta.index')
            ->with('success', 'Maklumat peserta berjaya dikemaskini.');
    }

    public function destroy(PendaftaranPeserta $pendaftaranPeserta)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pendaftaranPeserta->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $pendaftaranPeserta->delete();

        return redirect()->route('pendaftaran-peserta.index')
            ->with('success', 'Peserta berjaya dipadam.');
    }
}
