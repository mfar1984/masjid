<?php

namespace App\Http\Controllers;

use App\Models\PermohonanBantuan;
use App\Models\PenerimaBantuan;
use App\Models\ProgramKebajikan;
use App\Models\TetapanKebajikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanBantuanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $query = PermohonanBantuan::with(['masjid', 'penerimaBantuan', 'programKebajikan']);

        // Multi-masjid isolation
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('program_kebajikan_id')) {
            $query->where('program_kebajikan_id', $request->program_kebajikan_id);
        }

        if ($request->filled('status_permohonan')) {
            $query->where('status_permohonan', $request->status_permohonan);
        }

        if ($request->filled('keutamaan')) {
            $query->where('keutamaan', $request->keutamaan);
        }

        if ($request->filled('tarikh_dari')) {
            $query->whereDate('tarikh_permohonan', '>=', $request->tarikh_dari);
        }

        if ($request->filled('tarikh_hingga')) {
            $query->whereDate('tarikh_permohonan', '<=', $request->tarikh_hingga);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_permohonan', 'like', "%{$search}%")
                    ->orWhereHas('penerimaBantuan', function ($q) use ($search) {
                        $q->where('nama_penuh', 'like', "%{$search}%");
                    });
            });
        }

        // Get items per page from settings
        $settings = TetapanKebajikan::getSettings($isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id, ['items_per_page']);
        $perPage = $settings['items_per_page'] ?? 10;

        $permohonan = $query->latest()->paginate($perPage);

        // Stats
        $baseQuery = PermohonanBantuan::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $stats = [
            ['title' => 'Total Permohonan', 'value' => (clone $baseQuery)->count(), 'icon' => 'description', 'color' => 'blue'],
            ['title' => 'Baharu', 'value' => (clone $baseQuery)->where('status_permohonan', 'Baharu')->count(), 'icon' => 'fiber_new', 'color' => 'blue'],
            ['title' => 'Dalam Semakan', 'value' => (clone $baseQuery)->where('status_permohonan', 'Dalam Semakan')->count(), 'icon' => 'rate_review', 'color' => 'yellow'],
            ['title' => 'Lawatan Rumah', 'value' => (clone $baseQuery)->where('status_permohonan', 'Lawatan Rumah')->count(), 'icon' => 'home', 'color' => 'purple'],
            ['title' => 'Lulus', 'value' => (clone $baseQuery)->where('status_permohonan', 'Lulus')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Ditolak', 'value' => (clone $baseQuery)->where('status_permohonan', 'Ditolak')->count(), 'icon' => 'cancel', 'color' => 'red'],
        ];

        // Get programs for filter
        $programs = ProgramKebajikan::where('status_program', 'Aktif')
            ->where('masjid_id', $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id)
            ->get();

        return view('permohonan-bantuan.index', compact('permohonan', 'stats', 'isSuperAdmin', 'programs'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get settings
        $settings = TetapanKebajikan::getSettings($masjidId, [
            'auto_approve_amount',
            'permohonan_cooldown_days',
            'permohonan_max_per_year'
        ]);

        // Get active penerima and programs
        $penerima = PenerimaBantuan::where('masjid_id', $masjidId)
            ->where('status_penerima', 'Aktif')
            ->orderBy('nama_penuh')
            ->get();

        $programs = ProgramKebajikan::where('masjid_id', $masjidId)
            ->where('status_program', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        // Get kategori from database
        $jenisBantuan = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->jenisBantuan()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $keutamaan = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->keutamaan()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('permohonan-bantuan.create', compact('penerima', 'programs', 'settings', 'jenisBantuan', 'keutamaan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        // Get settings for validation
        $settings = TetapanKebajikan::getSettings($masjidId, [
            'permohonan_cooldown_days',
            'permohonan_max_per_year',
            'auto_approve_amount'
        ]);

        $validated = $request->validate([
            'penerima_bantuan_id' => 'required|exists:penerima_bantuan,id',
            'program_kebajikan_id' => 'required|exists:program_kebajikan,id',
            'tarikh_permohonan' => 'required|date',
            'jenis_bantuan' => 'required|in:Tunai,Barangan,Perkhidmatan,Campuran',
            'jumlah_dipohon' => 'nullable|numeric|min:0',
            'tujuan_permohonan' => 'required|string',
            'keutamaan' => 'required|in:Biasa,Sederhana,Tinggi,Kecemasan',
        ]);

        // Check cooldown period
        $cooldownDays = $settings['permohonan_cooldown_days'] ?? 0;
        if ($cooldownDays > 0) {
            $lastPermohonan = PermohonanBantuan::where('penerima_bantuan_id', $request->penerima_bantuan_id)
                ->where('masjid_id', $masjidId)
                ->latest('tarikh_permohonan')
                ->first();

            if ($lastPermohonan) {
                $daysSinceLastApplication = now()->diffInDays($lastPermohonan->tarikh_permohonan);
                if ($daysSinceLastApplication < $cooldownDays) {
                    $remainingDays = $cooldownDays - $daysSinceLastApplication;
                    return back()->withErrors(['penerima_bantuan_id' => "Penerima perlu menunggu {$remainingDays} hari lagi sebelum membuat permohonan baharu."])->withInput();
                }
            }
        }

        // Check max applications per year
        $maxPerYear = $settings['permohonan_max_per_year'] ?? 0;
        if ($maxPerYear > 0) {
            $thisYearCount = PermohonanBantuan::where('penerima_bantuan_id', $request->penerima_bantuan_id)
                ->where('masjid_id', $masjidId)
                ->whereYear('tarikh_permohonan', now()->year)
                ->count();

            if ($thisYearCount >= $maxPerYear) {
                return back()->withErrors(['penerima_bantuan_id' => "Penerima telah mencapai had maksimum {$maxPerYear} permohonan untuk tahun ini."])->withInput();
            }
        }

        $validated['masjid_id'] = $masjidId;
        $validated['no_permohonan'] = PermohonanBantuan::generateNoPermohonan($masjidId);
        
        // Auto-approve if amount is below threshold
        $autoApproveAmount = $settings['auto_approve_amount'] ?? 0;
        if ($autoApproveAmount > 0 && $request->jumlah_dipohon > 0 && $request->jumlah_dipohon <= $autoApproveAmount) {
            $validated['status_permohonan'] = 'Lulus';
            $validated['jumlah_diluluskan'] = $request->jumlah_dipohon;
            $validated['tarikh_keputusan'] = now()->toDateString();
            $validated['diluluskan_oleh'] = $user->id;
            $validated['tarikh_diluluskan'] = now();
            $validated['catatan_kelulusan'] = 'Auto-approved (jumlah di bawah had auto-approve)';
        } else {
            $validated['status_permohonan'] = 'Baharu';
        }
        
        $validated['created_by'] = $user->id;

        $permohonan = PermohonanBantuan::create($validated);

        $message = $validated['status_permohonan'] == 'Lulus' 
            ? 'Permohonan bantuan berjaya dicipta dan diluluskan secara automatik.' 
            : 'Permohonan bantuan berjaya dicipta.';

        return redirect()->route('permohonan-bantuan.index')
            ->with('success', $message);
    }

    public function show(PermohonanBantuan $permohonanBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonanBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $permohonanBantuan->load([
            'masjid',
            'penerimaBantuan',
            'programKebajikan',
            'penyemak',
            'pelulus',
            'penolak',
            'pembatal',
            'creator',
            'updater'
        ]);

        return view('permohonan-bantuan.show', compact('permohonanBantuan'));
    }

    public function edit(PermohonanBantuan $permohonanBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonanBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $masjidId = $permohonanBantuan->masjid_id;

        // Get settings
        $settings = TetapanKebajikan::getSettings($masjidId, [
            'auto_approve_amount',
            'permohonan_cooldown_days',
            'permohonan_max_per_year'
        ]);

        $penerima = PenerimaBantuan::where('masjid_id', $masjidId)
            ->where('status_penerima', 'Aktif')
            ->orderBy('nama_penuh')
            ->get();

        $programs = ProgramKebajikan::where('masjid_id', $masjidId)
            ->where('status_program', 'Aktif')
            ->orderBy('nama_program')
            ->get();

        // Get kategori from database
        $jenisBantuan = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->jenisBantuan()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $keutamaan = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->keutamaan()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('permohonan-bantuan.edit', compact('permohonanBantuan', 'penerima', 'programs', 'settings', 'jenisBantuan', 'keutamaan'));
    }

    public function update(Request $request, PermohonanBantuan $permohonanBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonanBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'penerima_bantuan_id' => 'required|exists:penerima_bantuan,id',
            'program_kebajikan_id' => 'required|exists:program_kebajikan,id',
            'tarikh_permohonan' => 'required|date',
            'jenis_bantuan' => 'required|in:Tunai,Barangan,Perkhidmatan,Campuran',
            'jumlah_dipohon' => 'nullable|numeric|min:0',
            'tujuan_permohonan' => 'required|string',
            'keutamaan' => 'required|in:Biasa,Sederhana,Tinggi,Kecemasan',
        ]);

        $validated['updated_by'] = $user->id;

        $permohonanBantuan->update($validated);

        return redirect()->route('permohonan-bantuan.index')
            ->with('success', 'Permohonan bantuan berjaya dikemaskini.');
    }

    public function destroy(PermohonanBantuan $permohonanBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonanBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $permohonanBantuan->update(['deleted_by' => $user->id]);
        $permohonanBantuan->delete();

        return redirect()->route('permohonan-bantuan.index')
            ->with('success', 'Permohonan bantuan berjaya dipadam.');
    }

    // Workflow Methods
    public function semak(Request $request, $id)
    {
        $permohonan = PermohonanBantuan::findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($permohonan->status_permohonan !== 'Baharu') {
            return back()->with('error', 'Permohonan ini tidak boleh disemak.');
        }

        $permohonan->update([
            'status_permohonan' => 'Dalam Semakan',
            'disemak_oleh' => $user->id,
            'tarikh_disemak' => now(),
            'catatan_semakan' => $request->catatan_semakan,
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Permohonan berjaya disemak.');
    }

    public function lawatan(Request $request, $id)
    {
        $permohonan = PermohonanBantuan::findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($permohonan->status_permohonan !== 'Dalam Semakan') {
            return back()->with('error', 'Permohonan ini tidak boleh dijadualkan lawatan.');
        }

        $validated = $request->validate([
            'tarikh_lawatan' => 'required|date',
            'masa_lawatan' => 'required',
            'pegawai_lawatan' => 'required|string|max:255',
        ]);

        $validated['status_permohonan'] = 'Lawatan Rumah';
        $validated['updated_by'] = $user->id;

        $permohonan->update($validated);

        return back()->with('success', 'Lawatan rumah berjaya dijadualkan.');
    }

    public function lulus(Request $request, $id)
    {
        $permohonan = PermohonanBantuan::findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($permohonan->status_permohonan, ['Dalam Semakan', 'Lawatan Rumah'])) {
            return back()->with('error', 'Permohonan ini tidak boleh diluluskan.');
        }

        $validated = $request->validate([
            'jumlah_diluluskan' => 'required|numeric|min:0',
            'catatan_kelulusan' => 'nullable|string',
        ]);

        $validated['status_permohonan'] = 'Lulus';
        $validated['tarikh_keputusan'] = now()->toDateString();
        $validated['diluluskan_oleh'] = $user->id;
        $validated['tarikh_diluluskan'] = now();
        $validated['updated_by'] = $user->id;

        $permohonan->update($validated);

        return back()->with('success', 'Permohonan berjaya diluluskan.');
    }

    public function tolak(Request $request, $id)
    {
        $permohonan = PermohonanBantuan::findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($permohonan->status_permohonan, ['Baharu', 'Dalam Semakan', 'Lawatan Rumah'])) {
            return back()->with('error', 'Permohonan ini tidak boleh ditolak.');
        }

        $validated = $request->validate([
            'sebab_tolak' => 'required|string',
        ]);

        $validated['status_permohonan'] = 'Ditolak';
        $validated['tarikh_keputusan'] = now()->toDateString();
        $validated['ditolak_oleh'] = $user->id;
        $validated['tarikh_ditolak'] = now();
        $validated['updated_by'] = $user->id;

        $permohonan->update($validated);

        return back()->with('success', 'Permohonan berjaya ditolak.');
    }

    public function batal(Request $request, $id)
    {
        $permohonan = PermohonanBantuan::findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $permohonan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($permohonan->status_permohonan, ['Lulus', 'Ditolak', 'Dibatalkan'])) {
            return back()->with('error', 'Permohonan ini tidak boleh dibatalkan.');
        }

        $validated = $request->validate([
            'sebab_batal' => 'required|string',
        ]);

        $validated['status_permohonan'] = 'Dibatalkan';
        $validated['dibatalkan_oleh'] = $user->id;
        $validated['tarikh_dibatalkan'] = now();
        $validated['updated_by'] = $user->id;

        $permohonan->update($validated);

        return back()->with('success', 'Permohonan berjaya dibatalkan.');
    }
}
