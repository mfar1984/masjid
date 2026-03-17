<?php

namespace App\Http\Controllers;

use App\Models\SenaraiProgram;
use App\Models\JadualProgram;
use App\Models\PendaftaranPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->isSuperAdmin() ? $request->masjid_id : $user->masjid_id;

        // Stats
        $programQuery = SenaraiProgram::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId));
        $jadualQuery = JadualProgram::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId));
        $pesertaQuery = PendaftaranPeserta::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId));

        $stats = [
            ['title' => 'Jumlah Program', 'value' => (clone $programQuery)->count(), 'icon' => 'school', 'color' => 'blue'],
            ['title' => 'Jumlah Jadual', 'value' => (clone $jadualQuery)->count(), 'icon' => 'event', 'color' => 'green'],
            ['title' => 'Jumlah Peserta', 'value' => (clone $pesertaQuery)->count(), 'icon' => 'people', 'color' => 'purple'],
            ['title' => 'Kutipan Yuran', 'value' => 'RM ' . number_format((clone $pesertaQuery)->where('status_bayaran', 'Sudah Bayar')->sum('jumlah_bayaran'), 2), 'icon' => 'payments', 'color' => 'orange'],
        ];

        // Program by jenis
        $programByJenis = SenaraiProgram::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->selectRaw('jenis_program, count(*) as total')
            ->groupBy('jenis_program')
            ->get();

        // Recent jadual
        $recentJadual = JadualProgram::with(['program'])
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->latest('tarikh')
            ->take(10)
            ->get();

        // Filter by jenis
        $jenisProgram = $request->jenis_program;

        // Program list with peserta count
        $programList = SenaraiProgram::withCount('peserta')
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->when($jenisProgram, fn($q) => $q->where('jenis_program', $jenisProgram))
            ->latest()
            ->get();

        return view('laporan-program.index', compact('stats', 'programList'));
    }
}
