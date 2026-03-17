<?php

namespace App\Http\Controllers;

use App\Models\TetapanKewangan;
use App\Models\KategoriKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TetapanKewanganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        // Check if user has permission to access any TAB under Tetapan Kewangan
        if (!$isSuperAdmin) {
            $hasPermission = $user->hasPermission('tetapan_kewangan_umum', 'read') ||
                           $user->hasPermission('tetapan_kewangan_umum', 'update') ||
                           $user->hasPermission('tetapan_kewangan_kategori', 'read') ||
                           $user->hasPermission('tetapan_kewangan_kategori', 'update');
            
            if (!$hasPermission) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
            }
        }
        
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;

        // Get all settings for this masjid
        $settings = [
            'records_per_page' => TetapanKewangan::get('records_per_page', 10, $masjidId),
            'auto_generate_receipt' => TetapanKewangan::get('auto_generate_receipt', true, $masjidId),
            'receipt_prefix' => TetapanKewangan::get('receipt_prefix', 'TXN', $masjidId),
            'enable_approval_workflow' => TetapanKewangan::get('enable_approval_workflow', false, $masjidId),
            'approval_threshold' => TetapanKewangan::get('approval_threshold', 1000, $masjidId),
            'fiscal_year_start' => TetapanKewangan::get('fiscal_year_start', 1, $masjidId),
            'default_currency' => TetapanKewangan::get('default_currency', 'RM', $masjidId),
            'enable_notifications' => TetapanKewangan::get('enable_notifications', true, $masjidId),
            // Kaedah Bayaran
            'enable_tunai' => TetapanKewangan::get('enable_tunai', 'Ya', $masjidId),
            'enable_online_transfer' => TetapanKewangan::get('enable_online_transfer', 'Ya', $masjidId),
            'enable_cek' => TetapanKewangan::get('enable_cek', 'Ya', $masjidId),
            'enable_kad' => TetapanKewangan::get('enable_kad', 'Tidak', $masjidId),
            'enable_lain_lain' => TetapanKewangan::get('enable_lain_lain', 'Ya', $masjidId),
        ];

        // Get kategori kewangan for old tabs
        $kategoriPerbelanjaan = KategoriKewangan::where('masjid_id', $masjidId)
            ->perbelanjaan()
            ->orderBy('urutan')
            ->get();

        // Get kategori data for new Kategori tab
        $kategoriPendapatan = KategoriKewangan::where('masjid_id', $masjidId)
            ->kategoriPendapatan()
            ->orderBy('urutan')
            ->get();

        $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)
            ->kaedahBayaran()
            ->orderBy('urutan')
            ->get();

        $jenisAkaun = KategoriKewangan::where('masjid_id', $masjidId)
            ->jenisAkaun()
            ->orderBy('urutan')
            ->get();

        $namaBank = KategoriKewangan::where('masjid_id', $masjidId)
            ->namaBank()
            ->orderBy('urutan')
            ->get();

        $jenisDerma = KategoriKewangan::where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'jenis_derma')
            ->orderBy('urutan')
            ->get();

        $jenisBil = KategoriKewangan::where('masjid_id', $masjidId)
            ->where('jenis_kategori', 'jenis_bil')
            ->orderBy('urutan')
            ->get();

        // Check tab permissions
        $tabPermissions = [
            'kategori' => $user->hasPermission('tetapan_kewangan_kategori', 'read') || $user->hasPermission('tetapan_kewangan_kategori', 'update'),
            'display' => $user->hasPermission('tetapan_kewangan_paparan', 'read') || $user->hasPermission('tetapan_kewangan_paparan', 'update'),
        ];

        return view('tetapan-kewangan.index', compact(
            'settings',
            'kategoriPendapatan',
            'kaedahBayaran',
            'jenisAkaun',
            'namaBank',
            'jenisDerma',
            'jenisBil',
            'kategoriPerbelanjaan',
            'isSuperAdmin',
            'tabPermissions'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'records_per_page' => 'nullable|integer|min:5|max:100',
            'auto_generate_receipt' => 'nullable|boolean',
            'receipt_prefix' => 'nullable|string|max:10',
            'enable_approval_workflow' => 'nullable|boolean',
            'approval_threshold' => 'nullable|numeric|min:0',
            'fiscal_year_start' => 'nullable|integer|min:1|max:12',
            'default_currency' => 'nullable|string|max:10',
            'enable_notifications' => 'nullable|boolean',
        ]);

        // Save each setting
        foreach ($validated as $key => $value) {
            TetapanKewangan::set($key, $value, $masjidId);
        }

        return redirect()->route('tetapan-kewangan.index')
            ->with('success', 'Tetapan kewangan berjaya dikemaskini.');
    }

    public function kategoriStore(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'jenis_kategori' => 'required|in:kategori_pendapatan,kaedah_bayaran,jenis_akaun,nama_bank,jenis_derma,jenis_bil',
            'nama_kategori' => 'required|string|max:255',
            'kod_kategori' => 'nullable|string|max:20',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['masjid_id'] = $masjidId;
        $validated['created_by'] = $user->id;

        KategoriKewangan::create($validated);

        return redirect()->route('tetapan-kewangan.index', ['tab' => 'kategori'])
            ->with('success', 'Kategori berjaya ditambah.');
    }

    public function kategoriUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $kategori = KategoriKewangan::findOrFail($id);

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kategori->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'jenis_kategori' => 'required|in:kategori_pendapatan,kaedah_bayaran,jenis_akaun,nama_bank,jenis_derma,jenis_bil',
            'nama_kategori' => 'required|string|max:255',
            'kod_kategori' => 'nullable|string|max:20',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['updated_by'] = $user->id;
        $kategori->update($validated);

        return redirect()->route('tetapan-kewangan.index', ['tab' => 'kategori'])
            ->with('success', 'Kategori berjaya dikemaskini.');
    }

    public function kategoriDestroy($id)
    {
        $user = Auth::user();
        $kategori = KategoriKewangan::findOrFail($id);

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kategori->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $kategori->update(['deleted_by' => $user->id]);
        $kategori->delete();

        return redirect()->route('tetapan-kewangan.index', ['tab' => 'kategori'])
            ->with('success', 'Kategori berjaya dipadam.');
    }
}
