<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Role::query();

        // Multi-Masjid Data Isolation using Custom Role System
        if ($user->isSuperAdmin()) {
            // Super Admin can see all roles (global + all masjid roles)
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see roles from their own masjid
            // NO global/system roles visible to maintain strict isolation
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $query->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no roles
                $query->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            if ($request->type === 'system') {
                $query->systemRoles();
            } elseif ($request->type === 'custom') {
                $query->customRoles();
            } elseif ($request->type === 'global') {
                $query->globalRoles();
            } elseif ($request->type === 'masjid') {
                $query->masjidRoles();
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $roles = $query->with('masjid')
                      ->orderBy('is_system_role', 'desc')
                      ->orderBy('masjid_id', 'asc')
                      ->orderBy('name')
                      ->paginate(10)
                      ->withQueryString();

        // Statistics with STRICT masjid isolation
        $baseQuery = Role::query();
        if (!$user->isSuperAdmin()) {
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                $baseQuery->whereRaw('1 = 0'); // No data if no masjid_id
            }
        }

        // Build stats array dynamically based on actual data
        $totalRoles = $baseQuery->count();
        $activeRoles = (clone $baseQuery)->where('is_active', true)->count();
        $inactiveRoles = (clone $baseQuery)->where('is_active', false)->count();
        $customRoles = (clone $baseQuery)->where('is_system_role', false)->count();

        $stats = [];

        // Always show total
        $stats[] = [
            'title' => 'Jumlah Kumpulan',
            'value' => $totalRoles,
            'icon' => 'groups',
            'color' => 'blue'
        ];

        // Show active if there are any
        if ($activeRoles > 0) {
            $stats[] = [
                'title' => 'Aktif',
                'value' => $activeRoles,
                'icon' => 'check_circle',
                'color' => 'green'
            ];
        }

        // Show inactive if there are any
        if ($inactiveRoles > 0) {
            $stats[] = [
                'title' => 'Tidak Aktif',
                'value' => $inactiveRoles,
                'icon' => 'pause_circle',
                'color' => 'gray'
            ];
        }

        // Show custom roles if there are any
        if ($customRoles > 0) {
            $stats[] = [
                'title' => 'Kumpulan Tersuai',
                'value' => $customRoles,
                'icon' => 'tune',
                'color' => 'orange'
            ];
        }

        // Filter options
        $typeList = [
            'system' => 'Sistem',
            'custom' => 'Tersuai'
        ];

        $statusList = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif'
        ];

        return view('pentadbiran.senarai-kumpulan', compact(
            'roles',
            'typeList',
            'statusList',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = $this->getAvailableModules();
        $actions = $this->getAvailableActions();
        $readOnlyModules = $this->getReadOnlyModules();
        $headerModules = $this->getHeaderModules();
        $settingsOnlyModules = $this->getSettingsOnlyModules();
        $workflowModules = $this->getWorkflowModules();
        $partialWorkflowModules = $this->getPartialWorkflowModules();

        // Get masjids for Super Admin to choose from
        $masjids = collect();
        if (auth()->user()->isSuperAdmin()) {
            $masjids = \App\Models\Masjid::where('status', 'active')
                                        ->orderBy('nama')
                                        ->get(['id', 'nama']);
        }

        return view('pentadbiran.kumpulan.create', compact('modules', 'actions', 'readOnlyModules', 'headerModules', 'settingsOnlyModules', 'workflowModules', 'partialWorkflowModules', 'masjids'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
            'scope_type' => 'required|in:global,masjid',
            'masjid_id' => 'nullable|exists:masjids,id',
        ]);

        $validated['is_system_role'] = false;
        $validated['is_active'] = $request->has('is_active');

        // Multi-Masjid Data Isolation using Custom Role System
        if ($user->isSuperAdmin()) {
            // Super Admin can choose scope
            if ($request->scope_type === 'global') {
                $validated['masjid_id'] = null;
                // Validate unique name globally
                $request->validate([
                    'name' => 'unique:roles,name,NULL,id,masjid_id,NULL'
                ]);
            } else {
                // Creating for specific masjid
                $validated['masjid_id'] = $request->masjid_id;
                // Validate unique name within masjid scope
                $request->validate([
                    'masjid_id' => 'required|exists:masjids,id',
                    'name' => 'unique:roles,name,NULL,id,masjid_id,' . $request->masjid_id
                ]);
            }
        } else {
            // Admin Masjid can only create roles for their masjid
            $userMasjidId = $user->masjid_id ?? 1;
            $validated['masjid_id'] = $userMasjidId;

            // Validate unique name within masjid scope
            $request->validate([
                'name' => "unique:roles,name,NULL,id,masjid_id,{$userMasjidId}"
            ]);
        }

        Role::create($validated);

        return redirect()->route('senarai-kumpulan.index')
            ->with('success', 'Kumpulan akses berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $currentUser = auth()->user();

        // Multi-Masjid Data Isolation - STRICT MODE
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only view roles from their own masjid or global roles
            if ($role->masjid_id !== null && $role->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk melihat kumpulan ini.');
            }
        }

        $modules = $this->getAvailableModules();
        $actions = $this->getAvailableActions();
        $readOnlyModules = $this->getReadOnlyModules();
        $headerModules = $this->getHeaderModules();
        $settingsOnlyModules = $this->getSettingsOnlyModules();
        $workflowModules = $this->getWorkflowModules();
        $partialWorkflowModules = $this->getPartialWorkflowModules();

        return view('pentadbiran.kumpulan.show', compact('role', 'modules', 'actions', 'readOnlyModules', 'headerModules', 'settingsOnlyModules', 'workflowModules', 'partialWorkflowModules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $currentUser = auth()->user();

        // Check if current user can edit this role
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only edit roles from their own masjid
            if ($role->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengedit kumpulan ini.');
            }
        }

        $modules = $this->getAvailableModules();
        $actions = $this->getAvailableActions();
        $readOnlyModules = $this->getReadOnlyModules();
        $headerModules = $this->getHeaderModules();
        $settingsOnlyModules = $this->getSettingsOnlyModules();
        $workflowModules = $this->getWorkflowModules();
        $partialWorkflowModules = $this->getPartialWorkflowModules();

        // Get masjids for Super Admin to choose from
        $masjids = collect();
        if ($currentUser->isSuperAdmin()) {
            $masjids = \App\Models\Masjid::where('status', 'active')
                                        ->orderBy('nama')
                                        ->get(['id', 'nama']);
        }

        return view('pentadbiran.kumpulan.edit', compact('role', 'modules', 'actions', 'readOnlyModules', 'headerModules', 'settingsOnlyModules', 'workflowModules', 'partialWorkflowModules', 'masjids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $user = auth()->user();

        // System roles cannot be modified
        if ($role->is_system_role) {
            return redirect()->route('senarai-kumpulan.index')
                ->with('error', 'Role sistem tidak boleh diubah.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
            'scope_type' => 'required|in:global,masjid',
            'masjid_id' => 'nullable|exists:masjids,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle scope changes for Super Admin
        if ($user->isSuperAdmin()) {
            if ($request->scope_type === 'global') {
                $validated['masjid_id'] = null;
                // Validate unique name globally
                $request->validate([
                    'name' => 'unique:roles,name,' . $role->id . ',id,masjid_id,NULL'
                ]);
            } else {
                // Moving to specific masjid
                $validated['masjid_id'] = $request->masjid_id;
                // Validate unique name within masjid scope
                $request->validate([
                    'masjid_id' => 'required|exists:masjids,id',
                    'name' => 'unique:roles,name,' . $role->id . ',id,masjid_id,' . $request->masjid_id
                ]);
            }
        } else {
            // Admin Masjid cannot change scope
            $validated['masjid_id'] = $role->masjid_id;
            // Validate unique name within current scope
            if ($role->masjid_id) {
                $request->validate([
                    'name' => 'unique:roles,name,' . $role->id . ',id,masjid_id,' . $role->masjid_id
                ]);
            } else {
                $request->validate([
                    'name' => 'unique:roles,name,' . $role->id . ',id,masjid_id,NULL'
                ]);
            }
        }

        $role->update($validated);

        return redirect()->route('senarai-kumpulan.index')
            ->with('success', 'Kumpulan akses berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->is_system_role) {
            return redirect()->route('senarai-kumpulan.index')
                ->with('error', 'Kumpulan sistem tidak boleh dipadam.');
        }

        $role->delete();

        return redirect()->route('senarai-kumpulan.index')
            ->with('success', 'Kumpulan akses berjaya dipadam.');
    }

    /**
     * Get available modules for permission matrix
     */
    private function getAvailableModules()
    {
        return [
            // ═══════════════════════════════════════════════════════════════
            // 📊 PAPARAN PEMUKA
            // ═══════════════════════════════════════════════════════════════
            'dashboard' => 'Papan Pemuka',
            
            // ═══════════════════════════════════════════════════════════════
            // 👥 PENGURUSAN
            // ═══════════════════════════════════════════════════════════════
            'kariah' => 'Ahli Kariah',
            
            // Ahli Jawatankuasa Masjid (Header with submenus)
            'ajk_header' => 'Ahli Jawatankuasa Masjid',
            'ajk' => '├─ Senarai AJK',
            'ajk_arkib' => '├─ Arkib AJK',
            'ajk_laporan' => '└─ Laporan AJK',
            
            // Asnaf (Header with submenus)
            'asnaf_header' => 'Asnaf',
            'asnaf' => '├─ Senarai Asnaf',
            'permohonan_zakat' => '├─ Permohonan Zakat',
            'agihan_zakat' => '├─ Agihan Zakat',
            'laporan_zakat' => '├─ Laporan Zakat',
            'tetapan_asnaf' => '├─ Tetapan Asnaf',
            'tetapan_asnaf_had_kifayah' => '│  ├─ Had Kifayah',
            'tetapan_asnaf_had_bantuan' => '│  ├─ Had Bantuan',
            'tetapan_asnaf_workflow' => '│  ├─ Workflow',
            'tetapan_asnaf_permohonan' => '│  ├─ Permohonan',
            'tetapan_asnaf_kategori' => '│  ├─ Kategori',
            'tetapan_asnaf_payment' => '│  ├─ Payment Gateway',
            'tetapan_asnaf_display' => '│  └─ Display',
            
            // Kebajikan (Header with submenus)
            'kebajikan_header' => 'Kebajikan',
            'program_kebajikan' => '├─ Program Kebajikan',
            'penerima_bantuan' => '├─ Penerima Bantuan',
            'permohonan_bantuan' => '├─ Permohonan Bantuan',
            'pembayaran_bantuan' => '├─ Pembayaran Bantuan',
            'laporan_kebajikan' => '├─ Laporan Kebajikan',
            'tetapan_kebajikan' => '└─ Tetapan Kebajikan',
            'tetapan_kebajikan_had_bantuan' => '   ├─ Had Bantuan',
            'tetapan_kebajikan_workflow' => '   ├─ Workflow',
            'tetapan_kebajikan_permohonan' => '   ├─ Permohonan',
            'tetapan_kebajikan_kategori_penerima' => '   ├─ Kategori Penerima',
            'tetapan_kebajikan_pembayaran' => '   ├─ Pembayaran',
            'tetapan_kebajikan_paparan' => '   ├─ Paparan',
            'tetapan_kebajikan_kategori' => '   └─ Kategori',
            
            // ═══════════════════════════════════════════════════════════════
            // 💰 KEWANGAN
            // ═══════════════════════════════════════════════════════════════
            // Kewangan (Header with submenus)
            'kewangan_header' => 'Kewangan',
            'akaun_bank' => '├─ Akaun Bank',
            'transaksi_kewangan' => '├─ Transaksi Kewangan',
            'laporan_kewangan' => '├─ Laporan Kewangan',
            'laporan_kewangan_penyata' => '│  ├─ Penyata Kewangan',
            'laporan_kewangan_pendapatan' => '│  ├─ Laporan Pendapatan',
            'laporan_kewangan_perbelanjaan' => '│  ├─ Laporan Perbelanjaan',
            'laporan_kewangan_aliran_tunai' => '│  ├─ Aliran Tunai',
            'laporan_kewangan_imbangan_duga' => '│  ├─ Imbangan Duga',
            'laporan_kewangan_perbandingan' => '│  ├─ Perbandingan Bulanan',
            'laporan_kewangan_kategori' => '│  ├─ Laporan Mengikut Kategori',
            'laporan_kewangan_baki_bank' => '│  └─ Baki Bank',
            'tetapan_kewangan' => '└─ Tetapan Kewangan',
            'tetapan_kewangan_umum' => '   ├─ Tetapan Umum',
            'tetapan_kewangan_kategori' => '   └─ Kategori',
            
            // ═══════════════════════════════════════════════════════════════
            // 📅 OPERASI
            // ═══════════════════════════════════════════════════════════════
            'operasi_header' => 'Operasi',
            
            // Program & Pendidikan (Submenu)
            'program_pendidikan_header' => '├─ Program & Pendidikan',
            'senarai_program' => '│  ├─ Senarai Program',
            'jadual_program' => '│  ├─ Jadual Program',
            'pendaftaran_peserta' => '│  ├─ Pendaftaran Peserta',
            'laporan_program' => '│  └─ Laporan Program',
            
            // Fasiliti & Tempahan (Submenu - existing)
            'fasiliti_tempahan' => '├─ Fasiliti & Tempahan',
            
            // Jadual Tugas (Submenu)
            'jadual_tugas_header' => '├─ Jadual Tugas',
            'senarai_penceramah' => '│  ├─ Senarai Penceramah',
            'jadual_ceramah' => '│  ├─ Jadual Ceramah',
            'jadual_imam_bilal' => '│  ├─ Jadual Imam & Bilal',
            'laporan_tugas' => '│  └─ Laporan Tugas',
            
            // Khidmat Komuniti (Submenu)
            'khidmat_komuniti_header' => '└─ Khidmat Komuniti',
            'urusan_jenazah' => '   ├─ Urusan Jenazah',
            'laporan_khidmat' => '   └─ Laporan Khidmat',
            
            // ═══════════════════════════════════════════════════════════════
            // 📦 ASET
            // ═══════════════════════════════════════════════════════════════
            'aset_header' => 'Aset',
            'pengurusan_aset' => '├─ Pengurusan Aset',
            'senarai_aset' => '│  ├─ Senarai Aset',
            'kategori_aset' => '│  ├─ Kategori Aset',
            'pemindahan_aset' => '│  ├─ Pemindahan Aset',
            'pergerakan_aset' => '│  └─ Pergerakan Aset',
            'penyelenggaraan_aset' => '├─ Penyelenggaraan',
            'jadual_penyelenggaraan' => '│  ├─ Jadual Penyelenggaraan',
            'kerja_penyelenggaraan' => '│  ├─ Kerja Penyelenggaraan',
            'laporan_penyelenggaraan' => '│  └─ Laporan Penyelenggaraan',
            'penyusutan_nilai' => '├─ Penyusutan & Nilai',
            'jadual_penyusutan' => '│  ├─ Jadual Penyusutan',
            'nilai_semasa' => '│  ├─ Nilai Semasa',
            'trend_penyusutan' => '│  └─ Trend Penyusutan',
            'pelupusan_aset' => '├─ Pelupusan Aset',
            'permohonan_pelupusan' => '│  ├─ Permohonan Pelupusan',
            'kelulusan_pelupusan' => '│  ├─ Kelulusan Pelupusan',
            'rekod_pelupusan' => '│  └─ Rekod Pelupusan',
            'laporan_aset' => '└─ Laporan Aset',
            'laporan_aset_dashboard' => '   ├─ Dashboard Aset',
            'laporan_aset_inventori' => '   ├─ Laporan Inventori',
            'laporan_aset_lokasi' => '   ├─ Laporan Lokasi',
            'laporan_aset_penyelenggaraan' => '   ├─ Laporan Penyelenggaraan',
            'laporan_aset_pergerakan' => '   ├─ Laporan Pergerakan',
            'laporan_aset_pemindahan' => '   └─ Laporan Pemindahan',
            
            // ═══════════════════════════════════════════════════════════════
            // 🔧 OPERASI > FASILITI & TEMPAHAN
            // ═══════════════════════════════════════════════════════════════
            'fasiliti_header' => 'Fasiliti & Tempahan',
            'senarai_fasiliti' => '├─ Senarai Fasiliti',
            'tempahan_fasiliti' => '├─ Tempahan Fasiliti',
            'pembayaran_sewa' => '├─ Pembayaran Sewa',
            'laporan_tempahan' => '└─ Laporan Tempahan',
            
            // ═══════════════════════════════════════════════════════════════
            // 📢 KOMUNIKASI
            // ═══════════════════════════════════════════════════════════════
            'komunikasi_header' => 'Komunikasi',
            'siaran_mesej' => '├─ Siaran Mesej',
            'kandungan_website' => '├─ Kandungan Website',
            'pengumuman_berita' => '└─ Pengumuman & Berita',
            
            // ═══════════════════════════════════════════════════════════════
            // 📁 FAIL
            // ═══════════════════════════════════════════════════════════════
            'fail_header' => 'Fail',
            'documents' => '├─ Pengurusan Dokumen',
            'perpustakaan_digital' => '├─ Perpustakaan Digital',
            'arkib_rekod' => '└─ Arkib & Rekod',
            
            // ═══════════════════════════════════════════════════════════════
            // ⚙️ PENTADBIRAN SISTEM
            // ═══════════════════════════════════════════════════════════════
            'settings' => 'Tetapan Umum',
            'masjids' => 'Senarai Masjid',
            'users' => 'Senarai Pengguna',
            'roles' => 'Senarai Kumpulan',
            
            // Integrasi (Header with submenus)
            'integrations_header' => 'Integrasi',
            'integrations_email' => '├─ Email (SMTP)',
            'integrations_weather' => '├─ Cuaca',
            'integrations_api' => '└─ API',
        ];
    }

    /**
     * Get available actions for permission matrix
     */
    private function getAvailableActions()
    {
        return [
            'basic' => [
                'create' => 'Tambah',
                'read' => 'Lihat',
                'update' => 'Kemaskini',
                'delete' => 'Padam'
            ],
            'workflow' => [
                'approve' => 'Terima',
                'reject' => 'Tolak',
                'suspend' => 'Gantung',
                'reactivate' => 'Aktifkan'
            ]
        ];
    }

    /**
     * Get modules that are read-only (view only)
     */
    private function getReadOnlyModules()
    {
        return [
            'dashboard', // Papan Pemuka - view only
            'ajk_arkib', // Arkib AJK - view only
            'ajk_laporan', // Laporan AJK - view only
            'laporan_zakat', // Laporan Zakat - view only
            'laporan_kebajikan', // Laporan Kebajikan - view only
            
            // Laporan Kewangan TABs - all read only
            'laporan_kewangan_penyata',
            'laporan_kewangan_pendapatan',
            'laporan_kewangan_perbelanjaan',
            'laporan_kewangan_aliran_tunai',
            'laporan_kewangan_imbangan_duga',
            'laporan_kewangan_perbandingan',
            'laporan_kewangan_kategori',
            'laporan_kewangan_baki_bank',
            
            // Laporan Aset TABs - all read only
            'laporan_aset_dashboard',
            'laporan_aset_inventori',
            'laporan_aset_lokasi',
            'laporan_aset_penyelenggaraan',
            'laporan_aset_pergerakan',
            'laporan_aset_pemindahan',
            
            // Laporan Penyelenggaraan - read only
            'laporan_penyelenggaraan',
            
            // Penyusutan & Nilai - read only pages
            'nilai_semasa', // Nilai Semasa - auto-calculated, read only
            'trend_penyusutan', // Trend Penyusutan - report, read only
            
            // Pelupusan Aset - read only pages
            'kelulusan_pelupusan', // Kelulusan Pelupusan - workflow page
            'rekod_pelupusan', // Rekod Pelupusan - history, read only
            
            // Laporan Tempahan - read only
            'laporan_tempahan',
            
            // Laporan Operasi - read only
            'laporan_program', // Laporan Program - read only
            'laporan_tugas', // Laporan Tugas - read only
            'laporan_khidmat', // Laporan Khidmat - read only
        ];
    }
    
    /**
     * Get modules that are headers only (no checkboxes at all)
     */
    private function getHeaderModules()
    {
        return [
            'ajk_header', // Ahli Jawatankuasa Masjid - header only
            'asnaf_header', // Asnaf - header only
            'kebajikan_header', // Kebajikan - header only
            'kewangan_header', // Kewangan - header only
            'operasi_header', // Operasi - header only
            'aset_header', // Aset - header only
            'pengurusan_aset', // Pengurusan Aset - submenu header
            'penyelenggaraan_aset', // Penyelenggaraan - submenu header
            'penyusutan_nilai', // Penyusutan & Nilai - submenu header
            'pelupusan_aset', // Pelupusan Aset - submenu header
            'laporan_aset', // Laporan Aset - submenu header
            'komunikasi_header', // Komunikasi - header only
            'fail_header', // Fail - header only
            'integrations_header', // Integrasi - header only
            'fasiliti_header', // Fasiliti & Tempahan - header only
            'program_pendidikan_header', // Program & Pendidikan - submenu header
            'jadual_tugas_header', // Jadual Tugas - submenu header
            'khidmat_komuniti_header', // Khidmat Komuniti - submenu header
            'tetapan_asnaf', // Tetapan Asnaf - header for TABs
            'tetapan_kebajikan', // Tetapan Kebajikan - header for TABs
            'tetapan_kewangan', // Tetapan Kewangan - header for TABs
            'laporan_kewangan', // Laporan Kewangan - header for TABs
        ];
    }

    /**
     * Get modules that have settings-only actions (read and update only)
     */
    private function getSettingsOnlyModules()
    {
        return [
            'settings', // Tetapan Umum - read and update only
            
            // Tetapan Asnaf TABs
            'tetapan_asnaf_had_kifayah',
            'tetapan_asnaf_had_bantuan',
            'tetapan_asnaf_workflow',
            'tetapan_asnaf_permohonan',
            'tetapan_asnaf_kategori',
            'tetapan_asnaf_payment',
            'tetapan_asnaf_display',
            
            // Tetapan Kebajikan TABs
            'tetapan_kebajikan_had_bantuan',
            'tetapan_kebajikan_workflow',
            'tetapan_kebajikan_permohonan',
            'tetapan_kebajikan_kategori_penerima',
            'tetapan_kebajikan_pembayaran',
            'tetapan_kebajikan_paparan',
            'tetapan_kebajikan_kategori',
            
            // Tetapan Kewangan TABs
            'tetapan_kewangan_umum',
            'tetapan_kewangan_kategori',
            
            // Integrasi TABs
            'integrations_email',
            'integrations_weather',
            'integrations_api',
        ];
    }

    /**
     * Get modules that have workflow actions
     */
    private function getWorkflowModules()
    {
        return [
            'kariah', // Ahli Kariah - has approve/reject/suspend/reactivate
            'ajk', // Ahli Jawatankuasa Masjid - has approve/reject/suspend/reactivate
            'asnaf', // Asnaf - has approve/reject/suspend/reactivate
            'permohonan_zakat', // Permohonan Zakat - has approve/reject only
            'masjids', // Senarai Masjid - has approve/reject/suspend/reactivate (Super Admin only)
            'users', // Senarai Pengguna - has suspend/reactivate (verify/unverify)
        ];
    }
    
    /**
     * Get modules that have partial workflow (approve/reject only, no suspend/reactivate)
     */
    private function getPartialWorkflowModules()
    {
        return [
            'permohonan_zakat', // Permohonan Zakat - approve/reject only
            'kelulusan_pelupusan', // Kelulusan Pelupusan - approve/reject only
        ];
    }
}
