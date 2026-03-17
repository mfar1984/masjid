<?php

namespace App\Http\Controllers;

use App\Models\TetapanKebajikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TetapanKebajikanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        // Check if user has permission to access any TAB under Tetapan Kebajikan
        if (!$isSuperAdmin) {
            $hasPermission = $user->hasPermission('tetapan_kebajikan_had_bantuan', 'read') ||
                           $user->hasPermission('tetapan_kebajikan_had_bantuan', 'update') ||
                           $user->hasPermission('tetapan_kebajikan_workflow', 'read') ||
                           $user->hasPermission('tetapan_kebajikan_workflow', 'update') ||
                           $user->hasPermission('tetapan_kebajikan_permohonan', 'read') ||
                           $user->hasPermission('tetapan_kebajikan_permohonan', 'update') ||
                           $user->hasPermission('tetapan_kebajikan_kategori_penerima', 'read') ||
                           $user->hasPermission('tetapan_kebajikan_kategori_penerima', 'update') ||
                           $user->hasPermission('tetapan_kebajikan_pembayaran', 'read') ||
                           $user->hasPermission('tetapan_kebajikan_pembayaran', 'update') ||
                           $user->hasPermission('tetapan_kebajikan_paparan', 'read') ||
                           $user->hasPermission('tetapan_kebajikan_paparan', 'update') ||
                           $user->hasPermission('tetapan_kebajikan_kategori', 'read') ||
                           $user->hasPermission('tetapan_kebajikan_kategori', 'update');
            
            if (!$hasPermission) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
            }
        }
        
        $masjidId = $isSuperAdmin ? null : $user->masjid_id;

        // Get all settings for the masjid
        $settings = $this->getAllSettings($masjidId);

        // Get kategori data - use first masjid if Super Admin
        $kategoriMasjidId = $masjidId ?? $user->masjid_id ?? 1;
        
        $jenisBantuan = \App\Models\KategoriKebajikan::where('masjid_id', $kategoriMasjidId)
            ->jenisBantuan()
            ->orderBy('urutan')
            ->get();

        $keutamaan = \App\Models\KategoriKebajikan::where('masjid_id', $kategoriMasjidId)
            ->keutamaan()
            ->orderBy('urutan')
            ->get();

        $jenisProgram = \App\Models\KategoriKebajikan::where('masjid_id', $kategoriMasjidId)
            ->jenisProgram()
            ->orderBy('urutan')
            ->get();

        $tempohBantuan = \App\Models\KategoriKebajikan::where('masjid_id', $kategoriMasjidId)
            ->tempohBantuan()
            ->orderBy('urutan')
            ->get();

        $bangsa = \App\Models\KategoriKebajikan::where('masjid_id', $kategoriMasjidId)
            ->bangsa()
            ->orderBy('urutan')
            ->get();

        $agama = \App\Models\KategoriKebajikan::where('masjid_id', $kategoriMasjidId)
            ->agama()
            ->orderBy('urutan')
            ->get();

        $jenisKediaman = \App\Models\KategoriKebajikan::where('masjid_id', $kategoriMasjidId)
            ->jenisKediaman()
            ->orderBy('urutan')
            ->get();

        // Check tab permissions
        $tabPermissions = [
            'had_bantuan' => $user->hasPermission('tetapan_kebajikan_had_bantuan', 'read') || $user->hasPermission('tetapan_kebajikan_had_bantuan', 'update'),
            'workflow' => $user->hasPermission('tetapan_kebajikan_workflow', 'read') || $user->hasPermission('tetapan_kebajikan_workflow', 'update'),
            'permohonan' => $user->hasPermission('tetapan_kebajikan_permohonan', 'read') || $user->hasPermission('tetapan_kebajikan_permohonan', 'update'),
            'kategori_penerima' => $user->hasPermission('tetapan_kebajikan_kategori_penerima', 'read') || $user->hasPermission('tetapan_kebajikan_kategori_penerima', 'update'),
            'pembayaran' => $user->hasPermission('tetapan_kebajikan_pembayaran', 'read') || $user->hasPermission('tetapan_kebajikan_pembayaran', 'update'),
            'display' => $user->hasPermission('tetapan_kebajikan_paparan', 'read') || $user->hasPermission('tetapan_kebajikan_paparan', 'update'),
            'kategori' => $user->hasPermission('tetapan_kebajikan_kategori', 'read') || $user->hasPermission('tetapan_kebajikan_kategori', 'update'),
        ];

        return view('tetapan-kebajikan.index', compact('settings', 'jenisBantuan', 'keutamaan', 'jenisProgram', 'tempohBantuan', 'bangsa', 'agama', 'jenisKediaman', 'tabPermissions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        // Validate based on tab
        $validated = $request->validate([
            // Had Bantuan
            'had_minimum_pendidikan' => 'nullable|numeric|min:0',
            'had_maksimum_pendidikan' => 'nullable|numeric|min:0',
            'had_minimum_kesihatan' => 'nullable|numeric|min:0',
            'had_maksimum_kesihatan' => 'nullable|numeric|min:0',
            'had_minimum_kecemasan' => 'nullable|numeric|min:0',
            'had_maksimum_kecemasan' => 'nullable|numeric|min:0',
            'had_minimum_kebajikan_am' => 'nullable|numeric|min:0',
            'had_maksimum_kebajikan_am' => 'nullable|numeric|min:0',

            // Workflow
            'auto_approve_below_amount' => 'nullable|numeric|min:0',
            'require_home_visit' => 'nullable|in:Ya,Tidak',
            'home_visit_mandatory_above' => 'nullable|numeric|min:0',
            'approval_levels' => 'nullable|integer|min:1|max:3',
            'notification_email' => 'nullable|email',
            'notification_sms' => 'nullable|in:Ya,Tidak',

            // Permohonan
            'allow_multiple_applications' => 'nullable|in:Ya,Tidak',
            'application_cooldown_days' => 'nullable|integer|min:0',
            'max_applications_per_year' => 'nullable|integer|min:0',
            'require_documents' => 'nullable|in:Ya,Tidak',

            // Kategori Penerima
            'enable_oku' => 'nullable|in:Ya,Tidak',
            'enable_yatim' => 'nullable|in:Ya,Tidak',
            'enable_ibu_tunggal' => 'nullable|in:Ya,Tidak',
            'enable_warga_emas' => 'nullable|in:Ya,Tidak',

            // Pembayaran
            'default_payment_method' => 'nullable|in:Tunai,Cek,Bank Transfer,Barangan,Baucar',
            'enable_digital_signature' => 'nullable|in:Ya,Tidak',
            'require_acknowledgment_letter' => 'nullable|in:Ya,Tidak',
            'payment_approval_required' => 'nullable|in:Ya,Tidak',

            // Display Settings
            'show_penerima_photo' => 'nullable|in:Ya,Tidak',
            'show_financial_details' => 'nullable|in:Ya,Tidak',
            'items_per_page' => 'nullable|integer|in:10,25,50,100',
            'default_sort_order' => 'nullable|in:Terbaru,Terlama,Nama A-Z,Nama Z-A',
        ]);

        // Save all settings
        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $type = is_numeric($value) ? 'number' : 'text';
                TetapanKebajikan::setSetting($masjidId, $key, $value, $type);
            }
        }

        return redirect()->route('tetapan-kebajikan.index')
            ->with('success', 'Tetapan kebajikan berjaya dikemaskini.');
    }

    private function getAllSettings($masjidId)
    {
        $keys = [
            // Had Bantuan
            'had_minimum_pendidikan',
            'had_maksimum_pendidikan',
            'had_minimum_kesihatan',
            'had_maksimum_kesihatan',
            'had_minimum_kecemasan',
            'had_maksimum_kecemasan',
            'had_minimum_kebajikan_am',
            'had_maksimum_kebajikan_am',

            // Workflow
            'auto_approve_below_amount',
            'require_home_visit',
            'home_visit_mandatory_above',
            'approval_levels',
            'notification_email',
            'notification_sms',

            // Permohonan
            'allow_multiple_applications',
            'application_cooldown_days',
            'max_applications_per_year',
            'require_documents',

            // Kategori Penerima
            'enable_oku',
            'enable_yatim',
            'enable_ibu_tunggal',
            'enable_warga_emas',

            // Pembayaran
            'default_payment_method',
            'enable_digital_signature',
            'require_acknowledgment_letter',
            'payment_approval_required',

            // Display Settings
            'show_penerima_photo',
            'show_financial_details',
            'items_per_page',
            'default_sort_order',
        ];

        return TetapanKebajikan::getSettings($masjidId, $keys);
    }

    // Kategori Management
    public function kategoriStore(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'jenis_kategori' => 'required|in:jenis_bantuan,keutamaan,jenis_program,tempoh_bantuan,bangsa,agama,jenis_kediaman',
            'nama_kategori' => 'required|max:100',
            'kod_kategori' => 'nullable|max:50',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['masjid_id'] = $masjidId;
        $validated['created_by'] = $user->id;

        \App\Models\KategoriKebajikan::create($validated);

        return redirect()->route('tetapan-kebajikan.index', ['tab' => 'kategori-data'])
            ->with('success', 'Kategori berjaya ditambah.');
    }

    public function kategoriUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $kategori = \App\Models\KategoriKebajikan::findOrFail($id);

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kategori->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'jenis_kategori' => 'required|in:jenis_bantuan,keutamaan,jenis_program,tempoh_bantuan,bangsa,agama,jenis_kediaman',
            'nama_kategori' => 'required|max:100',
            'kod_kategori' => 'nullable|max:50',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['updated_by'] = $user->id;

        $kategori->update($validated);

        return redirect()->route('tetapan-kebajikan.index', ['tab' => 'kategori-data'])
            ->with('success', 'Kategori berjaya dikemaskini.');
    }

    public function kategoriDestroy($id)
    {
        $user = Auth::user();
        $kategori = \App\Models\KategoriKebajikan::findOrFail($id);

        // Check ownership
        if (!$user->hasRole('Super Admin') && $kategori->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $kategori->update(['deleted_by' => $user->id]);
        $kategori->delete();

        return redirect()->route('tetapan-kebajikan.index', ['tab' => 'kategori-data'])
            ->with('success', 'Kategori berjaya dipadam.');
    }
}
