<?php

namespace App\Http\Controllers;

use App\Models\PermohonanPelupusan;
use App\Models\SenariAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanPelupusanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PermohonanPelupusan::with(['masjid', 'senariAset', 'diluluskanOleh']);

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('kaedah_pelupusan')) {
            $query->where('kaedah_pelupusan', $request->kaedah_pelupusan);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_rujukan', 'like', "%{$search}%")
                    ->orWhere('sebab_pelupusan', 'like', "%{$search}%")
                    ->orWhereHas('senariAset', function ($q2) use ($search) {
                        $q2->where('nama_aset', 'like', "%{$search}%");
                    });
            });
        }

        $permohonanPelupusan = $query->latest()->paginate(25);

        // Stats
        $statsQuery = PermohonanPelupusan::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalPermohonan = (clone $statsQuery)->count();
        $menunggu = (clone $statsQuery)->where('status', 'Menunggu')->count();
        $diluluskan = (clone $statsQuery)->where('status', 'Diluluskan')->count();
        $selesai = (clone $statsQuery)->where('status', 'Selesai')->count();

        $stats = [
            ['title' => 'Jumlah Permohonan', 'value' => $totalPermohonan, 'icon' => 'description', 'color' => 'blue'],
            ['title' => 'Menunggu', 'value' => $menunggu, 'icon' => 'hourglass_empty', 'color' => 'orange'],
            ['title' => 'Diluluskan', 'value' => $diluluskan, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Selesai', 'value' => $selesai, 'icon' => 'task_alt', 'color' => 'purple'],
        ];

        return view('permohonan-pelupusan.index', compact('permohonanPelupusan', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $senariAset = SenariAset::where('masjid_id', $masjidId)
            ->whereIn('status_aset', ['Aktif', 'Rosak', 'Tidak Aktif'])
            ->orderBy('nama_aset')
            ->get();

        return view('permohonan-pelupusan.create', compact('senariAset'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'senarai_aset_id' => 'required|exists:senarai_aset,id',
            'tarikh_permohonan' => 'required|date',
            'sebab_pelupusan' => 'required|string',
            'kaedah_pelupusan' => 'required|in:Jualan,Derma,Buang,Tukar Ganti',
            'nilai_pelupusan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['no_rujukan'] = PermohonanPelupusan::generateNoRujukan($user->masjid_id);
        $validated['status'] = 'Menunggu';
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        PermohonanPelupusan::create($validated);

        return redirect()->route('permohonan-pelupusan.index')
            ->with('success', 'Permohonan pelupusan berjaya ditambah.');
    }

    public function show(PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $permohonanPelupusan->load(['masjid', 'senariAset.kategoriAset', 'diluluskanOleh', 'createdBy', 'updatedBy']);

        return view('permohonan-pelupusan.show', compact('permohonanPelupusan'));
    }

    public function edit(PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        if ($permohonanPelupusan->status !== 'Menunggu') {
            return redirect()->route('permohonan-pelupusan.index')
                ->with('error', 'Permohonan yang sudah diproses tidak boleh diedit.');
        }

        $senariAset = SenariAset::where('masjid_id', $permohonanPelupusan->masjid_id)
            ->whereIn('status_aset', ['Aktif', 'Rosak', 'Tidak Aktif'])
            ->orderBy('nama_aset')
            ->get();

        return view('permohonan-pelupusan.edit', compact('permohonanPelupusan', 'senariAset'));
    }

    public function update(Request $request, PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        if ($permohonanPelupusan->status !== 'Menunggu') {
            return redirect()->route('permohonan-pelupusan.index')
                ->with('error', 'Permohonan yang sudah diproses tidak boleh diedit.');
        }

        $validated = $request->validate([
            'senarai_aset_id' => 'required|exists:senarai_aset,id',
            'tarikh_permohonan' => 'required|date',
            'sebab_pelupusan' => 'required|string',
            'kaedah_pelupusan' => 'required|in:Jualan,Derma,Buang,Tukar Ganti',
            'nilai_pelupusan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $permohonanPelupusan->update($validated);

        return redirect()->route('permohonan-pelupusan.index')
            ->with('success', 'Permohonan pelupusan berjaya dikemaskini.');
    }

    public function destroy(PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        if ($permohonanPelupusan->status !== 'Menunggu') {
            return redirect()->route('permohonan-pelupusan.index')
                ->with('error', 'Permohonan yang sudah diproses tidak boleh dipadam.');
        }

        $permohonanPelupusan->delete();

        return redirect()->route('permohonan-pelupusan.index')
            ->with('success', 'Permohonan pelupusan berjaya dipadam.');
    }
}
