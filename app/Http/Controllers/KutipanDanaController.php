<?php

namespace App\Http\Controllers;

use App\Models\KutipanDana;
use App\Models\KategoriKewangan;
use App\Models\AkaunBank;
use App\Models\TransaksiKewangan;
use App\Models\TetapanKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KutipanDanaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;

        $perPage = TetapanKewangan::get('records_per_page', 10, $masjidId);

        $query = KutipanDana::with(['masjid', 'kategoriKewangan', 'akaunBank']);

        // Multi-masjid isolation
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('jenis_kutipan')) {
            $query->where('jenis_kutipan', $request->jenis_kutipan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_kutipan', 'like', "%{$search}%")
                    ->orWhere('nama_penderma', 'like', "%{$search}%")
                    ->orWhere('nama_pembayar', 'like', "%{$search}%");
            });
        }

        $kutipan = $query->latest('tarikh_kutipan')->paginate($perPage);

        // Stats
        $baseQuery = KutipanDana::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $stats = [
            'kutipan_kariah' => (clone $baseQuery)->kutipanKariah()->sum('jumlah'),
            'derma_sumbangan' => (clone $baseQuery)->dermaSumbangan()->sum('jumlah'),
            'kutipan_zakat' => (clone $baseQuery)->kutipanZakat()->sum('jumlah'),
            'kutipan_lain' => (clone $baseQuery)->kutipanLain()->sum('jumlah'),
        ];

        return view('kutipan-dana.index', compact('kutipan', 'stats', 'isSuperAdmin'));
    }

    public function kutipanKariah()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kariah = \App\Models\Kariah::where('masjid_id', $masjidId)->active()->orderBy('nama')->get();
        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('kutipan-dana.kutipan-kariah', compact('kariah', 'kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function dermaSumbangan()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();
        $jenisDerma = KategoriKewangan::where('masjid_id', $masjidId)->where('jenis_kategori', 'jenis_derma')->where('status', 'Aktif')->orderBy('urutan')->get();

        return view('kutipan-dana.derma-sumbangan', compact('kategori', 'akaunBank', 'kaedahBayaran', 'jenisDerma'));
    }

    public function kutipanZakat()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('kutipan-dana.kutipan-zakat', compact('kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function kutipanLain()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('kutipan-dana.kutipan-lain', compact('kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'tarikh_kutipan' => 'required|date',
            'jenis_kutipan' => 'required|in:Kutipan Kariah,Derma & Sumbangan,Kutipan Zakat,Kutipan Lain-lain',
            'kariah_id' => 'nullable|exists:kariah,id',
            'bulan_kutipan' => 'nullable|string|max:20',
            'nama_penderma' => 'nullable|max:255',
            'no_telefon_penderma' => 'nullable|max:20',
            'alamat_penderma' => 'nullable|string',
            'jenis_derma' => 'nullable|in:Wang Tunai,Barangan,Perkhidmatan,Lain-lain',
            'jenis_zakat' => 'nullable|in:Zakat Fitrah,Zakat Harta,Zakat Perniagaan,Zakat Pendapatan,Lain-lain',
            'nama_pembayar' => 'nullable|max:255',
            'no_kp_pembayar' => 'nullable|max:20',
            'kategori_kewangan_id' => 'required|exists:kategori_kewangan,id',
            'akaun_bank_id' => 'required|exists:akaun_bank,id',
            'jumlah' => 'required|numeric|min:0.01',
            'kaedah_bayaran' => 'required|max:255',
            'no_rujukan' => 'nullable|max:100',
            'no_resit' => 'nullable|max:100',
            'tujuan' => 'nullable|string',
            'dokumen' => 'nullable|array',
            'dokumen.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate kutipan number
            $validated['no_kutipan'] = KutipanDana::generateNoKutipan($masjidId);
            $validated['masjid_id'] = $masjidId;
            $validated['created_by'] = $user->id;

            // Handle file uploads
            if ($request->hasFile('dokumen')) {
                $dokumenPaths = [];
                foreach ($request->file('dokumen') as $file) {
                    $dokumenPaths[] = $file->store('kutipan-dana', 'public');
                }
                $validated['dokumen'] = $dokumenPaths;
            }

            $kutipan = KutipanDana::create($validated);

            // Create transaksi kewangan
            $transaksi = TransaksiKewangan::create([
                'masjid_id' => $masjidId,
                'no_transaksi' => TransaksiKewangan::generateNoTransaksi($masjidId),
                'tarikh_transaksi' => $validated['tarikh_kutipan'],
                'jenis_transaksi' => 'Pendapatan',
                'kategori_kewangan_id' => $validated['kategori_kewangan_id'],
                'akaun_bank_id' => $validated['akaun_bank_id'],
                'jumlah' => $validated['jumlah'],
                'kaedah_bayaran' => $validated['kaedah_bayaran'],
                'no_rujukan' => $validated['no_rujukan'] ?? null,
                'keterangan' => $validated['jenis_kutipan'] . ' - ' . ($validated['nama_penderma'] ?? $validated['nama_pembayar'] ?? 'Tidak dinyatakan'),
                'rujukan_id' => $kutipan->id,
                'rujukan_type' => KutipanDana::class,
                'status' => 'Selesai',
                'created_by' => $user->id,
            ]);

            // Update kutipan with transaksi reference
            $kutipan->update(['transaksi_kewangan_id' => $transaksi->id]);

            // Update bank balance
            $akaunBank = AkaunBank::find($validated['akaun_bank_id']);
            $akaunBank->updateBaki($validated['jumlah'], 'tambah');

            DB::commit();

            return redirect()->route('kutipan-dana.index')
                ->with('success', 'Kutipan dana berjaya ditambah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan kutipan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(KutipanDana $kutipanDana)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kutipanDana->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $kutipanDana->load(['masjid', 'kategoriKewangan', 'akaunBank', 'transaksiKewangan', 'createdBy', 'updatedBy']);

        return view('kutipan-dana.show', compact('kutipanDana'));
    }

    public function edit(KutipanDana $kutipanDana)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kutipanDana->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $masjidId = $kutipanDana->masjid_id;
        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->pendapatan()->aktif()->orderBy('nama_kategori')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();

        return view('kutipan-dana.edit', compact('kutipanDana', 'kategori', 'akaunBank'));
    }

    public function update(Request $request, KutipanDana $kutipanDana)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kutipanDana->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'tarikh_kutipan' => 'required|date',
            'nama_penderma' => 'nullable|max:255',
            'no_telefon_penderma' => 'nullable|max:20',
            'alamat_penderma' => 'nullable|string',
            'nama_pembayar' => 'nullable|max:255',
            'no_kp_pembayar' => 'nullable|max:20',
            'kategori_kewangan_id' => 'required|exists:kategori_kewangan,id',
            'akaun_bank_id' => 'required|exists:akaun_bank,id',
            'jumlah' => 'required|numeric|min:0.01',
            'kaedah_bayaran' => 'required|max:255',
            'no_rujukan' => 'nullable|max:100',
            'tujuan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;
        $kutipanDana->update($validated);

        return redirect()->route('kutipan-dana.index')
            ->with('success', 'Kutipan dana berjaya dikemaskini.');
    }

    public function destroy(KutipanDana $kutipanDana)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kutipanDana->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $kutipanDana->update(['deleted_by' => $user->id]);
        $kutipanDana->delete();

        return redirect()->route('kutipan-dana.index')
            ->with('success', 'Kutipan dana berjaya dipadam.');
    }
}
