<?php

namespace App\Http\Controllers;

use App\Models\PermohonanPelupusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekodPelupusanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PermohonanPelupusan::with(['masjid', 'senariAset.kategoriAset', 'diluluskanOleh', 'createdBy'])
            ->where('status', 'Selesai');

        // WAJIB: Multi-Masjid Data Isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('kaedah_pelupusan')) {
            $query->where('kaedah_pelupusan', $request->kaedah_pelupusan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tarikh_pelupusan', $request->tahun);
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

        $rekodPelupusan = $query->latest('tarikh_pelupusan')->paginate(25);

        // Stats
        $statsQuery = PermohonanPelupusan::where('status', 'Selesai');
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalRekod = (clone $statsQuery)->count();
        $jumlahNilai = (clone $statsQuery)->sum('nilai_pelupusan');
        $jualan = (clone $statsQuery)->where('kaedah_pelupusan', 'Jualan')->count();
        $derma = (clone $statsQuery)->where('kaedah_pelupusan', 'Derma')->count();

        $stats = [
            ['title' => 'Jumlah Rekod', 'value' => $totalRekod, 'icon' => 'history', 'color' => 'blue'],
            ['title' => 'Nilai Pelupusan', 'value' => 'RM ' . number_format($jumlahNilai, 2), 'icon' => 'payments', 'color' => 'green'],
            ['title' => 'Jualan', 'value' => $jualan, 'icon' => 'sell', 'color' => 'purple'],
            ['title' => 'Derma', 'value' => $derma, 'icon' => 'volunteer_activism', 'color' => 'orange'],
        ];

        // Get years for filter
        $years = PermohonanPelupusan::where('status', 'Selesai')
            ->when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id))
            ->selectRaw('YEAR(tarikh_pelupusan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('rekod-pelupusan.index', compact('rekodPelupusan', 'stats', 'years'));
    }

    public function show(PermohonanPelupusan $permohonanPelupusan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $permohonanPelupusan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }

        $permohonanPelupusan->load(['masjid', 'senariAset.kategoriAset', 'diluluskanOleh', 'createdBy', 'updatedBy']);

        return view('rekod-pelupusan.show', compact('permohonanPelupusan'));
    }
}
