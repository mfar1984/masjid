<?php

namespace App\Http\Controllers;

use App\Models\Asnaf;
use App\Models\TetapanAsnaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AsnafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query
        $baseQuery = Asnaf::query();

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all asnaf
        } else {
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                $baseQuery->whereRaw('1 = 0');
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== '') {
            $baseQuery->where('status', $request->status);
        }

        if ($request->filled('kategori_asnaf') && $request->kategori_asnaf !== '') {
            $baseQuery->where('kategori_asnaf', $request->kategori_asnaf);
        }

        // Get settings
        $masjidId = $user->isSuperAdmin() ? ($request->masjid_id ?? $user->masjid_id) : $user->masjid_id;
        $recordsPerPage = TetapanAsnaf::get('records_per_page', 10, $masjidId);
        
        // Get paginated results
        $asnaf = $baseQuery->orderBy('nama')->paginate($recordsPerPage);

        // Build stats - SEPARATE query
        $statsQuery = Asnaf::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalAsnaf = (clone $statsQuery)->count();
        $diluluskan = (clone $statsQuery)->where('status', 'Diluluskan')->count();
        $menunggu = (clone $statsQuery)->where('status', 'Menunggu')->count();
        $ditolak = (clone $statsQuery)->where('status', 'Ditolak')->count();
        $digantung = (clone $statsQuery)->where('status', 'Digantung')->count();
        $dalamSemakan = (clone $statsQuery)->where('status', 'Dalam Semakan')->count();

        $stats = [
            [
                'title' => 'Jumlah Asnaf',
                'value' => $totalAsnaf,
                'icon' => 'people',
                'color' => 'blue'
            ],
            [
                'title' => 'Diluluskan',
                'value' => $diluluskan,
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Menunggu',
                'value' => $menunggu,
                'icon' => 'pending',
                'color' => 'orange'
            ],
            [
                'title' => 'Ditolak',
                'value' => $ditolak,
                'icon' => 'close',
                'color' => 'red'
            ],
            [
                'title' => 'Digantung',
                'value' => $digantung,
                'icon' => 'pause_circle',
                'color' => 'purple'
            ],
            [
                'title' => 'Dalam Semakan',
                'value' => $dalamSemakan,
                'icon' => 'rate_review',
                'color' => 'blue'
            ]
        ];

        return view('asnaf.index', compact('asnaf', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        // Get kategori data from database
        $bangsa = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->bangsa()->aktif()->orderBy('urutan')->get();
        $agama = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->agama()->aktif()->orderBy('urutan')->get();
        $statusPerkahwinan = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->statusPerkahwinan()->aktif()->orderBy('urutan')->get();
        $negeri = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->negeri()->aktif()->orderBy('urutan')->get();
        $kategoriAsnafList = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->kategoriAsnaf()->aktif()->orderBy('urutan')->get();
        $statusPekerjaan = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->statusPekerjaan()->aktif()->orderBy('urutan')->get();
        $statusKesihatan = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->statusKesihatan()->aktif()->orderBy('urutan')->get();

        return view('asnaf.create', compact(
            'bangsa',
            'agama',
            'statusPerkahwinan',
            'negeri',
            'kategoriAsnafList',
            'statusPekerjaan',
            'statusKesihatan'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation
        $validated = $request->validate([
            // Maklumat Peribadi
            'nama' => 'required|string|max:255',
            'no_ic' => 'required|string|size:14|unique:asnaf,no_ic',
            'jantina' => 'required|in:Lelaki,Perempuan',
            'bangsa' => 'required|string|max:100',
            'agama' => 'required|string|max:100',
            'status_perkahwinan' => 'required|in:Bujang,Berkahwin,Janda,Duda',
            'telefon' => 'required|string|max:15',
            'telefon_alternatif' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',

            // Alamat IC
            'alamat_ic' => 'required|string',
            'poskod_ic' => 'required|string|max:10',
            'bandar_ic' => 'required|string|max:100',
            'negeri_ic' => 'required|string|max:100',

            // Alamat Surat
            'alamat_surat' => 'required|string',
            'poskod_surat' => 'required|string|max:10',
            'bandar_surat' => 'required|string|max:100',
            'negeri_surat' => 'required|string|max:100',

            // Alamat Kediaman
            'alamat_kediaman' => 'required|string',
            'poskod_kediaman' => 'required|string|max:10',
            'bandar_kediaman' => 'required|string|max:100',
            'negeri_kediaman' => 'required|string|max:100',
            'status_kediaman' => 'required|in:Milik Sendiri,Sewa,Menumpang,Lain-lain',

            // Waris
            'nama_waris' => 'required|string|max:255',
            'hubungan_waris' => 'required|string|max:100',
            'no_ic_waris' => 'required|string|size:14',
            'telefon_waris' => 'required|string|max:15',
            'alamat_waris' => 'nullable|string',

            // Kategori Asnaf
            'kategori_asnaf' => 'required|in:Fakir,Miskin,Amil,Muallaf,Riqab,Gharimin,Fisabilillah,Ibnu Sabil',
            'sebab_permohonan' => 'required|string',

            // Pekerjaan & Pendapatan
            'status_pekerjaan' => 'required|in:Bekerja,Tidak Bekerja,Pesara,Pelajar',
            'nama_majikan' => 'nullable|string|max:255',
            'jawatan' => 'nullable|string|max:255',
            'pendapatan_bulanan' => 'required|numeric|min:0',
            'pendapatan_pasangan' => 'nullable|numeric|min:0',
            'pendapatan_lain' => 'nullable|numeric|min:0',
            'sumber_pendapatan_lain' => 'nullable|string|max:255',

            // Tanggungan
            'bilangan_tanggungan' => 'required|integer|min:0',
            'jumlah_perbelanjaan' => 'required|numeric|min:0',

            // Hutang
            'ada_hutang' => 'required|boolean',
            'jumlah_hutang' => 'nullable|numeric|min:0',
            'bayaran_hutang_bulanan' => 'nullable|numeric|min:0',
            'sebab_berhutang' => 'nullable|string',

            // Kesihatan
            'status_kesihatan' => 'required|in:Sihat,Sakit Kronik,OKU',
            'jenis_penyakit' => 'nullable|string|max:255',
            'kos_perubatan_bulanan' => 'nullable|numeric|min:0',

            // Aset
            'pemilikan_rumah' => 'required|in:Ada,Tiada',
            'pemilikan_kenderaan' => 'required|in:Ada,Tiada',
            'simpanan_bank' => 'nullable|numeric|min:0',

            // Dokumen
            'ic_depan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_belakang' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_waris' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'slip_gaji' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'penyata_bank' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bil_utiliti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_sokongan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'masjid_id' => $user->isSuperAdmin() ? 'required|exists:masjids,id' : 'nullable',
        ]);

        // WAJIB: Auto-assign masjid_id
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        }

        // Handle file uploads
        if ($request->hasFile('ic_depan')) {
            $validated['ic_depan_path'] = $request->file('ic_depan')->store('asnaf/ic', 'public');
        }
        if ($request->hasFile('ic_belakang')) {
            $validated['ic_belakang_path'] = $request->file('ic_belakang')->store('asnaf/ic', 'public');
        }
        if ($request->hasFile('ic_waris')) {
            $validated['ic_waris_path'] = $request->file('ic_waris')->store('asnaf/waris', 'public');
        }
        if ($request->hasFile('slip_gaji')) {
            $validated['slip_gaji_path'] = $request->file('slip_gaji')->store('asnaf/dokumen', 'public');
        }
        if ($request->hasFile('penyata_bank')) {
            $validated['penyata_bank_path'] = $request->file('penyata_bank')->store('asnaf/dokumen', 'public');
        }
        if ($request->hasFile('bil_utiliti')) {
            $validated['bil_utiliti_path'] = $request->file('bil_utiliti')->store('asnaf/dokumen', 'public');
        }
        if ($request->hasFile('surat_sokongan')) {
            $validated['surat_sokongan_path'] = $request->file('surat_sokongan')->store('asnaf/dokumen', 'public');
        }

        $validated['status'] = 'Menunggu';
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        Asnaf::create($validated);

        return redirect()->route('asnaf.index')
            ->with('success', 'Permohonan Asnaf berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        return view('asnaf.show', compact('asnaf'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $masjidId = $user->masjid_id;

        // Get kategori data from database
        $bangsa = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->bangsa()->aktif()->orderBy('urutan')->get();
        $agama = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->agama()->aktif()->orderBy('urutan')->get();
        $statusPerkahwinan = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->statusPerkahwinan()->aktif()->orderBy('urutan')->get();
        $negeri = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->negeri()->aktif()->orderBy('urutan')->get();
        $kategoriAsnafList = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->kategoriAsnaf()->aktif()->orderBy('urutan')->get();
        $statusPekerjaan = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->statusPekerjaan()->aktif()->orderBy('urutan')->get();
        $statusKesihatan = \App\Models\KategoriAsnaf::where('masjid_id', $masjidId)->statusKesihatan()->aktif()->orderBy('urutan')->get();

        return view('asnaf.edit', compact(
            'asnaf',
            'bangsa',
            'agama',
            'statusPerkahwinan',
            'negeri',
            'kategoriAsnafList',
            'statusPekerjaan',
            'statusKesihatan'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Validation
        $validated = $request->validate([
            // Maklumat Peribadi
            'nama' => 'required|string|max:255',
            'no_ic' => ['required', 'string', 'size:14', Rule::unique('asnaf')->ignore($asnaf->id)],
            'jantina' => 'required|in:Lelaki,Perempuan',
            'bangsa' => 'required|string|max:100',
            'agama' => 'required|string|max:100',
            'status_perkahwinan' => 'required|in:Bujang,Berkahwin,Janda,Duda',
            'telefon' => 'required|string|max:15',
            'telefon_alternatif' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',

            // Alamat IC
            'alamat_ic' => 'required|string',
            'poskod_ic' => 'required|string|max:10',
            'bandar_ic' => 'required|string|max:100',
            'negeri_ic' => 'required|string|max:100',

            // Alamat Surat
            'alamat_surat' => 'required|string',
            'poskod_surat' => 'required|string|max:10',
            'bandar_surat' => 'required|string|max:100',
            'negeri_surat' => 'required|string|max:100',

            // Alamat Kediaman
            'alamat_kediaman' => 'required|string',
            'poskod_kediaman' => 'required|string|max:10',
            'bandar_kediaman' => 'required|string|max:100',
            'negeri_kediaman' => 'required|string|max:100',
            'status_kediaman' => 'required|in:Milik Sendiri,Sewa,Menumpang,Lain-lain',

            // Waris
            'nama_waris' => 'required|string|max:255',
            'hubungan_waris' => 'required|string|max:100',
            'no_ic_waris' => 'required|string|size:14',
            'telefon_waris' => 'required|string|max:15',
            'alamat_waris' => 'nullable|string',

            // Kategori Asnaf
            'kategori_asnaf' => 'required|in:Fakir,Miskin,Amil,Muallaf,Riqab,Gharimin,Fisabilillah,Ibnu Sabil',
            'sebab_permohonan' => 'required|string',

            // Pekerjaan & Pendapatan
            'status_pekerjaan' => 'required|in:Bekerja,Tidak Bekerja,Pesara,Pelajar',
            'nama_majikan' => 'nullable|string|max:255',
            'jawatan' => 'nullable|string|max:255',
            'pendapatan_bulanan' => 'required|numeric|min:0',
            'pendapatan_pasangan' => 'nullable|numeric|min:0',
            'pendapatan_lain' => 'nullable|numeric|min:0',
            'sumber_pendapatan_lain' => 'nullable|string|max:255',

            // Tanggungan
            'bilangan_tanggungan' => 'required|integer|min:0',
            'jumlah_perbelanjaan' => 'required|numeric|min:0',

            // Hutang
            'ada_hutang' => 'required|boolean',
            'jumlah_hutang' => 'nullable|numeric|min:0',
            'bayaran_hutang_bulanan' => 'nullable|numeric|min:0',
            'sebab_berhutang' => 'nullable|string',

            // Kesihatan
            'status_kesihatan' => 'required|in:Sihat,Sakit Kronik,OKU',
            'jenis_penyakit' => 'nullable|string|max:255',
            'kos_perubatan_bulanan' => 'nullable|numeric|min:0',

            // Aset
            'pemilikan_rumah' => 'required|in:Ada,Tiada',
            'pemilikan_kenderaan' => 'required|in:Ada,Tiada',
            'simpanan_bank' => 'nullable|numeric|min:0',

            // Dokumen
            'ic_depan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_belakang' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_waris' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'slip_gaji' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'penyata_bank' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bil_utiliti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_sokongan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'masjid_id' => 'required|exists:masjids,id',
        ]);

        // Handle file uploads
        if ($request->hasFile('ic_depan')) {
            $validated['ic_depan_path'] = $request->file('ic_depan')->store('asnaf/ic', 'public');
        }
        if ($request->hasFile('ic_belakang')) {
            $validated['ic_belakang_path'] = $request->file('ic_belakang')->store('asnaf/ic', 'public');
        }
        if ($request->hasFile('ic_waris')) {
            $validated['ic_waris_path'] = $request->file('ic_waris')->store('asnaf/waris', 'public');
        }
        if ($request->hasFile('slip_gaji')) {
            $validated['slip_gaji_path'] = $request->file('slip_gaji')->store('asnaf/dokumen', 'public');
        }
        if ($request->hasFile('penyata_bank')) {
            $validated['penyata_bank_path'] = $request->file('penyata_bank')->store('asnaf/dokumen', 'public');
        }
        if ($request->hasFile('bil_utiliti')) {
            $validated['bil_utiliti_path'] = $request->file('bil_utiliti')->store('asnaf/dokumen', 'public');
        }
        if ($request->hasFile('surat_sokongan')) {
            $validated['surat_sokongan_path'] = $request->file('surat_sokongan')->store('asnaf/dokumen', 'public');
        }

        $validated['updated_by'] = $user->id;

        $asnaf->update($validated);

        return redirect()->route('asnaf.index')
            ->with('success', 'Maklumat Asnaf berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $asnaf->delete();

        return redirect()->route('asnaf.index')
            ->with('success', 'Asnaf berjaya dipadam.');
    }

    /**
     * Approve an asnaf application
     */
    public function approve(Request $request, Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $request->validate([
            'catatan_kelulusan' => 'nullable|string',
            'jumlah_diluluskan' => 'required|numeric|min:0',
        ]);

        $asnaf->update([
            'status' => 'Diluluskan',
            'diluluskan_oleh' => $user->id,
            'tarikh_diluluskan' => now(),
            'catatan_kelulusan' => $request->catatan_kelulusan,
            'jumlah_diluluskan' => $request->jumlah_diluluskan,
            'updated_by' => $user->id,
        ]);

        return redirect()->back()
            ->with('success', 'Permohonan Asnaf ' . $asnaf->nama . ' berjaya diluluskan.');
    }

    /**
     * Reject an asnaf application
     */
    public function reject(Request $request, Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $reason = $request->input('reason', 'Tiada sebab dinyatakan');

        $asnaf->update([
            'status' => 'Ditolak',
            'catatan_kelulusan' => 'Ditolak oleh ' . $user->name . '. Sebab: ' . $reason,
            'updated_by' => $user->id,
        ]);

        return redirect()->back()
            ->with('success', 'Permohonan Asnaf ' . $asnaf->nama . ' telah ditolak.');
    }

    /**
     * Suspend an asnaf
     */
    public function suspend(Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if ($asnaf->status === 'Digantung') {
            return redirect()->route('asnaf.index')
                ->with('error', 'Asnaf sudah digantung.');
        }

        $asnaf->update([
            'status' => 'Digantung',
            'updated_by' => $user->id,
        ]);

        return redirect()->route('asnaf.index')
            ->with('success', 'Asnaf ' . $asnaf->nama . ' berjaya digantung.');
    }

    /**
     * Reactivate (unsuspend) an asnaf
     */
    public function reactivate(Asnaf $asnaf)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($asnaf->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if ($asnaf->status !== 'Digantung') {
            return redirect()->route('asnaf.index')
                ->with('error', 'Asnaf tidak dalam status digantung.');
        }

        $asnaf->update([
            'status' => 'Diluluskan',
            'updated_by' => $user->id,
        ]);

        return redirect()->route('asnaf.index')
            ->with('success', 'Asnaf ' . $asnaf->nama . ' berjaya diaktifkan semula.');
    }

    /**
     * Export asnaf data
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Asnaf::query();

        // Apply masjid isolation
        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_asnaf') && $request->kategori_asnaf !== '') {
            $query->where('kategori_asnaf', $request->kategori_asnaf);
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $asnaf = $query->orderBy('nama')->get();

        // Generate CSV
        $filename = 'asnaf_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($asnaf) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Nama',
                'No. IC',
                'Telefon',
                'Kategori Asnaf',
                'Status',
                'Pendapatan Bulanan',
                'Jumlah Diluluskan',
                'Tarikh Diluluskan'
            ]);

            // Add data
            foreach ($asnaf as $row) {
                fputcsv($file, [
                    $row->nama,
                    $row->no_ic,
                    $row->telefon,
                    $row->kategori_asnaf,
                    $row->status,
                    'RM ' . number_format($row->pendapatan_bulanan, 2),
                    $row->jumlah_diluluskan ? 'RM ' . number_format($row->jumlah_diluluskan, 2) : '-',
                    $row->tarikh_diluluskan ? $row->tarikh_diluluskan->format('d/m/Y') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
