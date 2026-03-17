<?php

namespace App\Http\Controllers;

use App\Models\SenariAset;
use App\Models\KategoriAset;
use App\Models\PergerakanAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanAsetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        // Check permission
        if (!$isSuperAdmin) {
            $hasPermission = $user->hasPermission('laporan_aset_inventori', 'read') ||
                           $user->hasPermission('laporan_aset_lokasi', 'read') ||
                           $user->hasPermission('laporan_aset_penyelenggaraan', 'read') ||
                           $user->hasPermission('laporan_aset_dashboard', 'read') ||
                           $user->hasPermission('laporan_aset_pergerakan', 'read') ||
                           $user->hasPermission('laporan_aset_pemindahan', 'read');
            
            if (!$hasPermission) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
            }
        }
        
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;
        $masjids = $isSuperAdmin ? \App\Models\Masjid::orderBy('nama')->get() : collect();

        // TAB Permissions
        $tabPermissions = [
            'dashboard' => $user->hasPermission('laporan_aset_dashboard', 'read'),
            'inventori' => $user->hasPermission('laporan_aset_inventori', 'read'),
            'lokasi' => $user->hasPermission('laporan_aset_lokasi', 'read'),
            'penyelenggaraan' => $user->hasPermission('laporan_aset_penyelenggaraan', 'read'),
            'pergerakan' => $user->hasPermission('laporan_aset_pergerakan', 'read'),
            'pemindahan' => $user->hasPermission('laporan_aset_pemindahan', 'read'),
        ];

        // ========== DASHBOARD DATA ==========
        // Use withoutGlobalScopes() to avoid duplicate masjid_id conditions from HasMasjidScope trait
        $totalAset = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)->count();
        $asetAktif = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)->where('status_aset', 'Aktif')->count();
        $asetDisewa = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)->where('status_aset', 'Disewa')->count();
        $asetDipinjam = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)->where('status_aset', 'Dipinjam')->count();
        $asetRosak = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)->where('status_aset', 'Rosak')->count();
        $asetHilang = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)->where('status_aset', 'Hilang')->count();
        
        $totalNilaiAset = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)
            ->whereIn('status_aset', ['Aktif', 'Disewa', 'Dipinjam'])
            ->sum('harga_perolehan');

        // Pergerakan stats
        $totalPergerakan = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)->count();
        $pergerakanBelumPulang = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)->where('status_pulangan', 'Belum Pulang')->count();
        $pergerakanLewat = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)->where('status_pulangan', 'Lewat')->count();
        $pergerakanHilang = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)->where('status_pulangan', 'Hilang')->count();

        // Aset by kategori for chart
        $asetByKategori = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)
            ->join('kategori_aset', 'senarai_aset.kategori_aset_id', '=', 'kategori_aset.id')
            ->select('kategori_aset.nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori_aset.nama_kategori')
            ->get();

        // ========== INVENTORI DATA ==========
        $inventoriQuery = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)
            ->with('kategoriAset');

        if ($request->filled('kategori_id')) {
            $inventoriQuery->where('senarai_aset.kategori_aset_id', $request->kategori_id);
        }
        if ($request->filled('status_aset')) {
            $inventoriQuery->where('senarai_aset.status_aset', $request->status_aset);
        }
        if ($request->filled('kondisi_aset')) {
            $inventoriQuery->where('senarai_aset.kondisi_aset', $request->kondisi_aset);
        }

        $inventoriAset = $inventoriQuery->orderBy('senarai_aset.nama_aset')->get();

        // Summary by status
        $inventoriSummary = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)
            ->select('senarai_aset.status_aset', DB::raw('count(*) as total'), DB::raw('sum(senarai_aset.harga_perolehan) as nilai'))
            ->groupBy('senarai_aset.status_aset')
            ->get();

        // ========== LOKASI DATA ==========
        $lokasiAset = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)
            ->select('senarai_aset.lokasi_semasa', DB::raw('count(*) as total'))
            ->groupBy('senarai_aset.lokasi_semasa')
            ->orderBy('total', 'desc')
            ->get();

        $asetByLokasi = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)
            ->with('kategoriAset')
            ->orderBy('senarai_aset.lokasi_semasa')
            ->get()
            ->groupBy('lokasi_semasa');

        // ========== PENYELENGGARAAN DATA ==========
        $pergerakanPenyelenggaraan = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->where('jenis_pergerakan', 'Penyelenggaraan')
            ->with('senariAset')
            ->orderBy('tarikh_pergerakan', 'desc')
            ->get();

        // Aset yang perlu penyelenggaraan (kondisi Sederhana atau Teruk)
        $asetPerluPenyelenggaraan = SenariAset::withoutGlobalScopes()->where('senarai_aset.masjid_id', $masjidId)
            ->whereIn('senarai_aset.kondisi_aset', ['Sederhana', 'Teruk'])
            ->with('kategoriAset')
            ->orderBy('senarai_aset.kondisi_aset')
            ->get();

        // ========== PERGERAKAN DATA (Pinjaman/Sewa) ==========
        $pergerakanPinjaman = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->whereIn('jenis_pergerakan', ['Pinjaman', 'Sewa'])
            ->with('senariAset')
            ->orderBy('tarikh_pergerakan', 'desc')
            ->limit(50)
            ->get();

        // Pergerakan by jenis
        $pergerakanByJenis = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->select('pergerakan_aset.jenis_pergerakan', DB::raw('count(*) as total'))
            ->groupBy('pergerakan_aset.jenis_pergerakan')
            ->get();

        // Pergerakan by status pulangan
        $pergerakanByStatus = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->select('pergerakan_aset.status_pulangan', DB::raw('count(*) as total'))
            ->groupBy('pergerakan_aset.status_pulangan')
            ->get();

        // ========== PEMINDAHAN DATA ==========
        $pemindahanAset = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->whereIn('jenis_pergerakan', ['Pemindahan Dalaman', 'Pemindahan Luaran'])
            ->with('senariAset')
            ->orderBy('tarikh_pergerakan', 'desc')
            ->limit(50)
            ->get();

        // Pemindahan stats
        $totalPemindahan = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->whereIn('jenis_pergerakan', ['Pemindahan Dalaman', 'Pemindahan Luaran'])->count();
        $pemindahanDalaman = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->where('jenis_pergerakan', 'Pemindahan Dalaman')->count();
        $pemindahanLuaran = PergerakanAset::withoutGlobalScopes()->where('pergerakan_aset.masjid_id', $masjidId)
            ->where('jenis_pergerakan', 'Pemindahan Luaran')->count();

        // Get kategori list for filter
        $kategoriList = KategoriAset::withoutGlobalScopes()->where('kategori_aset.masjid_id', $masjidId)
            ->where('status', 'Aktif')
            ->orderBy('nama_kategori')
            ->get();

        return view('laporan-aset.index', compact(
            'masjids',
            'masjidId',
            'tabPermissions',
            // Dashboard
            'totalAset',
            'asetAktif',
            'asetDisewa',
            'asetDipinjam',
            'asetRosak',
            'asetHilang',
            'totalNilaiAset',
            'totalPergerakan',
            'pergerakanBelumPulang',
            'pergerakanLewat',
            'pergerakanHilang',
            'asetByKategori',
            // Inventori
            'inventoriAset',
            'inventoriSummary',
            'kategoriList',
            // Lokasi
            'lokasiAset',
            'asetByLokasi',
            // Penyelenggaraan
            'pergerakanPenyelenggaraan',
            'asetPerluPenyelenggaraan',
            // Pergerakan (Pinjaman/Sewa)
            'pergerakanPinjaman',
            'pergerakanByJenis',
            'pergerakanByStatus',
            // Pemindahan
            'pemindahanAset',
            'totalPemindahan',
            'pemindahanDalaman',
            'pemindahanLuaran'
        ));
    }
}
