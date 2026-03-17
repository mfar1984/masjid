<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\DocumentShare;
use App\Models\DocumentAccessRequest;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentSharingController extends Controller
{
    /**
     * Get sharing data for a document or folder
     */
    public function getSharingData(Request $request, $type, $id)
    {
        try {
            $user = Auth::user();
            
            // Get the item (document or folder)
            $item = $this->getItem($type, $id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemui'
                ], 404);
            }

            // Super Admin can access any document, regular users only their own masjid's documents
            if (!$user->isSuperAdmin() && $item->masjid_id !== $user->masjid_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak mempunyai akses'
                ], 403);
            }

            // Get shared masjids
            $query = DocumentShare::where('shareable_type', $this->getModelClass($type))
                ->where('shareable_id', $item->id)
                ->where('status', 'active')
                ->with('sharedWithMasjid');

            // Super Admin can see ALL shares for any document
            // Regular users can only see shares they created
            if (!$user->isSuperAdmin()) {
                $query->where('shared_by_masjid_id', $user->masjid_id);
            }
            // Note: Super Admin sees all shares (no additional filter)

            $sharedMasjids = $query->get()
                ->filter(function ($share) {
                    // Only include shares with specific masjids (not public links)
                    return $share->sharedWithMasjid !== null;
                })
                ->map(function ($share) {
                    return [
                        'kod_masjid' => $share->sharedWithMasjid->kod_masjid,
                        'nama' => $share->sharedWithMasjid->nama,
                        'permission_level' => $share->permission_level,
                        'shared_at' => $share->created_at->format('Y-m-d H:i:s')
                    ];
                });

            // Get current access level (check if public link exists)
            // Always check for ANY active public share (regardless of who created it)
            $publicShare = DocumentShare::where('shareable_type', $this->getModelClass($type))
                ->where('shareable_id', $item->id)
                ->where('is_public_link', true)
                ->where('status', 'active')
                ->first();

            $accessLevel = $publicShare ? 'anyone_with_link' : 'restricted';

            return response()->json([
                'success' => true,
                'data' => [
                    'item' => [
                        'type' => $type,
                        'id' => $id,
                        'name' => $item->name
                    ],
                    'current_user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'initials' => $user->initials,
                        'role' => $user->role ? $user->role->name : 'Owner',
                        'masjid' => $user->masjid ? [
                            'id' => $user->masjid->id,
                            'nama' => $user->masjid->nama,
                        ] : null
                    ],
                    'shared_masjids' => $sharedMasjids,
                    'access_level' => $accessLevel,
                    'share_link' => $publicShare ? url("/share/{$publicShare->share_token}") : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat mendapatkan data perkongsian'
            ], 500);
        }
    }

    /**
     * Share document/folder with another masjid
     */
    public function shareWithMasjid(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:document,folder',
            'item_id' => 'required|string', // Changed from integer to string to handle hash tokens
            'kod_masjid' => 'required|string|size:6',
            'permission_level' => 'required|in:view,comment,edit,full_access'
        ]);

        try {
            $user = Auth::user();
            
            // Get the item
            $item = $this->getItem($request->item_type, $request->item_id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemui'
                ], 404);
            }

            // Check access permissions
            if ($user->isSuperAdmin()) {
                // Super Admin can share any item (including their own null masjid_id items)
                // No additional permission check needed
            } else {
                // Regular users can only share items from their own masjid
                if ($item->masjid_id !== $user->masjid_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak mempunyai akses untuk berkongsi item ini'
                    ], 403);
                }
            }

            // Find target masjid
            $targetMasjid = Masjid::where('kod_masjid', $request->kod_masjid)->first();
            if (!$targetMasjid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kod Masjid tidak ditemui'
                ], 404);
            }

            // Check if trying to share with own masjid (not applicable for Super Admin)
            if (!$user->isSuperAdmin() && $targetMasjid->id === $user->masjid_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak boleh berkongsi dengan masjid sendiri'
                ], 400);
            }

            // Check if already shared (use database ID, not hash token)
            $existingShareQuery = DocumentShare::where('shareable_type', $this->getModelClass($request->item_type))
                ->where('shareable_id', $item->id) // Use database ID instead of hash token
                ->where('shared_with_masjid_id', $targetMasjid->id)
                ->where('status', 'active');

            // Handle Super Admin (null masjid_id) vs regular users
            if ($user->masjid_id === null) {
                $existingShareQuery->whereNull('shared_by_masjid_id');
            } else {
                $existingShareQuery->where('shared_by_masjid_id', $user->masjid_id);
            }

            $existingShare = $existingShareQuery->first();

            if ($existingShare) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item sudah dikongsi dengan masjid ini'
                ], 400);
            }

            // Create share record (use database ID, not hash token)
            DocumentShare::create([
                'shareable_type' => $this->getModelClass($request->item_type),
                'shareable_id' => $item->id, // Use database ID instead of hash token
                'shared_by_masjid_id' => $user->masjid_id,
                'shared_by_user_id' => $user->id,
                'shared_with_masjid_id' => $targetMasjid->id,
                'permission_level' => $request->permission_level,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item berjaya dikongsi'
            ]);

        } catch (\Exception $e) {
            \Log::error('DocumentSharing Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'item_type' => $request->item_type,
                'item_id' => $request->item_id,
                'kod_masjid' => $request->kod_masjid,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ralat berkongsi item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update permission level for a specific masjid share
     */
    public function updatePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_type' => 'required|in:document,folder',
            'item_id' => 'required|string',
            'kod_masjid' => 'required|string|size:6',
            'permission_level' => 'required|in:view,comment,edit,full_access'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sah',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            // Get the item (document or folder)
            $item = $this->getItem($request->item_type, $request->item_id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemui'
                ], 404);
            }

            // Check access permissions
            if ($user->isSuperAdmin()) {
                // Super Admin can update any item permissions
            } else {
                // Regular users can only update items from their own masjid
                if ($item->masjid_id !== $user->masjid_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak mempunyai akses untuk mengemas kini item ini'
                    ], 403);
                }
            }

            // Find target masjid
            $targetMasjid = Masjid::where('kod_masjid', $request->kod_masjid)->first();
            if (!$targetMasjid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kod Masjid tidak sah'
                ], 404);
            }

            // Find existing share
            $share = DocumentShare::where('shareable_type', $this->getModelClass($request->item_type))
                ->where('shareable_id', $item->id)
                ->where('shared_by_masjid_id', $user->isSuperAdmin() ? null : $user->masjid_id)
                ->where('shared_with_masjid_id', $targetMasjid->id)
                ->where('status', 'active')
                ->first();

            if (!$share) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perkongsian tidak ditemui'
                ], 404);
            }

            // Update permission
            $share->permission_level = $request->permission_level;
            $share->save();

            return response()->json([
                'success' => true,
                'message' => 'Kebenaran telah dikemaskini'
            ]);

        } catch (\Exception $e) {
            \Log::error('DocumentSharing UpdatePermission Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'item_type' => $request->item_type,
                'item_id' => $request->item_id,
                'kod_masjid' => $request->kod_masjid,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ralat mengemas kini kebenaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove sharing with a masjid
     */
    public function unshareWithMasjid(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:document,folder',
            'item_id' => 'required|string', // Changed from integer to string to handle hash tokens
            'kod_masjid' => 'required|string|size:6'
        ]);

        try {
            $user = Auth::user();

            // Get the item
            $item = $this->getItem($request->item_type, $request->item_id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemui'
                ], 404);
            }

            // Find target masjid
            $targetMasjid = Masjid::where('kod_masjid', $request->kod_masjid)->first();
            if (!$targetMasjid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kod Masjid tidak ditemui'
                ], 404);
            }

            // Find and remove share
            $shareQuery = DocumentShare::where('shareable_type', $this->getModelClass($request->item_type))
                ->where('shareable_id', $item->id)
                ->where('shared_with_masjid_id', $targetMasjid->id)
                ->where('status', 'active');

            // Super Admin can unshare any share, regular users can only unshare their own
            if (!$user->isSuperAdmin()) {
                $shareQuery->where('shared_by_masjid_id', $user->masjid_id);
            }

            $share = $shareQuery->first();

            if (!$share) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perkongsian tidak ditemui'
                ], 404);
            }

            $share->update(['status' => 'revoked']);

            return response()->json([
                'success' => true,
                'message' => 'Perkongsian telah dihentikan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat menghentikan perkongsian'
            ], 500);
        }
    }

    /**
     * Update access level (restricted/anyone with link)
     */
    public function updateAccessLevel(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:document,folder',
            'item_id' => 'required|string', // Changed from integer to string to handle hash tokens
            'access_level' => 'required|in:restricted,anyone_with_link'
        ]);

        try {
            $user = Auth::user();
            
            // Get the item
            $item = $this->getItem($request->item_type, $request->item_id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemui'
                ], 404);
            }

            // Check access - Super Admin can access any item, regular users only their own
            if (!$user->isSuperAdmin() && $item->masjid_id !== $user->masjid_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak mempunyai akses'
                ], 403);
            }

            if ($request->access_level === 'anyone_with_link') {
                // Create or activate public link - REUSE existing active share if available
                $publicShare = DocumentShare::where('shareable_type', $this->getModelClass($request->item_type))
                    ->where('shareable_id', $item->id)
                    ->where('is_public_link', true)
                    ->where('status', 'active')
                    ->first(); // Remove masjid_id filter to reuse ANY active public share

                if (!$publicShare) {
                    // Only create new share if no active public share exists
                    DocumentShare::create([
                        'shareable_type' => $this->getModelClass($request->item_type),
                        'shareable_id' => $item->id,
                        'shared_by_masjid_id' => $user->masjid_id,
                        'shared_by_user_id' => $user->id,
                        'shared_with_masjid_id' => null, // Public link - no specific masjid
                        'shared_with_user_id' => null,   // Public link - no specific user
                        'is_public_link' => true,
                        'share_token' => Str::random(32),
                        'status' => 'active'
                    ]);
                }
                // If active share already exists, no need to do anything - just reuse it
            } else {
                // Deactivate ALL public links for this item (regardless of who created them)
                // This ensures the document becomes truly restricted
                DocumentShare::where('shareable_type', $this->getModelClass($request->item_type))
                    ->where('shareable_id', $item->id)
                    ->where('is_public_link', true)
                    ->where('status', 'active')
                    ->update(['status' => 'revoked']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tahap akses telah dikemaskini'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat mengemas kini tahap akses'
            ], 500);
        }
    }

    /**
     * Get share link for public sharing
     */
    public function getShareLink(Request $request, $type, $id)
    {
        try {
            $user = Auth::user();
            
            // Get the item
            $item = $this->getItem($type, $id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemui'
                ], 404);
            }

            // Check access - Super Admin can access any item, regular users only their own
            if (!$user->isSuperAdmin() && $item->masjid_id !== $user->masjid_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak mempunyai akses'
                ], 403);
            }

            // Check if there's an active public share to determine access level
            $existingPublicShare = DocumentShare::where('shareable_type', $this->getModelClass($type))
                ->where('shareable_id', $item->id)
                ->where('is_public_link', true)
                ->where('status', 'active')
                ->first();

            // If no active public share exists, it means access level is restricted
            if (!$existingPublicShare) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses terhad - tidak boleh mendapatkan pautan'
                ], 403);
            }

            // Reuse the existing active public share (we already checked it exists above)
            $publicShare = $existingPublicShare;

            return response()->json([
                'success' => true,
                'data' => [
                    'share_link' => url("/share/{$publicShare->share_token}")
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat mendapatkan pautan'
            ], 500);
        }
    }

    /**
     * View public shared document/folder
     */
    public function viewPublicShare($token)
    {
        try {
            // Find the share by token
            $share = DocumentShare::where('share_token', $token)
                ->where('is_public_link', true)
                ->where('status', 'active')
                ->first();

            if (!$share) {
                abort(404, 'Share link not found or expired');
            }

            // Get the shared item first to check access level
            $item = null;
            if ($share->shareable_type === Document::class) {
                $item = Document::find($share->shareable_id);
                $type = 'document';
            } elseif ($share->shareable_type === DocumentFolder::class) {
                $item = DocumentFolder::find($share->shareable_id);
                $type = 'folder';
            }

            if (!$item) {
                abort(404, 'Shared item not found');
            }

            // Check if this is a restricted document that requires authentication
            $isRestricted = $this->isDocumentRestricted($item, $share);

            if ($isRestricted && !Auth::check()) {
                // Redirect to login with return URL
                return redirect()->route('login')->with([
                    'message' => 'Sila log masuk untuk mengakses dokumen terhad ini.',
                    'return_url' => request()->url()
                ]);
            }

            if ($isRestricted && Auth::check()) {
                // User is logged in but needs to request access
                return $this->showRequestAccessPage($token, $item, $type, $share);
            }

            // Update access tracking for public access
            $share->increment('access_count');
            $share->update([
                'last_accessed_at' => now(),
                'first_accessed_at' => $share->first_accessed_at ?? now()
            ]);

            // Return view with shared item data (for public access)
            return view('public.share', [
                'item' => $item,
                'type' => $type,
                'share' => $share,
                'permission_level' => $share->permission_level
            ]);

        } catch (\Exception $e) {
            abort(404, 'Invalid share link');
        }
    }

    /**
     * Download public shared document
     */
    public function downloadPublicShare($token, $itemToken)
    {
        try {
            // Find the share by token
            $share = DocumentShare::where('share_token', $token)
                ->where('is_public_link', true)
                ->where('status', 'active')
                ->first();

            if (!$share) {
                abort(404, 'Share link not found or expired');
            }

            // Check if download is allowed
            if ($share->permission_level === 'view' && !$share->can_download) {
                abort(403, 'Download not allowed');
            }

            // Find the document by hash token
            $document = Document::where('hash_token', $itemToken)->first();
            if (!$document) {
                abort(404, 'Document not found');
            }

            // Update access tracking
            $share->increment('access_count');
            $share->update(['last_accessed_at' => now()]);

            // Return file download
            $filePath = storage_path('app/' . $document->file_path);
            if (!file_exists($filePath)) {
                abort(404, 'File not found');
            }

            return response()->download($filePath, $document->original_filename);

        } catch (\Exception $e) {
            abort(404, 'Invalid download link');
        }
    }

    /**
     * Check if document is restricted (requires authentication/approval)
     */
    private function isDocumentRestricted($item, $share)
    {
        // Check if there's an active public share for this item
        $publicShare = DocumentShare::where('shareable_type', get_class($item))
            ->where('shareable_id', $item->id)
            ->where('is_public_link', true)
            ->where('status', 'active')
            ->first();

        // If no active public share exists, it's restricted
        return !$publicShare;
    }

    /**
     * Show request access page for restricted documents
     */
    private function showRequestAccessPage($token, $item, $type, $share)
    {
        return view('documents.request-access', [
            'token' => $token,
            'item' => $item,
            'type' => $type,
            'share' => $share
        ]);
    }

    /**
     * Handle access request for restricted documents
     */
    public function requestAccess(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'item_type' => 'required|in:document,folder',
            'item_id' => 'required|string',
            'reason' => 'required|string|max:1000',
            'requested_permission' => 'required|in:view,comment,edit'
        ]);

        try {
            $user = Auth::user();

            if (!$user || !$user->masjid_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda perlu log masuk dengan akaun masjid yang sah'
                ], 401);
            }

            // Check if request already exists
            $existingRequest = DocumentAccessRequest::where('share_token', $request->token)
                ->where('requester_masjid_id', $user->masjid_id)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah menghantar permohonan untuk dokumen ini. Sila tunggu keputusan.'
                ]);
            }

            // Create access request
            DocumentAccessRequest::create([
                'share_token' => $request->token,
                'item_type' => $request->item_type,
                'item_id' => $request->item_id,
                'requester_masjid_id' => $user->masjid_id,
                'requester_user_id' => $user->id,
                'reason' => $request->reason,
                'requested_permission' => $request->requested_permission,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan akses telah dihantar. Anda akan menerima notifikasi sebaik sahaja permohonan diproses.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat sistem. Sila cuba lagi.'
            ], 500);
        }
    }

    /**
     * Helper method to get item by type and id
     */
    private function getItem($type, $id)
    {
        if ($type === 'document') {
            // Try to find by hash token first, then by ID
            $document = Document::where('hash_token', $id)->first();
            if (!$document) {
                $document = Document::find($id);
            }
            return $document;
        } elseif ($type === 'folder') {
            // Try to find by hash token first, then by ID
            $folder = DocumentFolder::where('hash_token', $id)->first();
            if (!$folder) {
                $folder = DocumentFolder::find($id);
            }
            return $folder;
        }
        return null;
    }

    /**
     * Helper method to get model class by type
     */
    private function getModelClass($type)
    {
        if ($type === 'document') {
            return Document::class;
        } elseif ($type === 'folder') {
            return DocumentFolder::class;
        }
        return null;
    }
}
