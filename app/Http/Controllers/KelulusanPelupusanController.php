<?php

namespace App\Http\Controllers;

use App\Models\PermohonanPelupusan;
use App\Models\SenariAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelulusanPelupusanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PermohonanPelupusan::with(['masjid', 'senariAset', 'createdBy']);

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Default: show only pending
        if (!$request->filled('status')) {
            $query->where('status', 'Menunggu');
        } else {
            $query->where('status', $request->status);
        }

        // Filters
        if ($request->filled('kaedah_pelupusan')) {
            $query->where('kaedah_pelupusan', $request->kaedah_pelupusan);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_rujukan', 'like', "%{$search}%")
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

        $menunggu = (clone $statsQuery)->where('status', 'Menunggu')->count();
        $diluluskan = (clone $statsQuery)->where('status', 'Diluluskan')->count();
        $ditolak = (clone $statsQuery)->where('status', 'Ditolak')->count();
        $selesai = (clone $statsQuery)->where('status', 'Selesai')->count();

        $stats = [
            ['title' => 'Menunggu Kelulusan', 'value' => $menunggu, 'icon' => 'hourglass_empty', 'color' => 'orange'],
            ['title' => 'Diluluskan', 'value' => $diluluskan, 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Ditolak', 'value' => $ditolak, 'icon' => 'cancel', 'color' => 'red'],
            ['title' => 'Selesai', 'value' => $selesai, 'icon' => 'task_alt', 'color' => 'purple'],
        ];

        return view('kelulusan-pelupusan.index', compact('permohonanPelupusan', 'stats'));
    }

    public function show(PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $permohonanPelupusan->load(['masjid', 'senariAset.kategoriAset', 'diluluskanOleh', 'createdBy', 'updatedBy']);

        return view('kelulusan-pelupusan.show', compact('permohonanPelupusan'));
    }

    public function approve(Request $request, PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        if ($permohonanPelupusan->status !== 'Menunggu') {
            return redirect()->route('kelulusan-pelupusan.index')
                ->with('error', 'Permohonan ini sudah diproses.');
        }

        $validated = $request->validate([
            'catatan_kelulusan' => 'nullable|string',
        ]);

        $permohonanPelupusan->update([
            'status' => 'Diluluskan',
            'diluluskan_oleh' => $user->id,
            'tarikh_kelulusan' => now(),
            'catatan_kelulusan' => $validated['catatan_kelulusan'] ?? null,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('kelulusan-pelupusan.index')
            ->with('success', 'Permohonan pelupusan berjaya diluluskan.');
    }

    public function reject(Request $request, PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        if ($permohonanPelupusan->status !== 'Menunggu') {
            return redirect()->route('kelulusan-pelupusan.index')
                ->with('error', 'Permohonan ini sudah diproses.');
        }

        $validated = $request->validate([
            'catatan_kelulusan' => 'required|string',
        ]);

        $permohonanPelupusan->update([
            'status' => 'Ditolak',
            'diluluskan_oleh' => $user->id,
            'tarikh_kelulusan' => now(),
            'catatan_kelulusan' => $validated['catatan_kelulusan'],
            'updated_by' => $user->id,
        ]);

        return redirect()->route('kelulusan-pelupusan.index')
            ->with('success', 'Permohonan pelupusan telah ditolak.');
    }

    public function complete(Request $request, PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        if ($permohonanPelupusan->status !== 'Diluluskan') {
            return redirect()->route('kelulusan-pelupusan.index')
                ->with('error', 'Hanya permohonan yang diluluskan boleh diselesaikan.');
        }

        $validated = $request->validate([
            'tarikh_pelupusan' => 'required|date',
        ]);

        // Update permohonan status
        $permohonanPelupusan->update([
            'status' => 'Selesai',
            'tarikh_pelupusan' => $validated['tarikh_pelupusan'],
            'updated_by' => $user->id,
        ]);

        // Update aset status to Dilupuskan
        SenariAset::where('id', $permohonanPelupusan->senarai_aset_id)
            ->update(['status_aset' => 'Dilupuskan']);

        return redirect()->route('kelulusan-pelupusan.index')
            ->with('success', 'Pelupusan aset telah diselesaikan.');
    }
}
