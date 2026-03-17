<?php

namespace App\Http\Controllers;

use App\Models\UrusanJenazah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UrusanJenazahController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = UrusanJenazah::with(['masjid']);

        if (!$user->isSuperAdmin()) {
            $query->where('masjid_id', $user->masjid_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_simati', 'like', "%{$search}%")
                    ->orWhere('no_rujukan', 'like', "%{$search}%")
                    ->orWhere('nama_waris', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jenazahList = $query->latest()->paginate(25);

        $baseQuery = UrusanJenazah::when(!$user->isSuperAdmin(), fn($q) => $q->where('masjid_id', $user->masjid_id));
        $stats = [
            ['title' => 'Jumlah Rekod', 'value' => (clone $baseQuery)->count(), 'icon' => 'assignment', 'color' => 'blue'],
            ['title' => 'Dalam Proses', 'value' => (clone $baseQuery)->where('status', 'Dalam Proses')->count(), 'icon' => 'pending', 'color' => 'orange'],
            ['title' => 'Selesai', 'value' => (clone $baseQuery)->where('status', 'Selesai')->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Bulan Ini', 'value' => (clone $baseQuery)->whereMonth('tarikh_meninggal', now()->month)->count(), 'icon' => 'calendar_month', 'color' => 'purple'],
        ];

        return view('urusan-jenazah.index', compact('jenazahList', 'stats'));
    }

    public function create()
    {
        return view('urusan-jenazah.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_simati' => 'required|string|max:255',
            'no_ic_simati' => 'nullable|string|max:20',
            'jantina' => 'required|in:Lelaki,Perempuan',
            'umur' => 'nullable|integer|min:0',
            'alamat_simati' => 'nullable|string',
            'tarikh_meninggal' => 'required|date',
            'masa_meninggal' => 'nullable',
            'tempat_meninggal' => 'nullable|string|max:255',
            'sebab_kematian' => 'nullable|string|max:255',
            'nama_waris' => 'required|string|max:255',
            'no_telefon_waris' => 'required|string|max:20',
            'hubungan_waris' => 'nullable|string|max:100',
            'tarikh_mandi_kafan' => 'nullable|date',
            'tarikh_solat_jenazah' => 'nullable|date',
            'imam_solat' => 'nullable|string|max:255',
            'tarikh_kebumi' => 'nullable|date',
            'lokasi_kubur' => 'nullable|string|max:255',
            'no_kubur' => 'nullable|string|max:100',
            'kos_pengurusan' => 'nullable|numeric|min:0',
            'status_bayaran' => 'required|in:Belum Bayar,Sudah Bayar,Percuma',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;
        $validated['no_rujukan'] = UrusanJenazah::generateNoRujukan($user->masjid_id);
        $validated['status'] = 'Dalam Proses';

        UrusanJenazah::create($validated);

        return redirect()->route('urusan-jenazah.index')
            ->with('success', 'Rekod jenazah berjaya ditambah.');
    }

    public function show(UrusanJenazah $urusanJenazah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $urusanJenazah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $urusanJenazah->load(['masjid']);

        return view('urusan-jenazah.show', compact('urusanJenazah'));
    }

    public function edit(UrusanJenazah $urusanJenazah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $urusanJenazah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        return view('urusan-jenazah.edit', compact('urusanJenazah'));
    }

    public function update(Request $request, UrusanJenazah $urusanJenazah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $urusanJenazah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_simati' => 'required|string|max:255',
            'no_ic_simati' => 'nullable|string|max:20',
            'jantina' => 'required|in:Lelaki,Perempuan',
            'umur' => 'nullable|integer|min:0',
            'alamat_simati' => 'nullable|string',
            'tarikh_meninggal' => 'required|date',
            'masa_meninggal' => 'nullable',
            'tempat_meninggal' => 'nullable|string|max:255',
            'sebab_kematian' => 'nullable|string|max:255',
            'nama_waris' => 'required|string|max:255',
            'no_telefon_waris' => 'required|string|max:20',
            'hubungan_waris' => 'nullable|string|max:100',
            'tarikh_mandi_kafan' => 'nullable|date',
            'tarikh_solat_jenazah' => 'nullable|date',
            'imam_solat' => 'nullable|string|max:255',
            'tarikh_kebumi' => 'nullable|date',
            'lokasi_kubur' => 'nullable|string|max:255',
            'no_kubur' => 'nullable|string|max:100',
            'kos_pengurusan' => 'nullable|numeric|min:0',
            'status_bayaran' => 'required|in:Belum Bayar,Sudah Bayar,Percuma',
            'status' => 'required|in:Dalam Proses,Selesai',
            'catatan' => 'nullable|string',
        ]);

        $urusanJenazah->update($validated);

        return redirect()->route('urusan-jenazah.index')
            ->with('success', 'Rekod jenazah berjaya dikemaskini.');
    }

    public function destroy(UrusanJenazah $urusanJenazah)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $urusanJenazah->masjid_id !== $user->masjid_id) {
            abort(403);
        }

        $urusanJenazah->delete();

        return redirect()->route('urusan-jenazah.index')
            ->with('success', 'Rekod jenazah berjaya dipadam.');
    }
}
