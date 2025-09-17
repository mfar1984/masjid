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
        $workflowModules = $this->getWorkflowModules();

        // Get masjids for Super Admin to choose from
        $masjids = collect();
        if (auth()->user()->isSuperAdmin()) {
            $masjids = \App\Models\Masjid::where('status', 'active')
                                        ->orderBy('nama')
                                        ->get(['id', 'nama']);
        }

        return view('pentadbiran.kumpulan.create', compact('modules', 'actions', 'readOnlyModules', 'workflowModules', 'masjids'));
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
        $workflowModules = $this->getWorkflowModules();

        return view('pentadbiran.kumpulan.show', compact('role', 'modules', 'actions', 'readOnlyModules', 'workflowModules'));
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
        $workflowModules = $this->getWorkflowModules();

        // Get masjids for Super Admin to choose from
        $masjids = collect();
        if ($currentUser->isSuperAdmin()) {
            $masjids = \App\Models\Masjid::where('status', 'active')
                                        ->orderBy('nama')
                                        ->get(['id', 'nama']);
        }

        return view('pentadbiran.kumpulan.edit', compact('role', 'modules', 'actions', 'readOnlyModules', 'workflowModules', 'masjids'));
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
            'dashboard' => 'Paparan Pemuka',
            'masjids' => 'Senarai Masjid',
            'users' => 'Senarai Pengguna',
            'roles' => 'Senarai Kumpulan',
            // 'settings' => 'Tetapan Umum', // Module belum dibuat - removed temporarily
            // 'support' => 'Bantuan & Sokongan', // Global module - removed from permission matrix
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
            'dashboard', // Paparan Pemuka - view only
            // 'support' removed - global module
        ];
    }

    /**
     * Get modules that have workflow actions
     */
    private function getWorkflowModules()
    {
        return [
            'masjids', // Senarai Masjid - has approve/reject/suspend/reactivate
            'users', // Senarai Pengguna - has suspend/reactivate (verify/unverify)
        ];
    }
}
