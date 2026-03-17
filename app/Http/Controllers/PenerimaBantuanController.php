<?php

namespace App\Http\Controllers;

use App\Models\PenerimaBantuan;
use App\Models\TetapanKebajikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenerimaBantuanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $query = PenerimaBantuan::with('masjid');

        // Multi-masjid isolation
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('status_penerima')) {
            $query->where('status_penerima', $request->status_penerima);
        }

        if ($request->filled('kategori_penerima')) {
            $query->where('kategori_penerima', 'like', '%' . $request->kategori_penerima . '%');
        }

        if ($request->filled('status_oku')) {
            $query->where('status_oku', $request->status_oku);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('nama_penuh', 'like', "%{$search}%")
                    ->orWhere('no_kp', 'like', "%{$search}%");
            });
        }

        // Get items per page from settings
        $settings = TetapanKebajikan::getSettings($isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id, ['items_per_page']);
        $perPage = $settings['items_per_page'] ?? 10;

        $penerima = $query->latest()->paginate($perPage);

        // Stats
        $baseQuery = PenerimaBantuan::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $stats = [
            ['title' => 'Total Penerima', 'value' => (clone $baseQuery)->count(), 'icon' => 'people', 'color' => 'blue'],
            ['title' => 'Penerima Aktif', 'value' => (clone $baseQuery)->where('status_penerima', 'Aktif')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Tidak Aktif', 'value' => (clone $baseQuery)->where('status_penerima', 'Tidak Aktif')->count(), 'icon' => 'cancel', 'color' => 'orange'],
            ['title' => 'Total Tanggungan', 'value' => (clone $baseQuery)->sum('bilangan_tanggungan'), 'icon' => 'family_restroom', 'color' => 'purple'],
        ];

        return view('penerima-bantuan.index', compact('penerima', 'stats', 'isSuperAdmin', 'settings'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get kategori penerima settings
        $settings = TetapanKebajikan::getSettings($masjidId, [
            'enable_oku',
            'enable_yatim',
            'enable_ibu_tunggal',
            'enable_warga_emas'
        ]);

        // Get kategori data from database
        $bangsa = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->bangsa()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $agama = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->agama()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $jenisKediaman = \App\Models\KategoriKebajikan::where('masjid_id', $masjidId)
            ->jenisKediaman()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('penerima-bantuan.create', compact('settings', 'bangsa', 'agama', 'jenisKediaman'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'nama_penuh' => 'required|max:255',
            'no_kp' => 'required|size:12|unique:penerima_bantuan,no_kp',
            'jantina' => 'required|in:Lelaki,Perempuan',
            'tarikh_lahir' => 'required|date|before:today',
            'status_perkahwinan' => 'required|in:Bujang,Berkahwin,Duda,Janda,Bercerai',
            'no_telefon' => 'required|max:20',
            'alamat_1' => 'required|max:255',
            'poskod' => 'required|size:5',
            'bandar' => 'required|max:100',
            'negeri' => 'required|max:100',
            'status_pekerjaan' => 'required|in:Bekerja,Tidak Bekerja,Pesara,OKU,Pelajar,Suri Rumah',
            'jenis_kediaman' => 'required|in:Rumah Sendiri,Rumah Sewa,Rumah Keluarga,Rumah Pangsa,Rumah Setinggan,Lain-lain',
            'status_penerima' => 'required|in:Aktif,Tidak Aktif,Tamat',
        ]);

        $validated['masjid_id'] = $masjidId;
        $validated['no_pendaftaran'] = PenerimaBantuan::generateNoPendaftaran($masjidId);
        $validated['created_by'] = $user->id;

        // Calculate age
        if ($request->filled('tarikh_lahir')) {
            $validated['umur'] = \Carbon\Carbon::parse($request->tarikh_lahir)->age;
        }

        // Calculate total income
        $totalIncome = 0;
        if ($request->filled('pendapatan_bulanan')) {
            $totalIncome += $request->pendapatan_bulanan;
        }
        if ($request->filled('pendapatan_lain')) {
            $totalIncome += $request->pendapatan_lain;
        }
        if ($request->filled('pendapatan_pasangan')) {
            $totalIncome += $request->pendapatan_pasangan;
        }
        $validated['jumlah_pendapatan'] = $totalIncome;

        $penerima = PenerimaBantuan::create($validated);

        return redirect()->route('penerima-bantuan.index')
            ->with('success', 'Penerima bantuan berjaya didaftarkan.');
    }

    public function show(PenerimaBantuan $penerimaBantuan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $penerimaBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $penerimaBantuan->load('masjid', 'creator', 'updater', 'permohonanBantuan');

        return view('penerima-bantuan.show', compact('penerimaBantuan'));
    }

    public function edit(PenerimaBantuan $penerimaBantuan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $penerimaBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        // Get kategori penerima settings
        $settings = TetapanKebajikan::getSettings($penerimaBantuan->masjid_id, [
            'enable_oku',
            'enable_yatim',
            'enable_ibu_tunggal',
            'enable_warga_emas'
        ]);

        // Get kategori data from database
        $bangsa = \App\Models\KategoriKebajikan::where('masjid_id', $penerimaBantuan->masjid_id)
            ->bangsa()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $agama = \App\Models\KategoriKebajikan::where('masjid_id', $penerimaBantuan->masjid_id)
            ->agama()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $jenisKediaman = \App\Models\KategoriKebajikan::where('masjid_id', $penerimaBantuan->masjid_id)
            ->jenisKediaman()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('penerima-bantuan.edit', compact('penerimaBantuan', 'settings', 'bangsa', 'agama', 'jenisKediaman'));
    }

    public function update(Request $request, PenerimaBantuan $penerimaBantuan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $penerimaBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama_penuh' => 'required|max:255',
            'no_kp' => 'required|size:12|unique:penerima_bantuan,no_kp,' . $penerimaBantuan->id,
            'jantina' => 'required|in:Lelaki,Perempuan',
            'tarikh_lahir' => 'required|date|before:today',
            'status_perkahwinan' => 'required|in:Bujang,Berkahwin,Duda,Janda,Bercerai',
            'no_telefon' => 'required|max:20',
            'alamat_1' => 'required|max:255',
            'poskod' => 'required|size:5',
            'bandar' => 'required|max:100',
            'negeri' => 'required|max:100',
            'status_pekerjaan' => 'required|in:Bekerja,Tidak Bekerja,Pesara,OKU,Pelajar,Suri Rumah',
            'jenis_kediaman' => 'required|in:Rumah Sendiri,Rumah Sewa,Rumah Keluarga,Rumah Pangsa,Rumah Setinggan,Lain-lain',
            'status_penerima' => 'required|in:Aktif,Tidak Aktif,Tamat',
        ]);

        $validated['updated_by'] = $user->id;

        // Calculate age
        if ($request->filled('tarikh_lahir')) {
            $validated['umur'] = \Carbon\Carbon::parse($request->tarikh_lahir)->age;
        }

        // Calculate total income
        $totalIncome = 0;
        if ($request->filled('pendapatan_bulanan')) {
            $totalIncome += $request->pendapatan_bulanan;
        }
        if ($request->filled('pendapatan_lain')) {
            $totalIncome += $request->pendapatan_lain;
        }
        if ($request->filled('pendapatan_pasangan')) {
            $totalIncome += $request->pendapatan_pasangan;
        }
        $validated['jumlah_pendapatan'] = $totalIncome;

        $penerimaBantuan->update($validated);

        return redirect()->route('penerima-bantuan.index')
            ->with('success', 'Penerima bantuan berjaya dikemaskini.');
    }

    public function destroy(PenerimaBantuan $penerimaBantuan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $penerimaBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $penerimaBantuan->update(['deleted_by' => $user->id]);
        $penerimaBantuan->delete();

        return redirect()->route('penerima-bantuan.index')
            ->with('success', 'Penerima bantuan berjaya dipadam.');
    }
}
