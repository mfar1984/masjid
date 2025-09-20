<?php

namespace App\Http\Controllers;

use App\Models\DocumentFolder;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentFolderController extends Controller
{
    /**
     * Store a newly created folder.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'create')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk menambah folder.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'parent_folder_id' => 'nullable|exists:document_folders,id',
        ]);

        try {
            DB::beginTransaction();

            // Handle masjid_id assignment - CONSISTENT WITH DocumentController
            $masjidId = $user->masjid_id;

            // For Super Admin, folders should belong to Super Admin (masjid_id = null)
            // unless explicitly creating in a specific folder that belongs to a masjid
            if ($user->isSuperAdmin() && !$masjidId) {
                if ($request->parent_folder_id) {
                    $parentFolder = DocumentFolder::withoutGlobalScope('masjid')->find($request->parent_folder_id);
                    $masjidId = $parentFolder ? $parentFolder->masjid_id : null;
                }

                // For Super Admin personal folders, keep masjid_id as NULL
                // This allows Super Admin to have personal folders separate from any masjid
                if (!$masjidId) {
                    $masjidId = null; // Super Admin personal folders
                }
            }

            $folder = DocumentFolder::create([
                'name' => $request->name,
                'description' => $request->description,
                'color' => $request->color ?? '#3B82F6',
                'parent_folder_id' => $request->parent_folder_id,
                'masjid_id' => $masjidId,
                'created_by' => $user->id,
            ]);

            // Update path
            $folder->updatePath();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Folder berjaya dicipta.',
                'folder' => $folder->load('creator')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ralat berlaku semasa mencipta folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified folder.
     */
    public function update(Request $request, DocumentFolder $folder)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'update')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengemaskini folder.');
        }

        // Check if user can access this folder
        if (!$this->canAccessFolder($folder, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses folder ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'parent_folder_id' => 'nullable|exists:document_folders,id',
        ]);

        try {
            DB::beginTransaction();

            $folder->update([
                'name' => $request->name,
                'description' => $request->description,
                'color' => $request->color ?? $folder->color,
                'parent_folder_id' => $request->parent_folder_id,
                'updated_by' => $user->id,
            ]);

            // Update path if parent changed
            if ($folder->wasChanged('parent_folder_id') || $folder->wasChanged('name')) {
                $folder->updatePath();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Folder berjaya dikemaskini.',
                'folder' => $folder->fresh()->load('creator', 'updater')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ralat berlaku semasa mengemaskini folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified folder.
     */
    public function destroy(DocumentFolder $folder)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'delete')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk memadam folder.');
        }

        // Check if user can access this folder
        if (!$this->canAccessFolder($folder, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses folder ini.');
        }

        // Check if folder has contents
        if ($folder->documents()->count() > 0 || $folder->childFolders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Folder tidak boleh dipadam kerana masih mengandungi dokumen atau subfolder.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Delete all shares
            $folder->shares()->delete();

            // Delete folder
            $folder->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Folder berjaya dipadam.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ralat berlaku semasa memadam folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle star status for folder
     */
    public function toggleStar(DocumentFolder $folder)
    {
        $user = Auth::user();

        // Check if user can access this folder
        if (!$this->canAccessFolder($folder, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses folder ini.');
        }

        $folder->update(['is_starred' => !$folder->is_starred]);

        return response()->json([
            'success' => true,
            'is_starred' => $folder->is_starred,
            'message' => $folder->is_starred ? 'Folder ditambah ke kegemaran.' : 'Folder dikeluarkan dari kegemaran.'
        ]);
    }

    /**
     * Helper method to check if user can access folder
     */
    private function canAccessFolder(DocumentFolder $folder, $user): bool
    {
        // Super Admin can access all folders
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Owner masjid can always access
        if ($folder->masjid_id === $user->masjid_id) {
            return true;
        }

        // Check if folder is shared with user's masjid
        $share = $folder->shares()
            ->where('shared_with_masjid_id', $user->masjid_id)
            ->active()
            ->notExpired()
            ->first();

        return $share !== null;
    }

    /**
     * Update folder color
     */
    public function updateColor(Request $request, DocumentFolder $folder)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'update')) {
            return response()->json(['success' => false, 'message' => 'Anda tidak mempunyai kebenaran untuk mengemas kini folder.'], 403);
        }

        // Check if user can access this folder
        if ($folder->masjid_id !== $user->masjid_id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak mempunyai kebenaran untuk mengakses folder ini.'], 403);
        }

        // Validate color
        $request->validate([
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $folder->update([
            'color' => $request->color,
            'updated_by' => $user->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Warna folder telah dikemas kini.']);
    }
}
