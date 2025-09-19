<?php

namespace App\Http\Controllers;

use App\Models\Kariah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KariahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships
        $baseQuery = Kariah::query();

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all kariah
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see kariah from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no kariah
                $baseQuery->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $baseQuery->where('status', $request->status);
        }

        // Get paginated results
        $kariah = $baseQuery->orderBy('nama')->paginate(10);

        // Build stats array - SEPARATE query for statistics (not affected by search/filter)
        // Statistics should show TOTAL counts, not filtered counts
        $statsQuery = Kariah::query();

        // Apply masjid isolation for stats (but NOT search/filter)
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('masjid_id', $user->masjid_id);
        }

        $totalKariah = (clone $statsQuery)->count();
        $activeKariah = (clone $statsQuery)->where('status', 'Aktif')->count();
        $pendingKariah = (clone $statsQuery)->where('status', 'Menunggu')->count();
        $rejectedKariah = (clone $statsQuery)->where('status', 'Ditolak')->count();
        $inactiveKariah = (clone $statsQuery)->where('status', 'Tidak Aktif')->count();
        $suspendedKariah = (clone $statsQuery)->where('status', 'Digantung')->count();

        // Always show all 6 cards (consistent design even with 0 data)
        $stats = [
            [
                'title' => 'Jumlah Kariah',
                'value' => $totalKariah,
                'icon' => 'people',
                'color' => 'blue'
            ],
            [
                'title' => 'Aktif',
                'value' => $activeKariah,
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Menunggu',
                'value' => $pendingKariah,
                'icon' => 'pending',
                'color' => 'orange'
            ],
            [
                'title' => 'Ditolak',
                'value' => $rejectedKariah,
                'icon' => 'close',
                'color' => 'red'
            ],
            [
                'title' => 'Tidak Aktif',
                'value' => $inactiveKariah,
                'icon' => 'cancel',
                'color' => 'gray'
            ],
            [
                'title' => 'Digantung',
                'value' => $suspendedKariah,
                'icon' => 'pause_circle',
                'color' => 'purple'
            ]
        ];

        return view('kariah.index', compact('kariah', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kariah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => 'required|string|size:14|unique:kariah,no_ic',
            'telefon' => 'required|string|max:15',
            'bangsa' => 'required|string|max:100',
            'jantina' => 'required|in:Lelaki,Perempuan,Tidak Dinyatakan',
            'tarikh_keahlian' => 'required|date',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'ic_depan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_belakang' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // WAJIB: Auto-assign masjid_id for data isolation
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            // Super Admin can specify masjid_id or leave null
            $validated['masjid_id'] = $request->masjid_id;
        }

        // Handle file uploads
        if ($request->hasFile('ic_depan')) {
            $validated['ic_depan_path'] = $request->file('ic_depan')->store('kariah/ic', 'public');
        }

        if ($request->hasFile('ic_belakang')) {
            $validated['ic_belakang_path'] = $request->file('ic_belakang')->store('kariah/ic', 'public');
        }

        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        Kariah::create($validated);

        return redirect()->route('kariah.index')
            ->with('success', 'Ahli Kariah berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        return view('kariah.show', compact('kariah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        return view('kariah.edit', compact('kariah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Validation
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => ['required', 'string', 'size:14', Rule::unique('kariah')->ignore($kariah->id)],
            'telefon' => 'required|string|max:15',
            'bangsa' => 'required|string|max:100',
            'jantina' => 'required|in:Lelaki,Perempuan,Tidak Dinyatakan',
            'tarikh_keahlian' => 'required|date',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'ic_depan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ic_belakang' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('ic_depan')) {
            $validated['ic_depan_path'] = $request->file('ic_depan')->store('kariah/ic', 'public');
        }

        if ($request->hasFile('ic_belakang')) {
            $validated['ic_belakang_path'] = $request->file('ic_belakang')->store('kariah/ic', 'public');
        }

        $validated['updated_by'] = $user->id;

        $kariah->update($validated);

        return redirect()->route('kariah.index')
            ->with('success', 'Ahli Kariah berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $kariah->delete();

        return redirect()->route('kariah.index')
            ->with('success', 'Ahli Kariah berjaya dipadam.');
    }

    /**
     * Approve a kariah member
     */
    public function approve(Request $request, Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $request->validate([
            'catatan_kelulusan' => 'nullable|string',
        ]);

        $kariah->update([
            'status' => 'Aktif',
            'diluluskan_oleh' => $user->id,
            'tarikh_diluluskan' => now(),
            'catatan_kelulusan' => $request->catatan_kelulusan,
            'updated_by' => $user->id,
        ]);

        return redirect()->back()
            ->with('success', 'Ahli Kariah ' . $kariah->nama . ' berjaya diluluskan.');
    }

    /**
     * Reject a kariah member
     */
    public function reject(Request $request, Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $reason = $request->input('reason', 'Tiada sebab dinyatakan');

        $kariah->update([
            'status' => 'Ditolak',
            'catatan_kelulusan' => 'Ditolak oleh ' . $user->name . '. Sebab: ' . $reason,
            'updated_by' => $user->id,
        ]);

        return redirect()->back()
            ->with('success', 'Permohonan ahli kariah ' . $kariah->nama . ' telah ditolak.');
    }

    /**
     * Suspend a kariah member
     */
    public function suspend(Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if ($kariah->status === 'Digantung') {
            return redirect()->route('kariah.index')
                ->with('error', 'Ahli Kariah sudah digantung.');
        }

        $kariah->update([
            'status' => 'Digantung',
            'suspended_at' => now(),
            'suspended_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('kariah.index')
            ->with('success', 'Ahli Kariah ' . $kariah->nama . ' berjaya digantung.');
    }

    /**
     * Reactivate (unsuspend) a kariah member
     */
    public function reactivate(Kariah $kariah)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($kariah->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        if ($kariah->status !== 'Digantung') {
            return redirect()->route('kariah.index')
                ->with('error', 'Ahli Kariah tidak dalam status digantung.');
        }

        $kariah->update([
            'status' => 'Aktif',
            'suspended_at' => null,
            'suspended_by' => null,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('kariah.index')
            ->with('success', 'Ahli Kariah ' . $kariah->nama . ' berjaya diaktifkan semula.');
    }

    /**
     * Export kariah data
     */
    public function export(Request $request)
    {
        $query = Kariah::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('zon') && $request->zon !== 'Semua Zon') {
            $query->where('zon', $request->zon);
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('status', $request->status);
        }

        $kariah = $query->orderBy('nama')->get();

        // Generate CSV
        $filename = 'kariah_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($kariah) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Nama',
                'No. IC',
                'Telefon',
                'Tarikh Keahlian',
                'Status',
                'Zon',
                'Alamat',
                'Email',
                'Tarikh Kemaskini'
            ]);

            // Add data
            foreach ($kariah as $row) {
                fputcsv($file, [
                    $row->nama,
                    $row->no_ic,
                    $row->telefon,
                    $row->tarikh_keahlian_formatted,
                    $row->status,
                    $row->zon,
                    $row->alamat,
                    $row->email,
                    $row->tarikh_kemaskini_formatted
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
