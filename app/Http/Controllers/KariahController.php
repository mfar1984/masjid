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
        $query = Kariah::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ic', 'like', "%{$search}%")
                  ->orWhere('telefon', 'like', "%{$search}%");
            });
        }

        // Filter by zone
        if ($request->filled('zon') && $request->zon !== 'Semua Zon') {
            $query->where('zon', $request->zon);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('status', $request->status);
        }

        // Get paginated results
        $kariah = $query->orderBy('nama')->paginate(10);

        // Get zones for filter dropdown
        $zones = Kariah::distinct()->pluck('zon')->filter()->values();

        // Get current user
        $user = auth()->user();

        return view('kariah.index', compact('kariah', 'zones', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Kariah::distinct()->pluck('zon')->filter()->values();
        $user = auth()->user();
        return view('kariah.create', compact('zones', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => 'required|string|size:14|unique:kariah,no_ic',
            'telefon' => 'required|string|max:15',
            'bangsa' => 'required|string|max:100',
            'tarikh_keahlian' => 'required|date',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'zon' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

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
        return view('kariah.show', compact('kariah', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kariah $kariah)
    {
        $zones = Kariah::distinct()->pluck('zon')->filter()->values();
        $user = auth()->user();
        return view('kariah.edit', compact('kariah', 'zones', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kariah $kariah)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_ic' => ['required', 'string', 'size:14', Rule::unique('kariah')->ignore($kariah->id)],
            'telefon' => 'required|string|max:15',
            'bangsa' => 'required|string|max:100',
            'tarikh_keahlian' => 'required|date',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'zon' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['updated_by'] = Auth::id();

        $kariah->update($validated);

        return redirect()->route('kariah.index')
            ->with('success', 'Ahli Kariah berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kariah $kariah)
    {
        $kariah->delete();

        return redirect()->route('kariah.index')
            ->with('success', 'Ahli Kariah berjaya dipadam.');
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
