<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use App\Models\MasjidAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MasjidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Masjid::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by negeri
        if ($request->filled('negeri')) {
            $query->byNegeri($request->negeri);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $masjids = $query->paginate(10)->withQueryString();

        // Get filter options
        $negeriList = Masjid::distinct()->pluck('negeri')->filter()->sort()->values();
        $statusList = ['active', 'pending', 'rejected', 'inactive', 'suspended'];
        $kategoriList = ['masjid', 'surau', 'musolla'];

        // Statistics - Dynamic format matching other controllers
        $totalMasjids = Masjid::count();
        $activeMasjids = Masjid::where('status', 'active')->count();
        $pendingMasjids = Masjid::where('status', 'pending')->count();
        $rejectedMasjids = Masjid::where('status', 'rejected')->count();
        $inactiveMasjids = Masjid::where('status', 'inactive')->count();
        $suspendedMasjids = Masjid::where('status', 'suspended')->count();

        $stats = [];

        // Always show total
        $stats[] = [
            'title' => 'Jumlah Masjid',
            'value' => $totalMasjids,
            'icon' => 'mosque',
            'color' => 'blue'
        ];

        // Show active if there are any
        if ($activeMasjids > 0) {
            $stats[] = [
                'title' => 'Aktif',
                'value' => $activeMasjids,
                'icon' => 'check_circle',
                'color' => 'green'
            ];
        }

        // Show pending if there are any
        if ($pendingMasjids > 0) {
            $stats[] = [
                'title' => 'Menunggu',
                'value' => $pendingMasjids,
                'icon' => 'pending',
                'color' => 'orange'
            ];
        }

        // Show rejected if there are any
        if ($rejectedMasjids > 0) {
            $stats[] = [
                'title' => 'Ditolak',
                'value' => $rejectedMasjids,
                'icon' => 'close',
                'color' => 'red'
            ];
        }

        // Show inactive if there are any
        if ($inactiveMasjids > 0) {
            $stats[] = [
                'title' => 'Tidak Aktif',
                'value' => $inactiveMasjids,
                'icon' => 'cancel',
                'color' => 'gray'
            ];
        }

        // Show suspended if there are any
        if ($suspendedMasjids > 0) {
            $stats[] = [
                'title' => 'Digantung',
                'value' => $suspendedMasjids,
                'icon' => 'pause_circle',
                'color' => 'purple'
            ];
        }

        return view('pentadbiran.senarai-masjid', compact(
            'masjids',
            'negeriList',
            'statusList',
            'kategoriList',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pentadbiran.masjid.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombor_daftar' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'nama_penuh' => 'nullable|string',
            'kategori' => 'required|in:masjid,surau,musolla',
            'alamat' => 'required|string',
            'poskod' => 'nullable|string|max:10',
            'bandar' => 'nullable|string|max:100',
            'negeri' => 'required|string|max:50',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telefon' => 'nullable|string|max:20',
            'faks' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:masjids,email',
            'laman_web' => 'nullable|url',
            'tarikh_ditubuhkan' => 'nullable|date',
            'kapasiti_jemaah' => 'nullable|integer|min:0',
            'pendaftar_nama' => 'nullable|string|max:255',
            'pendaftar_jawatan' => 'nullable|string|max:100',
            'pendaftar_telefon' => 'nullable|string|max:20',
            'pendaftar_email' => 'nullable|email',
            'attachments' => 'nullable|array|max:5', // Maximum 5 files
            'attachments.*' => 'file|mimes:pdf,png,jpeg,jpg|max:5120', // 5MB max per file
            'pendaftar_telefon' => 'nullable|string|max:20',
            'pendaftar_email' => 'nullable|email',
            'pendaftar_jawatan' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Remove attachments from validated data for masjid creation
            $attachments = $validated['attachments'] ?? [];
            unset($validated['attachments']);

            // Create masjid
            $masjid = Masjid::create($validated);
            $masjid->generateKodMasjid();

            // Handle multiple file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '_' . $originalName;
                    $path = $file->storeAs('masjid-attachments', $filename, 'public');

                    MasjidAttachment::create([
                        'masjid_id' => $masjid->id,
                        'original_name' => $originalName,
                        'file_path' => $path,
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        });

        return redirect()->route('senarai-masjid.index')
            ->with('success', 'Masjid berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Masjid $masjid)
    {
        $currentUser = auth()->user();

        // Multi-Masjid Data Isolation - STRICT MODE
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only view their own masjid
            if ($masjid->id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk melihat masjid ini.');
            }
        }

        // Load relationships
        $masjid->load('attachments');

        return view('pentadbiran.senarai-masjid.show', compact('masjid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Masjid $masjid)
    {
        $currentUser = auth()->user();

        // Multi-Masjid Data Isolation - STRICT MODE
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only edit their own masjid
            if ($masjid->id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengedit masjid ini.');
            }
        }

        return view('pentadbiran.masjid.edit', compact('masjid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Masjid $masjid)
    {
        $currentUser = auth()->user();

        // Multi-Masjid Data Isolation - STRICT MODE
        if (!$currentUser->isSuperAdmin()) {
            // Admin Masjid can only update their own masjid
            if ($masjid->id !== $currentUser->masjid_id) {
                abort(403, 'Anda tidak mempunyai kebenaran untuk mengemaskini masjid ini.');
            }
        }

        $validated = $request->validate([
            'nombor_daftar' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'nama_penuh' => 'nullable|string',
            'alamat' => 'required|string',
            'poskod' => 'nullable|string|max:10',
            'bandar' => 'nullable|string|max:100',
            'negeri' => 'required|string|max:50',
            'telefon' => 'nullable|string|max:20',
            'faks' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:masjids,email,' . $masjid->id,
            'laman_web' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'kategori' => 'required|in:masjid,surau,musolla',
            'tarikh_ditubuhkan' => 'nullable|date',
            'kapasiti_jemaah' => 'nullable|integer|min:0',
            'pendaftar_nama' => 'nullable|string|max:255',
            'pendaftar_telefon' => 'nullable|string|max:20',
            'pendaftar_email' => 'nullable|email',
            'pendaftar_jawatan' => 'nullable|string|max:100',
            'attachments' => 'nullable|array|max:5', // Maximum 5 files
            'attachments.*' => 'file|mimes:pdf,png,jpeg,jpg|max:5120', // 5MB max per file
        ]);

        DB::transaction(function () use ($validated, $request, $masjid) {
            // Remove attachments from validated data for masjid update
            $attachments = $validated['attachments'] ?? [];
            unset($validated['attachments']);

            // Update masjid
            $masjid->update($validated);

            // Handle multiple file uploads for new attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '_' . $originalName;
                    $path = $file->storeAs('masjid-attachments', $filename, 'public');

                    MasjidAttachment::create([
                        'masjid_id' => $masjid->id,
                        'original_name' => $originalName,
                        'file_path' => $path,
                        'file_type' => strtolower($file->getClientOriginalExtension()),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        });

        return redirect()->route('senarai-masjid.index')
            ->with('success', 'Masjid berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Masjid $masjid)
    {
        $currentUser = auth()->user();

        // Multi-Masjid Data Isolation - STRICT MODE
        // Only Super Admin can delete masjids
        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk memadamkan masjid ini.');
        }

        $masjid->delete();

        return redirect()->route('senarai-masjid.index')
            ->with('success', 'Masjid berjaya dipadam.');
    }

    /**
     * Delete attachment from masjid
     */
    public function deleteAttachment(MasjidAttachment $attachment)
    {
        // Delete file from storage
        if (Storage::exists($attachment->file_path)) {
            Storage::delete($attachment->file_path);
        }

        // Delete record from database
        $attachment->delete();

        return redirect()->back()
            ->with('success', 'Lampiran berjaya dipadam.');
    }

    /**
     * Suspend the specified masjid.
     */
    public function suspend(Masjid $masjid)
    {
        if ($masjid->status === 'suspended') {
            return redirect()->route('senarai-masjid.index')
                ->with('error', 'Masjid sudah digantung.');
        }

        $masjid->update([
            'status' => 'suspended',
            'suspended_at' => now(),
            'suspended_by' => auth()->id(),
        ]);

        return redirect()->route('senarai-masjid.index')
            ->with('success', 'Masjid ' . $masjid->nama . ' berjaya digantung.');
    }

    /**
     * Unsuspend the specified masjid.
     */
    public function unsuspend(Masjid $masjid)
    {
        if ($masjid->status !== 'suspended') {
            return redirect()->route('senarai-masjid.index')
                ->with('error', 'Masjid tidak dalam status digantung.');
        }

        $masjid->update([
            'status' => 'active',
            'suspended_at' => null,
            'suspended_by' => null,
        ]);

        return redirect()->route('senarai-masjid.index')
            ->with('success', 'Masjid ' . $masjid->nama . ' berjaya diaktifkan semula.');
    }

    /**
     * Approve a masjid
     */
    public function approve(Request $request, Masjid $masjid)
    {
        $request->validate([
            'catatan_kelulusan' => 'nullable|string',
        ]);

        $masjid->approve(auth()->id(), $request->catatan_kelulusan);

        return redirect()->back()
            ->with('success', 'Masjid berjaya diluluskan.');
    }

    /**
     * Reject a pending masjid
     */
    public function reject(Request $request, Masjid $masjid)
    {
        if ($masjid->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya masjid dengan status pending boleh ditolak.');
        }

        $reason = $request->input('reason', 'Tiada sebab dinyatakan');

        $masjid->update([
            'status' => 'rejected',
            'catatan_kelulusan' => 'Ditolak oleh ' . auth()->user()->name . '. Sebab: ' . $reason
        ]);

        return redirect()->back()->with('success', "Permohonan masjid {$masjid->nama} telah ditolak.");
    }

    /**
     * Export masjids data
     */
    public function export(Request $request)
    {
        // Implementation for export functionality
        // Can use Laravel Excel or similar package

        return response()->json(['message' => 'Export functionality coming soon']);
    }
}
