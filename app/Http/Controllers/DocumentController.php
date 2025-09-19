<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\DocumentShare;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'read')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk melihat dokumen.');
        }



        // Get current folder - Use consistent approach
        $currentFolder = null;
        if ($request->filled('folder')) {
            // Use withoutGlobalScope for consistency and manual access control
            $currentFolder = DocumentFolder::withoutGlobalScope('masjid')->findOrFail($request->folder);

            // WAJIB: Check access permission for non-Super Admin
            if (!$user->isSuperAdmin()) {
                if ($currentFolder->masjid_id !== $user->masjid_id) {
                    abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses folder ini.');
                }
            }
        }

        // Base query with relationships - COPY SENARAI-PENGGUNA PATTERN
        $documentsQuery = Document::with(['folder', 'creator', 'shares', 'masjid']);

        // Multi-Masjid Data Isolation - STRICT MODE (EXACT COPY FROM UserController)
        if ($user->isSuperAdmin()) {
            // Super Admin can see all documents
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see documents from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $documentsQuery->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no documents
                $documentsQuery->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Apply folder filter
        if ($currentFolder) {
            $documentsQuery->where('folder_id', $currentFolder->id);
        } else {
            $documentsQuery->whereNull('folder_id'); // Root documents
        }

        // Base query with relationships - COPY SENARAI-PENGGUNA PATTERN
        $foldersQuery = DocumentFolder::with(['creator', 'shares', 'masjid']);

        // Multi-Masjid Data Isolation - STRICT MODE (EXACT COPY FROM UserController)
        if ($user->isSuperAdmin()) {
            // Super Admin can see all folders
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see folders from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $foldersQuery->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no folders
                $foldersQuery->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Apply folder filter
        if ($currentFolder) {
            $foldersQuery->where('parent_folder_id', $currentFolder->id);
        } else {
            $foldersQuery->whereNull('parent_folder_id'); // Root folders
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $documentsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('original_filename', 'like', "%{$search}%");
            });

            $foldersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            switch ($request->type) {
                case 'starred':
                    $documentsQuery->starred();
                    $foldersQuery->starred();
                    break;
                case 'shared':
                    $documentsQuery->shared();
                    $foldersQuery->shared();
                    break;
                case 'recent':
                    $documentsQuery->recent();
                    break;
            }
        }

        // Filter by file extension
        if ($request->filled('extension')) {
            $documentsQuery->byExtension($request->extension);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'name'); // Default to name
        switch ($sortBy) {
            case 'date':
                $documentsQuery->orderBy('created_at', 'desc');
                $foldersQuery->orderBy('created_at', 'desc');
                break;
            case 'size':
                $documentsQuery->orderBy('file_size', 'desc');
                $foldersQuery->orderBy('sort_order')->orderBy('name'); // Folders don't have size
                break;
            case 'type':
                $documentsQuery->orderBy('file_extension')->orderBy('name');
                $foldersQuery->orderBy('sort_order')->orderBy('name');
                break;
            case 'name':
            default:
                $documentsQuery->orderBy('name');
                $foldersQuery->orderBy('sort_order')->orderBy('name');
                break;
        }

        // Get results
        $documents = $documentsQuery->paginate(20, ['*'], 'documents_page');
        $folders = $foldersQuery->get();

        // Add computed properties to folders for JavaScript
        $folders->each(function ($folder) use ($user) {
            // Pass user's masjid_id for proper data isolation
            $userMasjidId = $user->isSuperAdmin() ? null : $user->masjid_id;
            $folder->total_documents = $folder->getTotalDocuments($userMasjidId);
            $folder->total_size = $folder->getTotalSize($userMasjidId);
        });







        // Get breadcrumb path
        $breadcrumbs = $this->getBreadcrumbs($currentFolder);

        // Get shared documents/folders for current masjid
        $sharedItems = $this->getSharedItems($user);

        // Statistics
        $stats = $this->getStatistics($user);

        return view('documents.index', compact(
            'documents',
            'folders',
            'currentFolder',
            'breadcrumbs',
            'sharedItems',
            'stats',
            'sortBy'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'create')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk menambah dokumen.');
        }

        // Get current folder - Use consistent approach
        $currentFolder = null;
        if ($request->filled('folder')) {
            $currentFolder = DocumentFolder::withoutGlobalScope('masjid')->findOrFail($request->folder);

            // WAJIB: Check access permission for non-Super Admin
            if (!$user->isSuperAdmin()) {
                if ($currentFolder->masjid_id !== $user->masjid_id) {
                    abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses folder ini.');
                }
            }
        }

        // Get all folders for dropdown - Use consistent approach
        $foldersQuery = DocumentFolder::withoutGlobalScope('masjid');

        if ($user->isSuperAdmin()) {
            $foldersQuery->with('masjid');
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see folders from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $foldersQuery->where('masjid_id', $userMasjidId);
            } else {
                $foldersQuery->whereRaw('1 = 0');
            }
        }

        $folders = $foldersQuery->orderBy('path')->get();

        return view('documents.create', compact('currentFolder', 'folders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'create')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk menambah dokumen.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'folder_id' => 'nullable|exists:document_folders,id',
            'file' => 'required|file|max:51200', // 50MB max
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Determine masjid_id for file storage and database
            $masjidId = $user->masjid_id;

            // For Super Admin, documents should belong to Super Admin (masjid_id = null)
            // unless explicitly uploading to a specific folder that belongs to a masjid
            if ($user->isSuperAdmin() && !$masjidId) {
                if ($request->folder_id) {
                    $folder = DocumentFolder::withoutGlobalScope('masjid')->find($request->folder_id);
                    $masjidId = $folder ? $folder->masjid_id : null;
                }

                // For Super Admin personal documents, keep masjid_id as NULL
                // This allows Super Admin to have personal documents separate from any masjid
                if (!$masjidId) {
                    $masjidId = null; // Super Admin personal documents
                }
            }

            // Generate unique filename
            $filename = Str::uuid() . '.' . $extension;

            // Handle file path for Super Admin personal documents (masjid_id = null)
            $folderPath = $masjidId ? $masjidId : 'super_admin';
            $filePath = 'documents/' . $folderPath . '/' . date('Y/m') . '/' . $filename;

            // Store file
            $file->storeAs('public/' . dirname($filePath), basename($filePath));

            // Calculate file hash for duplicate detection
            $fileHash = hash_file('sha256', $file->getRealPath());

            // Create document record
            $document = Document::create([
                'name' => $request->name,
                'description' => $request->description,
                'original_filename' => $originalFilename,
                'file_path' => $filePath,
                'file_extension' => strtolower($extension),
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'file_hash' => $fileHash,
                'folder_id' => $request->folder_id,
                'masjid_id' => $masjidId,
                'created_by' => $user->id,
            ]);

            DB::commit();

            return redirect()
                ->route('documents.index', ['folder' => $request->folder_id])
                ->with('success', 'Dokumen berjaya dimuat naik.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($filePath) && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Ralat berlaku semasa memuat naik dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $user = Auth::user();



        // Check permission
        if (!$user->hasPermission('documents', 'read')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk melihat dokumen.');
        }

        // Load relationships
        $document->load(['folder', 'creator', 'updater', 'versions', 'shares.sharedWithMasjid']);

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        // Update last accessed time
        $document->update(['last_accessed_at' => now()]);

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'update')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengemaskini dokumen.');
        }

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        // Get all folders for dropdown - Use consistent approach
        $foldersQuery = DocumentFolder::withoutGlobalScope('masjid');

        if ($user->isSuperAdmin()) {
            $foldersQuery->with('masjid');
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see folders from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $foldersQuery->where('masjid_id', $userMasjidId);
            } else {
                $foldersQuery->whereRaw('1 = 0');
            }
        }

        $folders = $foldersQuery->orderBy('path')->get();

        return view('documents.edit', compact('document', 'folders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'update')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengemaskini dokumen.');
        }

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'folder_id' => 'nullable|exists:document_folders,id',
            'file' => 'nullable|file|max:51200', // 50MB max
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'description' => $request->description,
                'folder_id' => $request->folder_id,
                'updated_by' => $user->id,
            ];

            // Handle file replacement
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();

                // Generate unique filename
                $filename = Str::uuid() . '.' . $extension;
                $filePath = 'documents/' . $user->masjid_id . '/' . date('Y/m') . '/' . $filename;

                // Store new file
                $file->storeAs('public/' . dirname($filePath), basename($filePath));

                // Calculate file hash
                $fileHash = hash_file('sha256', $file->getRealPath());

                // Delete old file
                if (Storage::exists('public/' . $document->file_path)) {
                    Storage::delete('public/' . $document->file_path);
                }

                // Update file-related fields
                $updateData = array_merge($updateData, [
                    'original_filename' => $originalFilename,
                    'file_path' => $filePath,
                    'file_extension' => strtolower($extension),
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                    'file_hash' => $fileHash,
                    'version' => $document->version + 1,
                ]);
            }

            $document->update($updateData);

            DB::commit();

            return redirect()
                ->route('documents.show', $document)
                ->with('success', 'Dokumen berjaya dikemaskini.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($filePath) && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Ralat berlaku semasa mengemaskini dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'delete')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk memadam dokumen.');
        }

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        try {
            DB::beginTransaction();

            // Delete file from storage
            if (Storage::exists('public/' . $document->file_path)) {
                Storage::delete('public/' . $document->file_path);
            }

            // Delete all shares
            $document->shares()->delete();

            // Delete document record
            $document->delete();

            DB::commit();

            return redirect()
                ->route('documents.index', ['folder' => $document->folder_id])
                ->with('success', 'Dokumen berjaya dipadam.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Ralat berlaku semasa memadam dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download document file
     */
    public function download(Document $document)
    {
        $user = Auth::user();

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        // Check if file exists
        if (!Storage::exists('public/' . $document->file_path)) {
            abort(404, 'Fail tidak dijumpai.');
        }

        // Increment download count
        $document->incrementDownloadCount();

        // Return file download response
        return Storage::download('public/' . $document->file_path, $document->original_filename);
    }

    /**
     * Preview document (for images, PDFs, text files)
     */
    public function preview(Document $document)
    {
        $user = Auth::user();

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        // Check if file is previewable
        if (!$document->isPreviewable()) {
            abort(400, 'Dokumen ini tidak boleh dipratonton.');
        }

        // Check if file exists
        if (!Storage::exists('public/' . $document->file_path)) {
            abort(404, 'Fail tidak dijumpai.');
        }

        // Update last accessed time
        $document->update(['last_accessed_at' => now()]);

        // Return file response
        return Storage::response('public/' . $document->file_path);
    }

    /**
     * Toggle star status
     */
    public function toggleStar(Document $document)
    {
        $user = Auth::user();

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        $document->update(['is_starred' => !$document->is_starred]);

        return response()->json([
            'success' => true,
            'is_starred' => $document->is_starred,
            'message' => $document->is_starred ? 'Dokumen ditambah ke kegemaran.' : 'Dokumen dikeluarkan dari kegemaran.'
        ]);
    }

    /**
     * Share document with another masjid
     */
    public function share(Request $request, Document $document)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->hasPermission('documents', 'share')) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk berkongsi dokumen.');
        }

        // Check if user can access this document
        if (!$this->canAccessDocument($document, $user)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses dokumen ini.');
        }

        $request->validate([
            'shared_with_masjid_id' => 'required|exists:masjids,id',
            'permission_level' => 'required|in:view,comment,edit,full_access',
            'can_download' => 'boolean',
            'can_share_further' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            // Check if already shared with this masjid
            $existingShare = DocumentShare::where([
                'shareable_type' => Document::class,
                'shareable_id' => $document->id,
                'shared_by_masjid_id' => $user->masjid_id,
                'shared_with_masjid_id' => $request->shared_with_masjid_id,
            ])->first();

            if ($existingShare) {
                return back()->with('error', 'Dokumen sudah dikongsi dengan masjid ini.');
            }

            // Create share record
            DocumentShare::create([
                'shareable_type' => Document::class,
                'shareable_id' => $document->id,
                'shared_by_masjid_id' => $user->masjid_id,
                'shared_by_user_id' => $user->id,
                'shared_with_masjid_id' => $request->shared_with_masjid_id,
                'permission_level' => $request->permission_level,
                'can_download' => $request->boolean('can_download', true),
                'can_share_further' => $request->boolean('can_share_further', false),
                'expires_at' => $request->expires_at,
            ]);

            // Mark document as shared
            $document->update(['is_shared' => true]);

            return back()->with('success', 'Dokumen berjaya dikongsi.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ralat berlaku semasa berkongsi dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to check if user can access document
     */
    private function canAccessDocument(Document $document, $user): bool
    {
        // Super Admin can access all documents
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Owner masjid can always access
        if ($document->masjid_id === $user->masjid_id) {
            return true;
        }

        // Check if document is shared with user's masjid
        $share = DocumentShare::where([
            'shareable_type' => Document::class,
            'shareable_id' => $document->id,
            'shared_with_masjid_id' => $user->masjid_id,
            'status' => 'active',
        ])->where(function ($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
        })->first();

        return $share !== null;
    }

    /**
     * Get breadcrumb path for current folder
     */
    private function getBreadcrumbs($currentFolder): array
    {
        $breadcrumbs = [
            ['name' => 'Dokumen Saya', 'url' => route('documents.index')]
        ];

        if ($currentFolder) {
            $folders = [];
            $folder = $currentFolder;

            while ($folder) {
                array_unshift($folders, $folder);
                $folder = $folder->parentFolder;
            }

            foreach ($folders as $folder) {
                $breadcrumbs[] = [
                    'name' => $folder->name,
                    'url' => route('documents.index', ['folder' => $folder->id])
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Get shared items for current masjid
     */
    private function getSharedItems($user): array
    {
        if ($user->isSuperAdmin()) {
            return [
                'shared_with_me' => collect(),
                'shared_by_me' => collect(),
            ];
        }

        $sharedWithMe = DocumentShare::with(['shareable', 'sharedByMasjid'])
            ->where('shared_with_masjid_id', $user->masjid_id)
            ->active()
            ->notExpired()
            ->latest()
            ->take(10)
            ->get();

        $sharedByMe = DocumentShare::with(['shareable', 'sharedWithMasjid'])
            ->where('shared_by_masjid_id', $user->masjid_id)
            ->active()
            ->latest()
            ->take(10)
            ->get();

        return [
            'shared_with_me' => $sharedWithMe,
            'shared_by_me' => $sharedByMe,
        ];
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics($user): array
    {
        // Build stats query - DISABLE HasMasjidScope and apply manual filtering
        $statsQuery = Document::withoutGlobalScope('masjid');

        // WAJIB: Multi-Masjid Data Isolation for stats - Manual filtering
        if ($user->isSuperAdmin()) {
            // Super Admin can see all documents statistics
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see statistics from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $statsQuery->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no statistics
                $statsQuery->whereRaw('1 = 0'); // Always false condition
            }
        }

        return [
            'total_documents' => (clone $statsQuery)->count(),
            'total_size' => (clone $statsQuery)->sum('file_size'),
            'total_downloads' => (clone $statsQuery)->sum('download_count'),
            'recent_documents' => (clone $statsQuery)->recent()->count(),
            'starred_documents' => (clone $statsQuery)->starred()->count(),
            'shared_documents' => (clone $statsQuery)->shared()->count(),
        ];
    }
}
