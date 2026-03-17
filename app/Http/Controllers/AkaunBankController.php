<?php

namespace App\Http\Controllers;

use App\Models\AkaunBank;
use App\Models\TetapanKewangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkaunBankController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $masjidId = $isSuperAdmin && $request->filled('masjid_id') ? $request->masjid_id : $user->masjid_id;

        // Get items per page from settings
        $perPage = TetapanKewangan::get('records_per_page', 10, $masjidId);

        $query = AkaunBank::with('masjid');

        // Multi-masjid isolation
        if ($isSuperAdmin) {
            if ($request->filled('masjid_id')) {
                $query->where('masjid_id', $request->masjid_id);
            }
        } else {
            $query->where('masjid_id', $user->masjid_id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_bank', 'like', "%{$search}%")
                    ->orWhere('no_akaun', 'like', "%{$search}%")
                    ->orWhere('nama_pemegang_akaun', 'like', "%{$search}%");
            });
        }

        $akaunBank = $query->latest()->paginate($perPage);

        // Stats
        $baseQuery = AkaunBank::query();
        if (!$isSuperAdmin) {
            $baseQuery->where('masjid_id', $user->masjid_id);
        } elseif ($request->filled('masjid_id')) {
            $baseQuery->where('masjid_id', $request->masjid_id);
        }

        $stats = [
            ['title' => 'Jumlah Akaun', 'value' => (clone $baseQuery)->count(), 'icon' => 'account_balance', 'color' => 'blue'],
            ['title' => 'Akaun Aktif', 'value' => (clone $baseQuery)->aktif()->count(), 'icon' => 'check_circle', 'color' => 'green'],
            ['title' => 'Tidak Aktif', 'value' => (clone $baseQuery)->tidakAktif()->count(), 'icon' => 'cancel', 'color' => 'orange'],
            ['title' => 'Jumlah Baki', 'value' => 'RM ' . number_format((clone $baseQuery)->aktif()->sum('baki_semasa'), 2), 'icon' => 'account_balance_wallet', 'color' => 'green'],
        ];

        return view('akaun-bank.index', compact('akaunBank', 'stats', 'isSuperAdmin'));
    }

    public function create()
    {
        $user = Auth::user();
        $masjidId = $user->masjid_id;

        // Get kategori from tetapan
        $namaBank = \App\Models\KategoriKewangan::where('masjid_id', $masjidId)
            ->namaBank()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $jenisAkaun = \App\Models\KategoriKewangan::where('masjid_id', $masjidId)
            ->jenisAkaun()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('akaun-bank.create', compact('namaBank', 'jenisAkaun'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->hasRole('Super Admin') && $request->filled('masjid_id')
            ? $request->masjid_id
            : $user->masjid_id;

        $validated = $request->validate([
            'nama_bank' => 'required|max:255',
            'no_akaun' => 'required|max:50',
            'jenis_akaun' => 'required|max:255',
            'nama_pemegang_akaun' => 'required|max:255',
            'cawangan' => 'nullable|max:255',
            'baki_awal' => 'required|numeric|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'catatan' => 'nullable|string',
        ]);

        $validated['masjid_id'] = $masjidId;
        $validated['baki_semasa'] = $validated['baki_awal'];
        $validated['created_by'] = $user->id;

        AkaunBank::create($validated);

        return redirect()->route('akaun-bank.index')
            ->with('success', 'Akaun bank berjaya ditambah.');
    }

    public function show(AkaunBank $akaunBank)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $akaunBank->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $akaunBank->load('masjid', 'createdBy', 'updatedBy');

        return view('akaun-bank.show', compact('akaunBank'));
    }

    public function edit(AkaunBank $akaunBank)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $akaunBank->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $masjidId = $akaunBank->masjid_id;

        // Get kategori from tetapan
        $namaBank = \App\Models\KategoriKewangan::where('masjid_id', $masjidId)
            ->namaBank()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        $jenisAkaun = \App\Models\KategoriKewangan::where('masjid_id', $masjidId)
            ->jenisAkaun()
            ->aktif()
            ->orderBy('urutan')
            ->get();

        return view('akaun-bank.edit', compact('akaunBank', 'namaBank', 'jenisAkaun'));
    }

    public function update(Request $request, AkaunBank $akaunBank)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $akaunBank->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama_bank' => 'required|max:255',
            'no_akaun' => 'required|max:50',
            'jenis_akaun' => 'required|max:255',
            'nama_pemegang_akaun' => 'required|max:255',
            'cawangan' => 'nullable|max:255',
            'baki_awal' => 'required|numeric|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = $user->id;

        $akaunBank->update($validated);

        return redirect()->route('akaun-bank.index')
            ->with('success', 'Akaun bank berjaya dikemaskini.');
    }

    public function destroy(AkaunBank $akaunBank)
    {
        $user = Auth::user();

        // Check ownership
        if (!$user->hasRole('Super Admin') && $akaunBank->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized action.');
        }

        $akaunBank->update(['deleted_by' => $user->id]);
        $akaunBank->delete();

        return redirect()->route('akaun-bank.index')
            ->with('success', 'Akaun bank berjaya dipadam.');
    }
}
