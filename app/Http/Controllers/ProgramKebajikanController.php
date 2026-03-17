<?php

namespace App\Http\Controllers;

use App\Models\ProgramKebajikan;
use App\Models\TetapanKebajikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramKebajikanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;

        // Get items per page from settings
        $settings = \App\Models\TetapanKebajikan::getSettings($masjidId, ['items_per_page']);
        $perPage = $settings['items_per_page'] ?? 10;

        $query = ProgramKebajikan::with('masjid');

        // Multi-masjid isolation
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('kategori_program')) {
            $query->where('kategori_program', $request->kategori_program);
        }

        if ($request->filled('jenis_bantuan')) {
            $query->where('jenis_bantuan', $request->jenis_bantuan);
        }

        if ($request->filled('status_program')) {
            $query->where('status_program', $request->status_program);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kod_program', 'like', "%{$search}%")
                    ->orWhere('nama_program', 'like', "%{$search}%");
            });
        }

        $programs = $query->latest()->paginate($perPage);

        // Stats
        $baseQuery = ProgramKebajikan::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $stats = [
            ['title' => 'Total Program', 'value' => (clone $baseQuery)->count(), 'icon' => 'category', 'color' => 'blue'],
            ['title' => 'Program Aktif', 'value' => (clone $baseQuery)->where('status_program', 'Aktif')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Tidak Aktif', 'value' => (clone $baseQuery)->where('status_program', 'Tidak Aktif')->count(), 'icon' => 'cancel', 'color' => 'orange'],
            ['title' => 'Program Tamat', 'value' => (clone $baseQuery)->where('status_program', 'Tamat')->count(), 'icon' => 'event_busy', 'color' => 'red'],
        ];

        return view('program-kebajikan.index', compact('programs', 'stats', 'isSuperAdmin'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get had bantuan settings
        $settings = TetapanKebajikan::getSettings($masjidId, [
            'had_pendidikan_min', 'had_pendidikan_max',
            'had_kesihatan_min', 'had_kesihatan_max',
            'had_kecemasan_min', 'had_kecemasan_max',
            'had_kebajikan_min', 'had_kebajikan_max'
        ]);

        // Get jenis program from database
        $jenisProgram = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->jenisProgram()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        // Get tempoh bantuan from database
        $tempohBantuan = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->tempohBantuan()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('program-kebajikan.create', compact('settings', 'jenisProgram', 'tempohBantuan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        // Get had bantuan settings for validation
        $settings = \App\Models\TetapanKebajikan::getSettings($masjidId, [
            'had_pendidikan_min', 'had_pendidikan_max',
            'had_kesihatan_min', 'had_kesihatan_max',
            'had_kecemasan_min', 'had_kecemasan_max',
            'had_kebajikan_min', 'had_kebajikan_max'
        ]);

        $validated = $request->validate([
            'nama_program' => 'required|max:255',
            'kategori_program' => 'required|in:Pendidikan,Kesihatan,Kecemasan,Kebajikan Am,Anak Yatim,OKU,Warga Emas,Ibu Tunggal,Lain-lain',
            'jenis_bantuan' => 'required|in:Tunai,Barangan,Perkhidmatan,Campuran',
            'had_maksimum' => 'nullable|numeric|min:0',
            'had_minimum' => 'nullable|numeric|min:0',
            'tempoh_bantuan' => 'required|in:Sekali,Bulanan,Tahunan,Mengikut Keperluan',
            'syarat_kelayakan' => 'nullable|string',
            'dokumen_diperlukan' => 'nullable|string',
            'status_program' => 'required|in:Aktif,Tidak Aktif,Tamat',
            'tarikh_mula' => 'nullable|date',
            'tarikh_tamat' => 'nullable|date|after_or_equal:tarikh_mula',
            'catatan' => 'nullable|string',
        ]);

        // Validate had bantuan based on kategori
        if ($request->filled('had_minimum') || $request->filled('had_maksimum')) {
            $kategori = strtolower(str_replace(' ', '_', $request->kategori_program));
            $minKey = "had_{$kategori}_min";
            $maxKey = "had_{$kategori}_max";

            if (isset($settings[$minKey]) && $request->had_minimum < $settings[$minKey]) {
                return back()->withErrors(['had_minimum' => "Had minimum mestilah sekurang-kurangnya RM " . number_format($settings[$minKey], 2)])->withInput();
            }

            if (isset($settings[$maxKey]) && $request->had_maksimum > $settings[$maxKey]) {
                return back()->withErrors(['had_maksimum' => "Had maksimum tidak boleh melebihi RM " . number_format($settings[$maxKey], 2)])->withInput();
            }
        }

        $validated['masjid_id'] = $masjidId;
        $validated['kod_program'] = ProgramKebajikan::generateKodProgram($masjidId);
        $validated['created_by'] = $user->id;

        ProgramKebajikan::create($validated);

        return redirect()->route('program-kebajikan.index')
            ->with('success', 'Program kebajikan berjaya dicipta.');
    }

    public function show(ProgramKebajikan $programKebajikan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $programKebajikan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $programKebajikan->load('masjid', 'creator', 'updater');

        return view('program-kebajikan.show', compact('programKebajikan'));
    }

    public function edit(ProgramKebajikan $programKebajikan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $programKebajikan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        // Get had bantuan settings
        $settings = TetapanKebajikan::getSettings($programKebajikan->masjid_id, [
            'had_pendidikan_min', 'had_pendidikan_max',
            'had_kesihatan_min', 'had_kesihatan_max',
            'had_kecemasan_min', 'had_kecemasan_max',
            'had_kebajikan_min', 'had_kebajikan_max'
        ]);

        // Get jenis program from database
        $jenisProgram = \App\Models\KategoriKebajikan::where('masjid_id', $programKebajikan->masjid_id)
            ->jenisProgram()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        // Get tempoh bantuan from database
        $tempohBantuan = \App\Models\KategoriKebajikan::where('masjid_id', $programKebajikan->masjid_id)
            ->tempohBantuan()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('program-kebajikan.edit', compact('programKebajikan', 'settings', 'jenisProgram', 'tempohBantuan'));
    }

    public function update(Request $request, ProgramKebajikan $programKebajikan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $programKebajikan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama_program' => 'required|max:255',
            'kategori_program' => 'required|in:Pendidikan,Kesihatan,Kecemasan,Kebajikan Am,Anak Yatim,OKU,Warga Emas,Ibu Tunggal,Lain-lain',
            'jenis_bantuan' => 'required|in:Tunai,Barangan,Perkhidmatan,Campuran',
            'had_maksimum' => 'nullable|numeric|min:0',
            'had_minimum' => 'nullable|numeric|min:0',
            'tempoh_bantuan' => 'required|in:Sekali,Bulanan,Tahunan,Mengikut Keperluan',
            'syarat_kelayakan' => 'nullable|string',
            'dokumen_diperlukan' => 'nullable|string',
            'status_program' => 'required|in:Aktif,Tidak Aktif,Tamat',
            'tarikh_mula' => 'nullable|date',
            'tarikh_tamat' => 'nullable|date|after_or_equal:tarikh_mula',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $programKebajikan->update($validated);

        return redirect()->route('program-kebajikan.index')
            ->with('success', 'Program kebajikan berjaya dikemaskini.');
    }

    public function destroy(ProgramKebajikan $programKebajikan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $programKebajikan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $programKebajikan->update(['deleted_by' => $user->id]);
        $programKebajikan->delete();

        return redirect()->route('program-kebajikan.index')
            ->with('success', 'Program kebajikan berjaya dipadam.');
    }
}
