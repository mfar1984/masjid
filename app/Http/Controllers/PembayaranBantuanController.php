<?php

namespace App\Http\Controllers;

use App\Models\PembayaranBantuan;
use App\Models\PermohonanBantuan;
use App\Models\TetapanKebajikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranBantuanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $query = PembayaranBantuan::with(['masjid', 'penerimaBantuan', 'programKebajikan', 'permohonanBantuan']);

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

        if ($request->filled('kaedah_bayaran')) {
            $query->where('kaedah_bayaran', $request->kaedah_bayaran);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('tarikh_dari')) {
            $query->whereDate('tarikh_pembayaran', '>=', $request->tarikh_dari);
        }

        if ($request->filled('tarikh_hingga')) {
            $query->whereDate('tarikh_pembayaran', '<=', $request->tarikh_hingga);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pembayaran', 'like', "%{$search}%")
                    ->orWhereHas('penerimaBantuan', function ($q) use ($search) {
                        $q->where('nama_penuh', 'like', "%{$search}%");
                    });
            });
        }

        // Get items per page from settings
        $settings = TetapanKebajikan::getSettings($isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id, ['items_per_page']);
        $perPage = $settings['items_per_page'] ?? 10;

        $pembayaran = $query->latest()->paginate($perPage);

        // Stats
        $baseQuery = PembayaranBantuan::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $jumlahDibayar = (clone $baseQuery)->where('status_pembayaran', 'Sudah Bayar')->sum('jumlah_bayaran');

        $stats = [
            ['title' => 'Total Pembayaran', 'value' => (clone $baseQuery)->count(), 'icon' => 'payments', 'color' => 'blue'],
            ['title' => 'Sudah Bayar', 'value' => (clone $baseQuery)->where('status_pembayaran', 'Sudah Bayar')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Belum Bayar', 'value' => (clone $baseQuery)->where('status_pembayaran', 'Belum Bayar')->count(), 'icon' => 'pending', 'color' => 'orange'],
            ['title' => 'Jumlah Dibayar', 'value' => 'RM ' . number_format($jumlahDibayar, 2), 'icon' => 'account_balance_wallet', 'color' => 'teal'],
        ];

        return view('pembayaran-bantuan.index', compact('pembayaran', 'stats', 'isSuperAdmin'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get default payment method from settings
        $settings = TetapanKebajikan::getSettings($masjidId, ['default_payment_method']);
        $defaultPaymentMethod = $settings['default_payment_method'] ?? 'Tunai';

        // Get approved permohonan without pembayaran
        $permohonan = PermohonanBantuan::where('masjid_id', $masjidId)
            ->where('status_permohonan', 'Lulus')
            ->whereDoesntHave('pembayaranBantuan')
            ->with(['penerimaBantuan', 'programKebajikan'])
            ->orderBy('tarikh_permohonan', 'desc')
            ->get();

        return view('pembayaran-bantuan.create', compact('permohonan', 'defaultPaymentMethod'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'permohonan_bantuan_id' => 'required|exists:permohonan_bantuan,id',
            'tarikh_pembayaran' => 'required|date',
            'jumlah_bayaran' => 'required|numeric|min:0',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Bank Transfer,Barangan,Baucar',
            'nama_bank' => 'required_if:kaedah_bayaran,Bank Transfer,Cek',
            'no_akaun' => 'required_if:kaedah_bayaran,Bank Transfer',
            'no_rujukan' => 'required_if:kaedah_bayaran,Bank Transfer',
            'no_cek' => 'required_if:kaedah_bayaran,Cek',
            'tarikh_cek' => 'required_if:kaedah_bayaran,Cek|nullable|date',
            'senarai_barangan' => 'required_if:kaedah_bayaran,Barangan',
            'nilai_barangan' => 'required_if:kaedah_bayaran,Barangan|nullable|numeric|min:0',
        ]);

        // Get permohonan details
        $permohonan = PermohonanBantuan::findOrFail($request->permohonan_bantuan_id);

        $validated['masjid_id'] = $masjidId;
        $validated['no_pembayaran'] = PembayaranBantuan::generateNoPembayaran($masjidId);
        $validated['penerima_bantuan_id'] = $permohonan->penerima_bantuan_id;
        $validated['program_kebajikan_id'] = $permohonan->program_kebajikan_id;
        $validated['status_pembayaran'] = 'Belum Bayar';
        $validated['created_by'] = $user->id;

        $pembayaran = PembayaranBantuan::create($validated);

        return redirect()->route('pembayaran-bantuan.index')
            ->with('success', 'Pembayaran bantuan berjaya dicipta.');
    }

    public function show(PembayaranBantuan $pembayaranBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $pembayaranBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $pembayaranBantuan->load([
            'masjid',
            'permohonanBantuan',
            'penerimaBantuan',
            'programKebajikan',
            'pembayar',
            'pengesah',
            'creator',
            'updater'
        ]);

        return view('pembayaran-bantuan.show', compact('pembayaranBantuan'));
    }

    public function edit(PembayaranBantuan $pembayaranBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $pembayaranBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pembayaran-bantuan.edit', compact('pembayaranBantuan'));
    }

    public function update(Request $request, PembayaranBantuan $pembayaranBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $pembayaranBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'tarikh_pembayaran' => 'required|date',
            'jumlah_bayaran' => 'required|numeric|min:0',
            'kaedah_bayaran' => 'required|in:Tunai,Cek,Bank Transfer,Barangan,Baucar',
            'status_pembayaran' => 'required|in:Belum Bayar,Sudah Bayar,Dibatalkan',
        ]);

        $validated['updated_by'] = $user->id;

        // Update status timestamps
        if ($request->status_pembayaran === 'Sudah Bayar' && $pembayaranBantuan->status_pembayaran !== 'Sudah Bayar') {
            $validated['dibayar_oleh'] = $user->id;
            $validated['tarikh_dibayar'] = now();
        }

        $pembayaranBantuan->update($validated);

        return redirect()->route('pembayaran-bantuan.index')
            ->with('success', 'Pembayaran bantuan berjaya dikemaskini.');
    }

    public function destroy(PembayaranBantuan $pembayaranBantuan)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $pembayaranBantuan->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $pembayaranBantuan->update(['deleted_by' => $user->id]);
        $pembayaranBantuan->delete();

        return redirect()->route('pembayaran-bantuan.index')
            ->with('success', 'Pembayaran bantuan berjaya dipadam.');
    }

    // Workflow Methods
    public function sahkan(Request $request, $id)
    {
        $pembayaran = PembayaranBantuan::findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $pembayaran->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($pembayaran->status_pembayaran !== 'Belum Bayar') {
            return back()->with('error', 'Pembayaran ini tidak boleh disahkan.');
        }

        $pembayaran->update([
            'status_pembayaran' => 'Sudah Bayar',
            'dibayar_oleh' => $user->id,
            'tarikh_dibayar' => now(),
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Pembayaran berjaya disahkan.');
    }

    public function batal(Request $request, $id)
    {
        $pembayaran = PembayaranBantuan::findOrFail($id);
        $user = Auth::user();

        if (!$user->hasRole('Super Admin') && $pembayaran->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($pembayaran->status_pembayaran === 'Sudah Bayar') {
            return back()->with('error', 'Pembayaran yang sudah dibayar tidak boleh dibatalkan.');
        }

        $validated = $request->validate([
            'sebab_batal' => 'required|string',
        ]);

        $pembayaran->update([
            'status_pembayaran' => 'Dibatalkan',
            'catatan' => $validated['sebab_batal'],
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Pembayaran berjaya dibatalkan.');
    }
}
