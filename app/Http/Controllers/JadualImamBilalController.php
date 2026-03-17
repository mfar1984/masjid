<?php

namespace App\Http\Controllers;

use App\Models\JadualImamBilal;
use App\Models\Ajk;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class JadualImamBilalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        // Get masjid_id - Super Admin can filter by masjid
        $filterMasjidId = $request->get('masjid_id');
        $masjidId = $isSuperAdmin && $filterMasjidId ? $filterMasjidId : $user->masjid_id;

        // Get all masjids for Super Admin dropdown
        $masjidList = $isSuperAdmin ? Masjid::orderBy('nama')->get() : collect();

        // Get filter parameters
        $filterTahun = $request->get('tahun');
        $filterBulan = $request->get('bulan');
        $search = $request->get('search');

        // Group jadual by month/year - show summary per month
        $query = JadualImamBilal::where('masjid_id', $masjidId);

        // Apply year filter if provided
        if ($filterTahun) {
            $query->whereYear('tarikh', $filterTahun);
        }

        // Apply month filter if provided
        if ($filterBulan) {
            $query->whereMonth('tarikh', $filterBulan);
        }

        // Apply search filter - search by imam or bilal name
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('imamAjk', function($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('bilalAjk', function($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('nama_imam', 'like', "%{$search}%")
                ->orWhere('nama_bilal', 'like', "%{$search}%");
            });
        }

        $jadualGroups = $query->selectRaw('YEAR(tarikh) as tahun, MONTH(tarikh) as bulan, COUNT(*) as jumlah_jadual')
            ->selectRaw('SUM(CASE WHEN status_imam = "Dijadual" THEN 1 ELSE 0 END) as dijadual')
            ->selectRaw('SUM(CASE WHEN status_imam = "Selesai" THEN 1 ELSE 0 END) as selesai')
            ->selectRaw('SUM(CASE WHEN jenis_jadual = "Auto" THEN 1 ELSE 0 END) as auto_generate')
            ->selectRaw('MIN(tarikh) as tarikh_mula, MAX(tarikh) as tarikh_akhir')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Get AJK list for imam/bilal selection - use notArchived() scope
        $ajkList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        // Get available years for filter dropdown
        $availableYears = JadualImamBilal::where('masjid_id', $masjidId)
            ->selectRaw('DISTINCT YEAR(tarikh) as tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Overall Statistics
        $stats = [
            [
                'title' => 'Jumlah Bulan',
                'value' => JadualImamBilal::where('masjid_id', $masjidId)
                    ->selectRaw('DISTINCT YEAR(tarikh), MONTH(tarikh)')
                    ->get()
                    ->count(),
                'icon' => 'calendar_month',
                'color' => 'blue'
            ],
            [
                'title' => 'Jumlah Jadual',
                'value' => JadualImamBilal::where('masjid_id', $masjidId)->count(),
                'icon' => 'event_note',
                'color' => 'purple'
            ],
            [
                'title' => 'Dijadual',
                'value' => JadualImamBilal::where('masjid_id', $masjidId)
                    ->where('status_imam', 'Dijadual')
                    ->count(),
                'icon' => 'schedule',
                'color' => 'orange'
            ],
            [
                'title' => 'Auto-Generate',
                'value' => JadualImamBilal::where('masjid_id', $masjidId)
                    ->where('jenis_jadual', 'Auto')
                    ->count(),
                'icon' => 'autorenew',
                'color' => 'green'
            ],
        ];

        $masjid = $isSuperAdmin && $filterMasjidId ? Masjid::find($masjidId) : $user->masjid;

        return view('jadual-imam-bilal.index', compact(
            'jadualGroups', 
            'ajkList', 
            'stats', 
            'masjid', 
            'availableYears', 
            'filterTahun',
            'isSuperAdmin',
            'masjidList',
            'masjidId'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        // Get Imam list - filter by jawatan starting with "Imam"
        $imamList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->where('jawatan', 'like', 'Imam%')
            ->orderBy('nama')
            ->get();

        // Get Bilal list - filter by jawatan starting with "Bilal"
        $bilalList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->where('jawatan', 'like', 'Bilal%')
            ->orderBy('nama')
            ->get();

        return view('jadual-imam-bilal.create', compact('imamList', 'bilalList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tarikh' => 'required|date',
            'waktu_solat' => 'required|in:Subuh,Zohor,Asar,Maghrib,Isyak,Jumaat,Tarawih,Hari Raya',
            'imam_ajk_id' => 'nullable|exists:ajk,id',
            'nama_imam' => 'nullable|string|max:255',
            'bilal_ajk_id' => 'nullable|exists:ajk,id',
            'nama_bilal' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        // Check for duplicate (including soft deleted)
        $existing = JadualImamBilal::withTrashed()
            ->where('masjid_id', $user->masjid_id)
            ->where('tarikh', $validated['tarikh'])
            ->where('waktu_solat', $validated['waktu_solat'])
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                // Restore and update soft deleted record
                $existing->restore();
                $validated['masjid_id'] = $user->masjid_id;
                $validated['created_by'] = $user->id;
                $validated['jenis_jadual'] = 'Manual';
                $existing->update($validated);
                
                return redirect()->route('jadual-imam-bilal.index')
                    ->with('success', 'Jadual berjaya ditambah.');
            }
            return back()->withInput()->with('error', 'Jadual untuk tarikh dan waktu solat ini sudah wujud.');
        }

        $validated['masjid_id'] = $user->masjid_id;
        $validated['created_by'] = $user->id;
        $validated['jenis_jadual'] = 'Manual';

        JadualImamBilal::create($validated);

        return redirect()->route('jadual-imam-bilal.index')
            ->with('success', 'Jadual berjaya ditambah.');
    }

    public function show(JadualImamBilal $jadualImamBilal)
    {
        return view('jadual-imam-bilal.show', compact('jadualImamBilal'));
    }

    /**
     * Show calendar view for a specific month
     */
    public function showMonth(Request $request, $bulan, $tahun)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        $jadualList = JadualImamBilal::with(['imamAjk', 'bilalAjk'])
            ->where('masjid_id', $masjidId)
            ->whereMonth('tarikh', $bulan)
            ->whereYear('tarikh', $tahun)
            ->orderBy('tarikh')
            ->orderByRaw("FIELD(waktu_solat, 'Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat', 'Tarawih', 'Hari Raya')")
            ->get();

        // Get AJK list for legend
        $ajkList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        // Transform to calendar format
        $calendarData = $this->transformToCalendarData($jadualList, $bulan, $tahun, $ajkList);

        $masjid = $user->masjid;
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');

        // Statistics for this month
        $stats = [
            [
                'title' => 'Jumlah Jadual',
                'value' => $jadualList->count(),
                'icon' => 'event_note',
                'color' => 'blue'
            ],
            [
                'title' => 'Dijadual',
                'value' => $jadualList->where('status_imam', 'Dijadual')->count(),
                'icon' => 'schedule',
                'color' => 'orange'
            ],
            [
                'title' => 'Selesai',
                'value' => $jadualList->where('status_imam', 'Selesai')->count(),
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Auto-Generate',
                'value' => $jadualList->where('jenis_jadual', 'Auto')->count(),
                'icon' => 'autorenew',
                'color' => 'purple'
            ],
        ];

        return view('jadual-imam-bilal.show-month', compact(
            'jadualList', 
            'masjid', 
            'namaBulan', 
            'bulan', 
            'tahun',
            'calendarData',
            'ajkList',
            'stats'
        ));
    }

    public function edit(JadualImamBilal $jadualImamBilal)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        // Get Imam list - filter by jawatan starting with "Imam"
        $imamList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->where('jawatan', 'like', 'Imam%')
            ->orderBy('nama')
            ->get();

        // Get Bilal list - filter by jawatan starting with "Bilal"
        $bilalList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->where('jawatan', 'like', 'Bilal%')
            ->orderBy('nama')
            ->get();

        return view('jadual-imam-bilal.edit', compact('jadualImamBilal', 'imamList', 'bilalList'));
    }

    public function update(Request $request, JadualImamBilal $jadualImamBilal)
    {
        $validated = $request->validate([
            'tarikh' => 'required|date',
            'waktu_solat' => 'required|in:Subuh,Zohor,Asar,Maghrib,Isyak,Jumaat,Tarawih,Hari Raya',
            'imam_ajk_id' => 'nullable|exists:ajk,id',
            'nama_imam' => 'nullable|string|max:255',
            'status_imam' => 'required|in:Dijadual,Selesai,Ganti,Batal',
            'imam_ganti' => 'nullable|string|max:255',
            'bilal_ajk_id' => 'nullable|exists:ajk,id',
            'nama_bilal' => 'nullable|string|max:255',
            'status_bilal' => 'required|in:Dijadual,Selesai,Ganti,Batal',
            'bilal_ganti' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        // If edited, mark as Manual
        $validated['jenis_jadual'] = 'Manual';

        $jadualImamBilal->update($validated);

        return redirect()->route('jadual-imam-bilal.index')
            ->with('success', 'Jadual berjaya dikemaskini.');
    }

    public function destroy(JadualImamBilal $jadualImamBilal)
    {
        $jadualImamBilal->delete();

        return redirect()->route('jadual-imam-bilal.index')
            ->with('success', 'Jadual berjaya dipadam.');
    }

    /**
     * Delete all jadual for a specific month (force delete to remove from database)
     */
    public function destroyMonth($bulan, $tahun)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        // Use forceDelete to permanently remove records (not soft delete)
        // This allows auto-generate to create new records without conflicts
        $deleted = JadualImamBilal::withTrashed()
            ->where('masjid_id', $masjidId)
            ->whereMonth('tarikh', $bulan)
            ->whereYear('tarikh', $tahun)
            ->forceDelete();

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');

        return redirect()->route('jadual-imam-bilal.index')
            ->with('success', "Semua jadual untuk {$namaBulan} berjaya dipadam ({$deleted} rekod).");
    }

    /**
     * Auto-generate jadual for a period
     */
    public function autoGenerate(Request $request)
    {
        $validated = $request->validate([
            'tempoh' => 'required|in:minggu,bulan',
            'bulan' => 'required_if:tempoh,bulan|nullable|integer|min:1|max:12',
            'tahun' => 'required_if:tempoh,bulan|nullable|integer|min:2020|max:2100',
            'tarikh_mula' => 'required_if:tempoh,minggu|nullable|date',
            'waktu_solat' => 'required|array|min:1',
            'waktu_solat.*' => 'in:Subuh,Zohor,Asar,Maghrib,Isyak,Jumaat',
            'corak_giliran' => 'required|in:harian,3_hari,mingguan,berpasangan',
            'imam_rotation' => 'required|array|min:1',
            'imam_rotation.*' => 'exists:ajk,id',
            'bilal_rotation' => 'required|array|min:1',
            'bilal_rotation.*' => 'exists:ajk,id',
        ]);

        $user = auth()->user();
        $masjidId = $user->masjid_id;
        $batchId = Str::uuid()->toString();

        // Determine start and end dates based on tempoh
        if ($validated['tempoh'] === 'bulan') {
            $startDate = Carbon::createFromDate($validated['tahun'], $validated['bulan'], 1);
            $endDate = $startDate->copy()->endOfMonth();
        } else {
            $startDate = Carbon::parse($validated['tarikh_mula']);
            $endDate = $startDate->copy()->addDays(6);
        }

        $imamList = $validated['imam_rotation'];
        $bilalList = $validated['bilal_rotation'];
        $waktuList = $validated['waktu_solat'];
        $corakGiliran = $validated['corak_giliran'];

        $imamIndex = 0;
        $bilalIndex = 0;
        $created = 0;
        $skipped = 0;
        $dayCounter = 0;
        $lastRotationDay = -1;

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            // Determine rotation based on corak_giliran
            $shouldRotate = false;
            
            switch ($corakGiliran) {
                case 'harian':
                    // Rotate every day
                    $shouldRotate = ($dayCounter > 0);
                    break;
                case '3_hari':
                    // Rotate every 3 days
                    $shouldRotate = ($dayCounter > 0 && $dayCounter % 3 === 0);
                    break;
                case 'mingguan':
                    // Rotate every week (every 7 days)
                    $shouldRotate = ($dayCounter > 0 && $dayCounter % 7 === 0);
                    break;
                case 'berpasangan':
                    // Paired rotation - imam and bilal stay together, rotate daily
                    $shouldRotate = ($dayCounter > 0);
                    break;
            }

            if ($shouldRotate && $lastRotationDay !== $dayCounter) {
                if ($corakGiliran === 'berpasangan') {
                    // Both rotate together
                    $imamIndex++;
                    $bilalIndex = $imamIndex; // Keep same index for paired
                } else {
                    $imamIndex++;
                    $bilalIndex++;
                }
                $lastRotationDay = $dayCounter;
            }

            foreach ($waktuList as $waktu) {
                // Skip Jumaat if not Friday
                if ($waktu === 'Jumaat' && $currentDate->dayOfWeek !== Carbon::FRIDAY) {
                    continue;
                }

                // Get current imam and bilal from rotation
                $currentImamIndex = $imamIndex % count($imamList);
                $currentBilalIndex = $corakGiliran === 'berpasangan' 
                    ? $imamIndex % count($bilalList) 
                    : $bilalIndex % count($bilalList);

                $imamAjkId = $imamList[$currentImamIndex];
                $bilalAjkId = $bilalList[$currentBilalIndex];

                // Check if already exists (including soft deleted)
                $existing = JadualImamBilal::withTrashed()
                    ->where('masjid_id', $masjidId)
                    ->where('tarikh', $currentDate->format('Y-m-d'))
                    ->where('waktu_solat', $waktu)
                    ->first();

                if ($existing) {
                    if ($existing->trashed()) {
                        // Restore and update soft deleted record
                        $existing->restore();
                        $existing->update([
                            'imam_ajk_id' => $imamAjkId,
                            'bilal_ajk_id' => $bilalAjkId,
                            'status_imam' => 'Dijadual',
                            'status_bilal' => 'Dijadual',
                            'jenis_jadual' => 'Auto',
                            'batch_id' => $batchId,
                            'created_by' => $user->id,
                        ]);
                        $created++;
                    } else {
                        // Active record exists, skip
                        $skipped++;
                    }
                    continue;
                }

                JadualImamBilal::create([
                    'masjid_id' => $masjidId,
                    'tarikh' => $currentDate->format('Y-m-d'),
                    'waktu_solat' => $waktu,
                    'imam_ajk_id' => $imamAjkId,
                    'bilal_ajk_id' => $bilalAjkId,
                    'status_imam' => 'Dijadual',
                    'status_bilal' => 'Dijadual',
                    'jenis_jadual' => 'Auto',
                    'batch_id' => $batchId,
                    'created_by' => $user->id,
                ]);

                $created++;
            }
            
            $currentDate->addDay();
            $dayCounter++;
        }

        // Redirect with month/year filter if bulanan
        $redirectParams = [];
        if ($validated['tempoh'] === 'bulan') {
            $redirectParams = ['bulan' => $validated['bulan'], 'tahun' => $validated['tahun']];
        }

        return redirect()->route('jadual-imam-bilal.index', $redirectParams)
            ->with('success', "Jadual berjaya dijana. {$created} jadual ditambah, {$skipped} dilangkau (sudah wujud).");
    }

    /**
     * Show auto-generate form
     */
    public function showAutoGenerateForm()
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        // Get Imam list - filter by jawatan starting with "Imam"
        $imamList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->where('jawatan', 'like', 'Imam%')
            ->orderBy('nama')
            ->get();

        // Get Bilal list - filter by jawatan starting with "Bilal"
        $bilalList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->where('jawatan', 'like', 'Bilal%')
            ->orderBy('nama')
            ->get();

        return view('jadual-imam-bilal.auto-generate', compact('imamList', 'bilalList'));
    }

    /**
     * Export jadual to printable view (can be printed as PDF)
     */
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $jadualList = JadualImamBilal::with(['imamAjk', 'bilalAjk'])
            ->where('masjid_id', $masjidId)
            ->whereMonth('tarikh', $bulan)
            ->whereYear('tarikh', $tahun)
            ->orderBy('tarikh')
            ->orderByRaw("FIELD(waktu_solat, 'Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat', 'Tarawih', 'Hari Raya')")
            ->get();

        // Get AJK list for legend (even if no schedule data)
        $ajkList = Ajk::where('masjid_id', $masjidId)
            ->notArchived()
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        // Transform to calendar format
        $calendarData = $this->transformToCalendarData($jadualList, $bulan, $tahun, $ajkList);

        $masjid = $user->masjid;
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');

        return view('jadual-imam-bilal.print', compact(
            'jadualList', 
            'masjid', 
            'namaBulan', 
            'bulan', 
            'tahun',
            'calendarData',
            'ajkList'
        ));
    }

    /**
     * Transform flat jadual list to calendar data structure
     */
    private function transformToCalendarData($jadualList, $bulan, $tahun, $ajkList = null)
    {
        $daysInMonth = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $waktuSolat = ['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak'];
        
        $schedules = [];
        $imamNames = [];
        $bilalNames = [];
        
        // Initialize empty structure for each day
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($tahun, $bulan, $day);
            $schedules[$day] = [
                'date' => $date,
                'dayName' => $date->translatedFormat('l'),
                'isFriday' => $date->isFriday(),
                'waktu' => []
            ];
            foreach ($waktuSolat as $waktu) {
                $schedules[$day]['waktu'][$waktu] = null;
            }
        }
        
        // Populate with actual data from jadual
        foreach ($jadualList as $jadual) {
            $day = $jadual->tarikh->day;
            $waktu = $jadual->waktu_solat;
            
            // Skip Jumaat for now (handled separately)
            if ($waktu === 'Jumaat') continue;
            
            // Only process if waktu exists in our list
            if (in_array($waktu, $waktuSolat)) {
                // Use full display name with prefix (Imam/Bilal + Nama)
                $imamName = $jadual->imam_display;
                $bilalName = $jadual->bilal_display;
                
                // Use short name for legend (without prefix)
                $imamShortName = $jadual->imam_short_name;
                $bilalShortName = $jadual->bilal_short_name;
                
                $schedules[$day]['waktu'][$waktu] = [
                    'id' => $jadual->id,
                    'imam' => $imamName,
                    'bilal' => $bilalName,
                    'imam_short' => $imamShortName,
                    'bilal_short' => $bilalShortName,
                    'status_imam' => $jadual->status_imam ?? 'Dijadual',
                    'status_bilal' => $jadual->status_bilal ?? 'Dijadual',
                    'imam_ganti' => $jadual->imam_ganti,
                    'bilal_ganti' => $jadual->bilal_ganti,
                ];
                
                // Collect unique short names for legend (only from actual jadual data)
                if ($imamShortName && $imamShortName !== '-') {
                    $imamNames[$imamShortName] = true;
                }
                if ($bilalShortName && $bilalShortName !== '-') {
                    $bilalNames[$bilalShortName] = true;
                }
            }
        }
        
        // Assign colors to names (only those actually assigned)
        $imamColors = $this->assignColors(array_keys($imamNames));
        $bilalColors = $this->assignColors(array_keys($bilalNames));
        
        return [
            'daysInMonth' => $daysInMonth,
            'waktuSolat' => $waktuSolat,
            'schedules' => $schedules,
            'legend' => [
                'imam' => $imamColors,
                'bilal' => $bilalColors,
            ]
        ];
    }

    /**
     * Assign colors to names for legend
     */
    private function assignColors($names)
    {
        $colorPalette = [
            '#3B82F6', // blue
            '#10B981', // green
            '#F59E0B', // amber
            '#8B5CF6', // purple
            '#EF4444', // red
            '#06B6D4', // cyan
            '#EC4899', // pink
            '#84CC16', // lime
            '#F97316', // orange
            '#6366F1', // indigo
        ];
        
        $colors = [];
        foreach ($names as $index => $name) {
            $colors[$name] = $colorPalette[$index % count($colorPalette)];
        }
        return $colors;
    }
}
