<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSewa;
use App\Models\TempahanFasiliti;
use App\Models\KutipanDana;
use App\Models\TransaksiKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranSewaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PembayaranSewa::with(['tempahanFasiliti', 'senariFasiliti']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id ?? 0);
        }

        // Filters
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('kaedah_bayaran')) {
            $query->where('kaedah_bayaran', $request->kaedah_bayaran);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pembayaran', 'like', "%{$search}%")
                    ->orWhereHas('tempahanFasiliti', function($q2) use ($search) {
                        $q2->where('nama_penyewa', 'like', "%{$search}%");
                    });
            });
        }

        $pembayaranSewa = $query->latest()->paginate(25);

        // Stats
        $statsQuery = PembayaranSewa::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'sudah_bayar' => (clone $statsQuery)->sudahBayar()->count(),
            'belum_bayar' => (clone $statsQuery)->belumBayar()->count(),
            'jumlah_terkumpul' => (clone $statsQuery)->sudahBayar()->sum('jumlah_bayaran') ?? 0,
        ];

        // Get fasiliti list for filter dropdown
        $fasilitiList = \App\Models\SenariFasiliti::where('masjid_id', $user->masjid_id)->get();

        // Alias for view compatibility
        $pembayaranList = $pembayaranSewa;

        return view('pembayaran-sewa.index', compact('pembayaranSewa', 'pembayaranList', 'stats', 'fasilitiList'));
    }

    public function create()
    {
        $user = Auth::user();
        $tempahanList = TempahanFasiliti::where('masjid_id', $user->masjid_id)
            ->where('status_tempahan', 'Lulus')
            ->whereDoesntHave('pembayaranSewa')
            ->get();
        return view('pembayaran-sewa.create', compact('tempahanList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'tempahan_fasiliti_id' => 'required|exists:tempahan_fasiliti,id',
            'tarikh_pembayaran' => 'required|date',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Bank Transfer,Online Banking,E-Wallet',
            'nama_bank' => 'nullable|max:255',
            'no_rujukan' => 'nullable|max:100',
            'no_cek' => 'nullable|max:50',
            'tarikh_cek' => 'nullable|date',
            'status_pembayaran' => 'required|in:Belum Bayar,Sudah Bayar',
            'catatan' => 'nullable|string',
        ]);

        $tempahan = TempahanFasiliti::findOrFail($validated['tempahan_fasiliti_id']);
        
        $validated['masjid_id'] = $tempahan->masjid_id;
        $validated['no_pembayaran'] = PembayaranSewa::generateNoPembayaran($tempahan->masjid_id);
        $validated['senarai_fasiliti_id'] = $tempahan->senarai_fasiliti_id;
        $validated['jumlah_sewa'] = $tempahan->harga_sewa;
        $validated['jumlah_deposit'] = $tempahan->deposit;
        $validated['jumlah_bayaran'] = $tempahan->jumlah_bayaran;
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        DB::beginTransaction();
        try {
            $pembayaran = PembayaranSewa::create($validated);

            // If status = Sudah Bayar, auto-create Kutipan Dana
            if ($validated['status_pembayaran'] === 'Sudah Bayar') {
                $this->createKutipanDana($pembayaran, $user);
            }

            DB::commit();
            return redirect()->route('pembayaran-sewa.index')
                ->with('success', 'Pembayaran berjaya direkodkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal merekod pembayaran: ' . $e->getMessage());
        }
    }

    public function show(PembayaranSewa $pembayaranSewa)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pembayaranSewa->masjid_id !== $user->masjid_id) {
            abort(403);
        }
        $pembayaranSewa->load(['tempahanFasiliti', 'senariFasiliti']);
        return view('pembayaran-sewa.show', compact('pembayaranSewa'));
    }

    public function edit(PembayaranSewa $pembayaranSewa)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pembayaranSewa->masjid_id !== $user->masjid_id) {
            abort(403);
        }
        return view('pembayaran-sewa.edit', compact('pembayaranSewa'));
    }

    public function update(Request $request, PembayaranSewa $pembayaranSewa)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pembayaranSewa->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'tarikh_pembayaran' => 'required|date',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Bank Transfer,Online Banking,E-Wallet',
            'nama_bank' => 'nullable|max:255',
            'no_rujukan' => 'nullable|max:100',
            'status_pembayaran' => 'required|in:Belum Bayar,Sudah Bayar,Deposit Dikembalikan,Dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        DB::beginTransaction();
        try {
            $oldStatus = $pembayaranSewa->status_pembayaran;
            $pembayaranSewa->update($validated);

            // If status changed to Sudah Bayar, auto-create Kutipan Dana
            if ($oldStatus !== 'Sudah Bayar' && $validated['status_pembayaran'] === 'Sudah Bayar') {
                $this->createKutipanDana($pembayaranSewa, $user);
            }

            DB::commit();
            return redirect()->route('pembayaran-sewa.index')
                ->with('success', 'Pembayaran berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal kemaskini pembayaran: ' . $e->getMessage());
        }
    }

    public function destroy(PembayaranSewa $pembayaranSewa)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $pembayaranSewa->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        if ($pembayaranSewa->status_pembayaran === 'Sudah Bayar') {
            return back()->with('error', 'Pembayaran yang sudah bayar tidak boleh dipadam.');
        }

        $pembayaranSewa->update(['deleted_by' => $user->id]);
        $pembayaranSewa->delete();

        return redirect()->route('pembayaran-sewa.index')
            ->with('success', 'Pembayaran berjaya dipadam.');
    }

    private function createKutipanDana($pembayaran, $user)
    {
        // Get kategori_kewangan_id for "Sewa Fasiliti & Aset"
        $kategori = \App\Models\KategoriKewangan::where('masjid_id', $pembayaran->masjid_id)
            ->where('nama_kategori', 'Sewa Fasiliti & Aset')
            ->first();

        if (!$kategori) {
            throw new \Exception('Kategori Kewangan "Sewa Fasiliti & Aset" tidak dijumpai.');
        }

        // Create Kutipan Dana
        KutipanDana::create([
            'masjid_id' => $pembayaran->masjid_id,
            'jenis_kutipan' => 'Kutipan Lain-lain',
            'kategori_kewangan_id' => $kategori->id,
            'tarikh_kutipan' => $pembayaran->tarikh_pembayaran,
            'jumlah' => $pembayaran->jumlah_bayaran,
            'kaedah_bayaran' => $pembayaran->kaedah_bayaran,
            'nama_bank' => $pembayaran->nama_bank,
            'no_rujukan' => $pembayaran->no_rujukan,
            'no_cek' => $pembayaran->no_cek,
            'tarikh_cek' => $pembayaran->tarikh_cek,
            'penerima' => 'Sewa Fasiliti: ' . $pembayaran->senariFasiliti->nama_fasiliti,
            'tujuan' => 'Sewa fasiliti untuk ' . $pembayaran->tempahanFasiliti->tujuan_tempahan,
            'rujukan_id' => $pembayaran->id,
            'rujukan_type' => 'PembayaranSewa',
            'status_kutipan' => 'Selesai',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // Create Transaksi Kewangan
        TransaksiKewangan::create([
            'masjid_id' => $pembayaran->masjid_id,
            'jenis_transaksi' => 'Pendapatan',
            'kategori_kewangan_id' => $kategori->id,
            'tarikh_transaksi' => $pembayaran->tarikh_pembayaran,
            'jumlah' => $pembayaran->jumlah_bayaran,
            'kaedah_bayaran' => $pembayaran->kaedah_bayaran,
            'penerima_pembayar' => $pembayaran->tempahanFasiliti->nama_penyewa,
            'keterangan' => 'Sewa ' . $pembayaran->senariFasiliti->nama_fasiliti,
            'rujukan_id' => $pembayaran->id,
            'rujukan_type' => 'PembayaranSewa',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    }
}
