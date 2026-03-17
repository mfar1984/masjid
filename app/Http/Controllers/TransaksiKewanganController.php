<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKewangan;
use App\Models\KategoriKewangan;
use App\Models\AkaunBank;
use App\Models\TetapanKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiKewanganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;

        // Get items per page from settings
        $perPage = TetapanKewangan::get('records_per_page', 10, $masjidId);

        $query = TransaksiKewangan::with(['masjid', 'kategoriKewangan', 'akaunBank']);

        // Multi-masjid isolation
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori_kewangan_id')) {
            $query->where('kategori_kewangan_id', $request->kategori_kewangan_id);
        }

        if ($request->filled('tarikh_dari')) {
            $query->whereDate('tarikh_transaksi', '>=', $request->tarikh_dari);
        }

        if ($request->filled('tarikh_hingga')) {
            $query->whereDate('tarikh_transaksi', '<=', $request->tarikh_hingga);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('no_rujukan', 'like', "%{$search}%");
            });
        }

        $transaksi = $query->latest('tarikh_transaksi')->paginate($perPage);

        // Stats
        $baseQuery = TransaksiKewangan::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $stats = [
            ['title' => 'Jumlah Transaksi', 'value' => (clone $baseQuery)->count(), 'icon' => 'receipt_long', 'color' => 'blue'],
            ['title' => 'Pendapatan', 'value' => 'RM ' . number_format((clone $baseQuery)->pendapatan()->selesai()->sum('jumlah'), 2), 'icon' => 'trending_up', 'color' => 'green'],
            ['title' => 'Perbelanjaan', 'value' => 'RM ' . number_format((clone $baseQuery)->perbelanjaan()->selesai()->sum('jumlah'), 2), 'icon' => 'trending_down', 'color' => 'red'],
            ['title' => 'Baki Bersih', 'value' => 'RM ' . number_format((clone $baseQuery)->pendapatan()->selesai()->sum('jumlah') - (clone $baseQuery)->perbelanjaan()->selesai()->sum('jumlah'), 2), 'icon' => 'account_balance_wallet', 'color' => 'purple'],
        ];

        // Get categories for filter
        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->orderBy('nama_kategori')->get();

        return view('transaksi-kewangan.index', compact('transaksi', 'stats', 'kategori', 'isSuperAdmin'));
    }

    public function createPendapatan()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('transaksi-kewangan.create-pendapatan', compact('kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function createPerbelanjaan()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('transaksi-kewangan.create-perbelanjaan', compact('kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'tarikh_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:Pendapatan,Perbelanjaan',
            'kategori_kewangan_id' => 'required|exists:kategori_kewangan,id',
            'akaun_bank_id' => 'required|exists:akaun_bank,id',
            'jumlah' => 'required|numeric|min:0.01',
            'kaedah_bayaran' => 'required|max:255',
            'no_rujukan' => 'nullable|max:100',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|array',
            'dokumen.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate transaction number
            $validated['no_transaksi'] = TransaksiKewangan::generateNoTransaksi($masjidId);
            $validated['masjid_id'] = $masjidId;
            $validated['status'] = 'Selesai';
            $validated['created_by'] = $user->id;

            // Handle file uploads
            if ($request->hasFile('dokumen')) {
                $dokumenPaths = [];
                foreach ($request->file('dokumen') as $file) {
                    $dokumenPaths[] = $file->store('transaksi-kewangan', 'public');
                }
                $validated['dokumen'] = $dokumenPaths;
            }

            $transaksi = TransaksiKewangan::create($validated);

            // Update bank balance
            $akaunBank = AkaunBank::find($validated['akaun_bank_id']);
            if ($validated['jenis_transaksi'] === 'Pendapatan') {
                $akaunBank->updateBaki($validated['jumlah'], 'tambah');
            } else {
                $akaunBank->updateBaki($validated['jumlah'], 'tolak');
            }

            DB::commit();

            return redirect()->route('transaksi-kewangan.index')
                ->with('success', 'Transaksi berjaya ditambah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(TransaksiKewangan $transaksiKewangan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $transaksiKewangan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $transaksiKewangan->load(['masjid', 'kategoriKewangan', 'akaunBank', 'createdBy', 'updatedBy']);

        // Calculate balance at the time of this transaction
        // Get all transactions before or at this transaction date for this bank account
        $pendapatanSebelum = TransaksiKewangan::where('akaun_bank_id', $transaksiKewangan->akaun_bank_id)
            ->where('jenis_transaksi', 'Pendapatan')
            ->where('tarikh_transaksi', '<=', $transaksiKewangan->tarikh_transaksi)
            ->where('id', '<=', $transaksiKewangan->id) // Include transactions up to this one
            ->sum('jumlah');

        $perbelanjaanSebelum = TransaksiKewangan::where('akaun_bank_id', $transaksiKewangan->akaun_bank_id)
            ->where('jenis_transaksi', 'Perbelanjaan')
            ->where('tarikh_transaksi', '<=', $transaksiKewangan->tarikh_transaksi)
            ->where('id', '<=', $transaksiKewangan->id) // Include transactions up to this one
            ->sum('jumlah');

        // Calculate balance at transaction time
        $bakiPadaMasaTransaksi = $transaksiKewangan->akaunBank->baki_awal + $pendapatanSebelum - $perbelanjaanSebelum;

        return view('transaksi-kewangan.show', compact('transaksiKewangan', 'bakiPadaMasaTransaksi'));
    }

    public function edit(TransaksiKewangan $transaksiKewangan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $transaksiKewangan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $masjidId = $transaksiKewangan->masjid_id;
        $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
        $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();

        return view('transaksi-kewangan.edit', compact('transaksiKewangan', 'kategori', 'akaunBank', 'kaedahBayaran'));
    }

    public function update(Request $request, TransaksiKewangan $transaksiKewangan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $transaksiKewangan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'tarikh_transaksi' => 'required|date',
            'kategori_kewangan_id' => 'required|exists:kategori_kewangan,id',
            'akaun_bank_id' => 'required|exists:akaun_bank,id',
            'jumlah' => 'required|numeric|min:0.01',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Online Transfer,Kad Kredit/Debit,Lain-lain',
            'no_rujukan' => 'nullable|max:100',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|array',
            'dokumen.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Revert old bank balance
            $oldAkaunBank = AkaunBank::find($transaksiKewangan->akaun_bank_id);
            if ($transaksiKewangan->jenis_transaksi === 'Pendapatan') {
                $oldAkaunBank->updateBaki($transaksiKewangan->jumlah, 'tolak');
            } else {
                $oldAkaunBank->updateBaki($transaksiKewangan->jumlah, 'tambah');
            }

            // Handle file uploads
            if ($request->hasFile('dokumen')) {
                $dokumenPaths = $transaksiKewangan->dokumen ?? [];
                foreach ($request->file('dokumen') as $file) {
                    $dokumenPaths[] = $file->store('transaksi-kewangan', 'public');
                }
                $validated['dokumen'] = $dokumenPaths;
            }

            $validated['updated_by'] = $user->id;
            $transaksiKewangan->update($validated);

            // Update new bank balance
            $newAkaunBank = AkaunBank::find($validated['akaun_bank_id']);
            if ($transaksiKewangan->jenis_transaksi === 'Pendapatan') {
                $newAkaunBank->updateBaki($validated['jumlah'], 'tambah');
            } else {
                $newAkaunBank->updateBaki($validated['jumlah'], 'tolak');
            }

            DB::commit();

            return redirect()->route('transaksi-kewangan.index')
                ->with('success', 'Transaksi berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengemaskini transaksi: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(TransaksiKewangan $transaksiKewangan)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $transaksiKewangan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Revert bank balance
            $akaunBank = AkaunBank::find($transaksiKewangan->akaun_bank_id);
            if ($transaksiKewangan->jenis_transaksi === 'Pendapatan') {
                $akaunBank->updateBaki($transaksiKewangan->jumlah, 'tolak');
            } else {
                $akaunBank->updateBaki($transaksiKewangan->jumlah, 'tambah');
            }

            $transaksiKewangan->update(['deleted_by' => $user->id]);
            $transaksiKewangan->delete();

            DB::commit();

            return redirect()->route('transaksi-kewangan.index')
                ->with('success', 'Transaksi berjaya dipadam.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memadam transaksi: ' . $e->getMessage()]);
        }
    }
}
