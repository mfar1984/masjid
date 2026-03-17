<?php

namespace App\Http\Controllers;

use App\Models\TetapanAsnaf;
use App\Models\KategoriAsnaf;
use App\Models\Masjid;
use Illuminate\Http\Request;

class TetapanAsnafController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        // Check if user has permission to access any TAB under Tetapan Asnaf
        if (!$isSuperAdmin) {
            $hasPermission = $user->hasPermission('tetapan_asnaf_had_kifayah', 'read') ||
                           $user->hasPermission('tetapan_asnaf_had_kifayah', 'update') ||
                           $user->hasPermission('tetapan_asnaf_had_bantuan', 'read') ||
                           $user->hasPermission('tetapan_asnaf_had_bantuan', 'update') ||
                           $user->hasPermission('tetapan_asnaf_workflow', 'read') ||
                           $user->hasPermission('tetapan_asnaf_workflow', 'update') ||
                           $user->hasPermission('tetapan_asnaf_permohonan', 'read') ||
                           $user->hasPermission('tetapan_asnaf_permohonan', 'update') ||
                           $user->hasPermission('tetapan_asnaf_kategori', 'read') ||
                           $user->hasPermission('tetapan_asnaf_kategori', 'update') ||
                           $user->hasPermission('tetapan_asnaf_payment', 'read') ||
                           $user->hasPermission('tetapan_asnaf_payment', 'update') ||
                           $user->hasPermission('tetapan_asnaf_display', 'read') ||
                           $user->hasPermission('tetapan_asnaf_display', 'update');
            
            if (!$hasPermission) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
            }
        }
        
        $masjidId = $user->masjid_id;

        // Get all settings by category (returns Collection, not array)
        $hadKifayah = TetapanAsnaf::getByCategory('had_kifayah', $masjidId)->toArray();
        $hadBantuan = TetapanAsnaf::getByCategory('had_bantuan', $masjidId)->toArray();
        $workflow = TetapanAsnaf::getByCategory('workflow', $masjidId)->toArray();
        $permohonan = TetapanAsnaf::getByCategory('permohonan', $masjidId)->toArray();
        $kategoriAsnaf = TetapanAsnaf::getByCategory('kategori_asnaf', $masjidId)->toArray();
        $paymentGateway = TetapanAsnaf::getByCategory('payment_gateway', $masjidId)->toArray();
        $displaySettings = TetapanAsnaf::getByCategory('display_settings', $masjidId)->toArray();

        // Get kategori data
        $bangsa = KategoriAsnaf::where('masjid_id', $masjidId)->bangsa()->aktif()->orderBy('urutan')->get();
        $agama = KategoriAsnaf::where('masjid_id', $masjidId)->agama()->aktif()->orderBy('urutan')->get();
        $statusPerkahwinan = KategoriAsnaf::where('masjid_id', $masjidId)->statusPerkahwinan()->aktif()->orderBy('urutan')->get();
        $negeri = KategoriAsnaf::where('masjid_id', $masjidId)->negeri()->aktif()->orderBy('urutan')->get();
        $kategoriAsnafList = KategoriAsnaf::where('masjid_id', $masjidId)->kategoriAsnaf()->aktif()->orderBy('urutan')->get();
        $statusPekerjaan = KategoriAsnaf::where('masjid_id', $masjidId)->statusPekerjaan()->aktif()->orderBy('urutan')->get();
        $statusKesihatan = KategoriAsnaf::where('masjid_id', $masjidId)->statusKesihatan()->aktif()->orderBy('urutan')->get();
        $kewarganegaraan = KategoriAsnaf::where('masjid_id', $masjidId)->kewarganegaraan()->aktif()->orderBy('urutan')->get();

        // Get masjid list for Super Admin
        $masjids = $user->isSuperAdmin() ? Masjid::where('status', 'active')->get() : collect();

        // Check tab permissions
        $tabPermissions = [
            'had_kifayah' => $user->hasPermission('tetapan_asnaf_had_kifayah', 'read') || $user->hasPermission('tetapan_asnaf_had_kifayah', 'update'),
            'had_bantuan' => $user->hasPermission('tetapan_asnaf_had_bantuan', 'read') || $user->hasPermission('tetapan_asnaf_had_bantuan', 'update'),
            'workflow' => $user->hasPermission('tetapan_asnaf_workflow', 'read') || $user->hasPermission('tetapan_asnaf_workflow', 'update'),
            'permohonan' => $user->hasPermission('tetapan_asnaf_permohonan', 'read') || $user->hasPermission('tetapan_asnaf_permohonan', 'update'),
            'kategori' => $user->hasPermission('tetapan_asnaf_kategori', 'read') || $user->hasPermission('tetapan_asnaf_kategori', 'update'),
            'payment' => $user->hasPermission('tetapan_asnaf_payment', 'read') || $user->hasPermission('tetapan_asnaf_payment', 'update'),
            'display' => $user->hasPermission('tetapan_asnaf_display', 'read') || $user->hasPermission('tetapan_asnaf_display', 'update'),
        ];

        return view('tetapan-asnaf.index', compact(
            'hadKifayah',
            'hadBantuan',
            'workflow',
            'permohonan',
            'kategoriAsnaf',
            'paymentGateway',
            'displaySettings',
            'bangsa',
            'agama',
            'statusPerkahwinan',
            'negeri',
            'kategoriAsnafList',
            'statusPekerjaan',
            'statusKesihatan',
            'kewarganegaraan',
            'masjids',
            'tabPermissions'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $masjidId = $user->isSuperAdmin() && $request->masjid_id 
            ? $request->masjid_id 
            : $user->masjid_id;

        $category = $request->input('category');

        // Validate based on category
        $validated = $this->validateByCategory($request, $category);

        // Save settings
        foreach ($validated as $key => $value) {
            if ($key === 'category' || $key === 'masjid_id') continue;

            // Determine type
            $type = $this->determineType($key, $value);

            TetapanAsnaf::set($key, $value, $masjidId, $type, $category);
        }

        return redirect()->back()->with('success', 'Tetapan berjaya dikemaskini.');
    }

    private function validateByCategory(Request $request, $category)
    {
        switch ($category) {
            case 'had_kifayah':
                return $request->validate([
                    'had_kifayah_individu' => 'required|numeric|min:0',
                    'had_kifayah_pasangan' => 'required|numeric|min:0',
                    'had_kifayah_anak' => 'required|numeric|min:0',
                    'had_kifayah_tanggungan' => 'required|numeric|min:0',
                    'had_kifayah_max_anak' => 'required|integer|min:0',
                    'had_kifayah_max_tanggungan' => 'required|integer|min:0',
                    'had_kifayah_auto_calculate' => 'required|boolean',
                ]);

            case 'had_bantuan':
                return $request->validate([
                    'fakir_percentage' => 'required|numeric|min:0|max:100',
                    'miskin_percentage' => 'required|numeric|min:0|max:100',
                    'amil_percentage' => 'required|numeric|min:0|max:100',
                    'muallaf_percentage' => 'required|numeric|min:0|max:100',
                    'riqab_percentage' => 'required|numeric|min:0|max:100',
                    'gharimin_percentage' => 'required|numeric|min:0|max:100',
                    'fisabilillah_percentage' => 'required|numeric|min:0|max:100',
                    'ibnu_sabil_percentage' => 'required|numeric|min:0|max:100',
                ]);

            case 'workflow':
                return $request->validate([
                    'require_mesyuarat_approval' => 'required|boolean',
                    'require_mesyuarat_attachment' => 'required|boolean',
                    'auto_approve_enabled' => 'required|boolean',
                    'auto_approve_amount' => 'required|numeric|min:0',
                    'notification_enabled' => 'required|boolean',
                    'notification_methods' => 'nullable|array',
                ]);

            case 'permohonan':
                return $request->validate([
                    'max_permohonan_per_year' => 'required|integer|min:0',
                    'allow_adhoc_agihan' => 'required|boolean',
                    'require_supporting_docs' => 'required|boolean',
                    'min_days_between_applications' => 'required|integer|min:0',
                    'allowed_file_types' => 'nullable|array',
                    'max_file_size_mb' => 'required|numeric|min:1',
                    'admin_only_create' => 'required|boolean',
                ]);

            case 'kategori_asnaf':
                return $request->validate([
                    'enable_fakir' => 'required|boolean',
                    'enable_miskin' => 'required|boolean',
                    'enable_amil' => 'required|boolean',
                    'enable_muallaf' => 'required|boolean',
                    'enable_riqab' => 'required|boolean',
                    'enable_gharimin' => 'required|boolean',
                    'enable_fisabilillah' => 'required|boolean',
                    'enable_ibnu_sabil' => 'required|boolean',
                ]);

            case 'payment_gateway':
                return $request->validate([
                    'chipasia_enabled' => 'required|boolean',
                    'chipasia_brand_id' => 'nullable|string',
                    'chipasia_api_key' => 'nullable|string',
                    'bank_name' => 'nullable|string|max:255',
                    'bank_account_number' => 'nullable|string|max:50',
                    'bank_account_name' => 'nullable|string|max:255',
                ]);

            case 'display_settings':
                return $request->validate([
                    'show_asnaf_on_website' => 'required|boolean',
                    'show_donation_form' => 'required|boolean',
                    'show_zakat_calculator' => 'required|boolean',
                    'records_per_page' => 'required|integer|min:5|max:100',
                    'date_format' => 'required|string',
                ]);

            default:
                return [];
        }
    }

    private function determineType($key, $value)
    {
        // Encrypted fields
        if (in_array($key, ['chipasia_brand_id', 'chipasia_api_key'])) {
            return 'encrypted';
        }

        // JSON array fields
        if (in_array($key, ['notification_methods', 'allowed_file_types'])) {
            return 'json';
        }

        // Boolean fields
        if (is_bool($value) || in_array($value, ['0', '1', 0, 1, true, false]) || 
            strpos($key, 'enable_') === 0 || strpos($key, 'require_') === 0 || 
            strpos($key, 'allow_') === 0 || strpos($key, 'show_') === 0 ||
            in_array($key, ['chipasia_enabled', 'notification_enabled', 'auto_approve_enabled', 'admin_only_create'])) {
            return 'boolean';
        }

        // Number fields
        if (is_numeric($value) || strpos($key, 'percentage') !== false || 
            strpos($key, 'amount') !== false || strpos($key, 'max_') === 0 || 
            strpos($key, 'min_') === 0 || in_array($key, ['records_per_page'])) {
            return 'number';
        }

        return 'string';
    }

    // Kategori CRUD Methods
    public function kategoriStore(Request $request)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        $validated = $request->validate([
            'jenis_kategori' => 'required|in:bangsa,agama,status_perkahwinan,negeri,kategori_asnaf,status_pekerjaan,status_kesihatan,kewarganegaraan',
            'nama_kategori' => 'required|string|max:255',
            'kod_kategori' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['masjid_id'] = $masjidId;
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        KategoriAsnaf::create($validated);

        return redirect()->back()->with('success', 'Kategori berjaya ditambah.');
    }

    public function kategoriUpdate(Request $request, $id)
    {
        $user = auth()->user();
        $kategori = KategoriAsnaf::findOrFail($id);

        // Check masjid ownership
        if ($kategori->masjid_id !== $user->masjid_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'jenis_kategori' => 'required|in:bangsa,agama,status_perkahwinan,negeri,kategori_asnaf,status_pekerjaan,status_kesihatan,kewarganegaraan',
            'nama_kategori' => 'required|string|max:255',
            'kod_kategori' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['updated_by'] = $user->id;

        $kategori->update($validated);

        return redirect()->back()->with('success', 'Kategori berjaya dikemaskini.');
    }

    public function kategoriDestroy($id)
    {
        $user = auth()->user();
        $kategori = KategoriAsnaf::findOrFail($id);

        // Check masjid ownership
        if ($kategori->masjid_id !== $user->masjid_id && !$user->isSuperAdmin()) {
            abort(403);
        }

        $kategori->deleted_by = $user->id;
        $kategori->save();
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berjaya dipadam.');
    }
}
