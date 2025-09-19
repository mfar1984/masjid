<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base query with relationships
        $baseQuery = User::with(['role', 'masjid']);
        
        // Multi-Masjid Data Isolation - STRICT MODE
        if ($user->isSuperAdmin()) {
            // Super Admin can see all users
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see users from their own masjid
            // NO Super Admin users visible to maintain strict isolation
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no users
                $baseQuery->whereRaw('1 = 0'); // Always false condition
            }
        }
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $baseQuery->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        if ($request->filled('masjid')) {
            $baseQuery->where('masjid_id', $request->masjid);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $baseQuery->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $baseQuery->whereNull('email_verified_at');
            }
        }
        
        // Get paginated results
        $users = $baseQuery->orderBy('created_at', 'desc')->paginate(10);
        
        // Build stats array - FIXED 3 CARDS ONLY
        $totalUsers = (clone $baseQuery)->count();
        $verifiedUsers = (clone $baseQuery)->whereNotNull('email_verified_at')->count();
        $unverifiedUsers = (clone $baseQuery)->whereNull('email_verified_at')->count();

        // Always show exactly 3 cards for consistent layout
        $stats = [
            [
                'title' => 'Jumlah Pengguna',
                'value' => $totalUsers,
                'icon' => 'people',
                'color' => 'blue'
            ],
            [
                'title' => 'Belum Disahkan',
                'value' => $unverifiedUsers,
                'icon' => 'pending',
                'color' => 'orange'
            ],
            [
                'title' => 'Disahkan',
                'value' => $verifiedUsers,
                'icon' => 'verified_user',
                'color' => 'green'
            ]
        ];
        
        // Filter options
        $roleOptions = Role::active()->pluck('name', 'name')->toArray();
        $masjidOptions = Masjid::active()->pluck('nama', 'id')->toArray();
        
        return view('pentadbiran.senarai-pengguna', compact(
            'users', 
            'stats', 
            'roleOptions', 
            'masjidOptions'
        ));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $user = auth()->user();

        // Get available roles based on user permissions
        if ($user->isSuperAdmin()) {
            $roles = Role::active()->get();
            $masjids = Masjid::active()->get();
        } else {
            // Admin Masjid can only assign roles from their masjid (no global roles)
            $userMasjidId = $user->masjid_id ?? 1;
            $roles = Role::active()->where('masjid_id', $userMasjidId)->get();
            $masjids = Masjid::active()->where('id', $userMasjidId)->get();
        }

        return view('pentadbiran.pengguna.create', compact('roles', 'masjids'));
    }
    
    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'masjid_id' => 'nullable|exists:masjids,id',
        ]);

        // Data Integrity Validation: Ensure role belongs to the same masjid or is global
        $role = Role::find($request->role_id);
        $masjidId = $request->masjid_id ?: null; // Convert empty string to null

        // Convert masjidId to integer if it's a numeric string
        if ($masjidId && is_numeric($masjidId)) {
            $masjidId = (int) $masjidId;
        }

        if ($role && $role->masjid_id !== null && $role->masjid_id !== $masjidId) {
            return back()->withErrors([
                'role_id' => 'Role yang dipilih tidak sesuai dengan masjid yang ditetapkan.'
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'masjid_id' => $masjidId, // Use processed masjidId
        ]);

        return redirect()->route('senarai-pengguna.index')
            ->with('success', 'Pengguna berjaya dicipta.');
    }
    
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $currentUser = auth()->user();

        // Multi-Masjid Data Isolation - STRICT MODE
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only view users from their own masjid
            if ($user->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk melihat pengguna ini.');
            }
        }

        $user->load(['role', 'masjid']);

        return view('pentadbiran.pengguna.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $currentUser = auth()->user();

        // Check if current user can edit this user
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only edit users from their own masjid
            if ($user->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengedit pengguna ini.');
            }
        }

        // Get available roles based on user permissions
        if ($currentUser->isSuperAdmin()) {
            $roles = Role::active()->get();
            $masjids = Masjid::active()->get();
        } else {
            // Admin Masjid can only assign roles from their masjid (no global roles)
            $userMasjidId = $currentUser->masjid_id ?? 1;
            $roles = Role::active()->where('masjid_id', $userMasjidId)->get();
            $masjids = Masjid::active()->where('id', $userMasjidId)->get();
        }

        return view('pentadbiran.pengguna.edit', compact('user', 'roles', 'masjids'));
    }
    
    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Check if current user can update this user
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only update users from their own masjid
            if ($user->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengemaskini pengguna ini.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'masjid_id' => 'nullable|exists:masjids,id',
        ]);

        // Data Integrity Validation: Ensure role belongs to the same masjid or is global
        $role = Role::find($request->role_id);
        $masjidId = $request->masjid_id ?: null; // Convert empty string to null (SAME AS CREATE)

        // Convert masjidId to integer if it's a numeric string (SAME AS CREATE)
        if ($masjidId && is_numeric($masjidId)) {
            $masjidId = (int) $masjidId;
        }

        if ($role && $role->masjid_id !== null && $role->masjid_id !== $masjidId) {
            return back()->withErrors([
                'role_id' => 'Role yang dipilih tidak sesuai dengan masjid yang ditetapkan.'
            ])->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'masjid_id' => $masjidId, // Use processed masjidId (SAME AS CREATE)
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        
        return redirect()->route('senarai-pengguna.index')
            ->with('success', 'Pengguna berjaya dikemaskini.');
    }
    
    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        // Multi-Masjid Data Isolation - STRICT MODE
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only delete users from their own masjid
            if ($user->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk memadamkan pengguna ini.');
            }
        }

        // Prevent deleting current user
        if ($user->id === auth()->id()) {
            return redirect()->route('senarai-pengguna.index')
                ->with('error', 'Anda tidak boleh memadamkan akaun anda sendiri.');
        }

        $user->delete();

        return redirect()->route('senarai-pengguna.index')
            ->with('success', 'Pengguna berjaya dipadamkan.');
    }

    /**
     * Verify/Activate a user manually by admin.
     */
    public function verify(User $user)
    {
        $currentUser = auth()->user();

        // Check if current user can verify this user
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only verify users from their own masjid
            if ($user->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengesahkan pengguna ini.');
            }
        }

        // Check if user is already verified
        if ($user->email_verified_at) {
            return redirect()->route('senarai-pengguna.index')
                ->with('error', 'Pengguna ' . $user->name . ' sudah disahkan.');
        }

        // Mark user as verified
        $user->markEmailAsVerified();

        return redirect()->route('senarai-pengguna.index')
            ->with('success', 'Pengguna ' . $user->name . ' berjaya disahkan dan diaktifkan.');
    }

    /**
     * Unverify/Deactivate a user manually by admin.
     */
    public function unverify(User $user)
    {


        $currentUser = auth()->user();

        // Check if current user can unverify this user
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only unverify users from their own masjid
            if ($user->masjid_id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk menyahkan pengguna ini.');
            }
        }

        // Check if user is already unverified
        if (!$user->email_verified_at) {
            return redirect()->route('senarai-pengguna.index')
                ->with('error', 'Pengguna ' . $user->name . ' sudah dalam status pending.');
        }

        // Prevent unverifying self
        if ($user->id === $currentUser->id) {
            return redirect()->route('senarai-pengguna.index')
                ->with('error', 'Anda tidak boleh menyahkan akaun anda sendiri.');
        }

        // Mark user as unverified
        $user->forceFill(['email_verified_at' => null]);
        $user->save();
        return redirect()->route('senarai-pengguna.index')
            ->with('success', 'Pengguna ' . $user->name . ' berjaya dinyahaktifkan.');
    }
}
