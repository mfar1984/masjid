<?php

namespace App\Http\Controllers;

use App\Models\Perbelanjaan;
use App\Models\KategoriKewangan;
use App\Models\AkaunBank;
use App\Models\TransaksiKewangan;
use App\Models\TetapanKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerbelanjaanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;

        $perPage = TetapanKewangan::get('records_per_page', 10, $masjidId);

        $query = Perbelanjaan::with(['masjid', 'kategoriKewangan', 'akaunBank']);

        // Multi-masjid isolation
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('jenis_perbelanjaan')) {
            $query->where('jenis_perbelanjaan', $request->jenis_perbelanjaan);
        }

        if ($request->filled('status_kelulusan')) {
            $query->where('status_kelulusan', $request->status_kelulusan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_perbelanjaan', 'like', "%{$search}%")
                    ->orWhere('pembekal_vendor', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $perbelanjaan = $query->latest('tarikh_perbelanjaan')->paginate($perPage);

        // Stats
        $baseQuery = Perbelanjaan::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $stats = [
            'utiliti_bil' => (clone $baseQuery)->utilitiBil()->diluluskan()->sum('jumlah'),
            'penyelenggaraan' => (clone $baseQuery)->penyelenggaraan()->diluluskan()->sum('jumlah'),
            'gaji_elaun' => (clone $baseQuery)->gajiElaun()->diluluskan()->sum('jumlah'),
            'perbelanjaan_lain' => (clone $baseQuery)->perbelanjaanLain()->diluluskan()->sum('jumlah'),
        ];

        return view('perbelanjaan.index', compact('perbelanjaan', 'stats', 'isSuperAdmin'));
    }

    public function utilitiBil()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->perbelanjaan()->aktif()->orderBy('nama_kategori')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();
        $jenisBil = KategoriKewangan::where('masjid_id', $masjidId)->where('jenis_kategori', 'jenis_bil')->where('status', 'Aktif')->orderBy('urutan')->get();

        return view('perbelanjaan.utiliti-bil', compact('kategori', 'akaunBank', 'kaedahBayaran', 'jenisBil'));
    }

    public function penyelenggaraan()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->perbelanjaan()->aktif()->orderBy('nama_kategori')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('perbelanjaan.penyelenggaraan', compact('kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function gajiElaun()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->perbelanjaan()->aktif()->orderBy('nama_kategori')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('perbelanjaan.gaji-elaun', compact('kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function perbelanjaanLain()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->perbelanjaan()->aktif()->orderBy('nama_kategori')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('perbelanjaan.perbelanjaan-lain', compact('kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'tarikh_perbelanjaan' => 'required|date',
            'jenis_perbelanjaan' => 'required|in:Utiliti & Bil,Penyelenggaraan,Gaji & Elaun,Perbelanjaan Lain',
            'jenis_bil' => 'nullable|in:Elektrik,Air,Internet,Telefon,Lain-lain',
            'no_bil' => 'nullable|max:100',
            'bacaan_meter_lama' => 'nullable|numeric',
            'bacaan_meter_baru' => 'nullable|numeric',
            'tarikh_akhir' => 'nullable|date',
            'jenis_penyelenggaraan' => 'nullable|in:Bangunan,Elektrik,Paip,Penyaman Udara,Landskap,Lain-lain',
            'kontraktor' => 'nullable|max:255',
            'no_telefon_kontraktor' => 'nullable|max:20',
            'kerja_dilakukan' => 'nullable|string',
            'nama_kakitangan' => 'nullable|max:255',
            'jawatan' => 'nullable|max:255',
            'gaji_pokok' => 'nullable|numeric|min:0',
            'elaun' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'kategori_kewangan_id' => 'required|exists:kategori_kewangan,id',
            'akaun_bank_id' => 'required|exists:akaun_bank,id',
            'jumlah' => 'required|numeric|min:0.01',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Online Transfer,Kad Kredit/Debit,Lain-lain',
            'no_rujukan' => 'nullable|max:100',
            'pembekal_vendor' => 'nullable|max:255',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|array',
            'dokumen.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate perbelanjaan number
            $validated['no_perbelanjaan'] = Perbelanjaan::generateNoPerbelanjaan($masjidId);
            $validated['masjid_id'] = $masjidId;
            $validated['status_kelulusan'] = 'Pending';
            $validated['created_by'] = $user->id;

            // Handle file uploads
            if ($request->hasFile('dokumen')) {
                $dokumenPaths = [];
                foreach ($request->file('dokumen') as $file) {
                    $dokumenPaths[] = $file->store('perbelanjaan', 'public');
                }
                $validated['dokumen'] = $dokumenPaths;
            }

            $perbelanjaan = Perbelanjaan::create($validated);

            DB::commit();

            return redirect()->route('perbelanjaan.index')
                ->with('success', 'Perbelanjaan berjaya ditambah dan menunggu kelulusan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan perbelanjaan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Perbelanjaan $perbelanjaan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $perbelanjaan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $perbelanjaan->load(['masjid', 'kategoriKewangan', 'akaunBank', 'transaksiKewangan', 'diluluskanOleh', 'createdBy', 'updatedBy']);

        return view('perbelanjaan.show', compact('perbelanjaan'));
    }

    public function edit(Perbelanjaan $perbelanjaan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $perbelanjaan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $masjidId = $perbelanjaan->masjid_id;
        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->perbelanjaan()->aktif()->orderBy('nama_kategori')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();

        return view('perbelanjaan.edit', compact('perbelanjaan', 'kategori', 'akaunBank'));
    }

    public function update(Request $request, Perbelanjaan $perbelanjaan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $perbelanjaan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'tarikh_perbelanjaan' => 'required|date',
            'pembekal_vendor' => 'nullable|max:255',
            'kategori_kewangan_id' => 'required|exists:kategori_kewangan,id',
            'akaun_bank_id' => 'required|exists:akaun_bank,id',
            'jumlah' => 'required|numeric|min:0.01',
            'kaedah_bayaran' => 'required|max:255',
            'no_rujukan' => 'nullable|max:100',
            'keterangan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;
        $perbelanjaan->update($validated);

        return redirect()->route('perbelanjaan.index')
            ->with('success', 'Perbelanjaan berjaya dikemaskini.');
    }

    public function destroy(Perbelanjaan $perbelanjaan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $perbelanjaan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $perbelanjaan->update(['deleted_by' => $user->id]);
        $perbelanjaan->delete();

        return redirect()->route('perbelanjaan.index')
            ->with('success', 'Perbelanjaan berjaya dipadam.');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $perbelanjaan = Perbelanjaan::findOrFail($id);

        // Check ownership
        if (!$user->hasRole('Super Admin') && $perbelanjaan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Update perbelanjaan status
            $perbelanjaan->update([
                'status_kelulusan' => 'Diluluskan',
                'diluluskan_oleh' => $user->id,
                'tarikh_diluluskan' => now(),
            ]);

            // Create transaksi kewangan
            $transaksi = TransaksiKewangan::create([
                'masjid_id' => $perbelanjaan->masjid_id,
                'no_transaksi' => TransaksiKewangan::generateNoTransaksi($perbelanjaan->masjid_id),
                'tarikh_transaksi' => $perbelanjaan->tarikh_perbelanjaan,
                'jenis_transaksi' => 'Perbelanjaan',
                'kategori_kewangan_id' => $perbelanjaan->kategori_kewangan_id,
                'akaun_bank_id' => $perbelanjaan->akaun_bank_id,
                'jumlah' => $perbelanjaan->jumlah,
                'kaedah_bayaran' => $perbelanjaan->kaedah_bayaran,
                'no_rujukan' => $perbelanjaan->no_rujukan,
                'keterangan' => $perbelanjaan->jenis_perbelanjaan . ' - ' . $perbelanjaan->keterangan,
                'rujukan_id' => $perbelanjaan->id,
                'rujukan_type' => Perbelanjaan::class,
                'status' => 'Selesai',
                'created_by' => $user->id,
            ]);

            // Update perbelanjaan with transaksi reference
            $perbelanjaan->update(['transaksi_kewangan_id' => $transaksi->id]);

            // Update bank balance
            $akaunBank = AkaunBank::find($perbelanjaan->akaun_bank_id);
            $akaunBank->updateBaki($perbelanjaan->jumlah, 'tolak');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Perbelanjaan berjaya diluluskan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal meluluskan perbelanjaan: ' . $e->getMessage()]);
        }
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $perbelanjaan = Perbelanjaan::findOrFail($id);

        // Check ownership
        if (!$user->hasRole('Super Admin') && $perbelanjaan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $perbelanjaan->update([
            'status_kelulusan' => 'Ditolak',
            'catatan' => $request->input('reason', 'Tiada sebab dinyatakan'),
        ]);

        return redirect()->back()
            ->with('success', 'Perbelanjaan telah ditolak.');
    }
}
