<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tetapan;
use Illuminate\Support\Facades\Auth;

class TetapanController extends Controller
{
    /**
     * Get available azan audio files from public/audio/azan directory
     */
    private function getAvailableAzanFiles()
    {
        $azanPath = public_path('audio/azan');
        $files = [];
        
        if (is_dir($azanPath)) {
            $audioFiles = glob($azanPath . '/*.mp3');
            
            foreach ($audioFiles as $file) {
                $filename = basename($file, '.mp3');
                
                // Generate display name from filename
                $displayName = $this->generateAzanDisplayName($filename);
                
                $files[] = [
                    'value' => $filename,
                    'name' => $displayName,
                    'is_fajr' => str_contains($filename, '-fajr') || str_contains($filename, 'fajr'),
                    'file_size' => filesize($file)
                ];
            }
        }
        
        return collect($files);
    }
    
    /**
     * Get current system version from release notes
     */
    private function getCurrentSystemVersion()
    {
        try {
            // Try to get version from nota-keluaran view
            $viewPath = resource_path('views/bantuan/nota-keluaran.blade.php');
            
            if (file_exists($viewPath)) {
                $content = file_get_contents($viewPath);
                
                // Look for version patterns like v1.2.3 or Version 1.2.3
                if (preg_match('/(?:v|version|versi)\s*(\d+\.\d+(?:\.\d+)?)/i', $content, $matches)) {
                    return $matches[1];
                }
                
                // Look for date-based versions like 2024.09.18
                if (preg_match('/(\d{4}\.\d{2}\.\d{2})/', $content, $matches)) {
                    return $matches[1];
                }
            }
            
            // Fallback: Generate version from current date
            return date('Y.m.d');
            
        } catch (\Exception $e) {
            // Ultimate fallback
            return '1.0.0';
        }
    }

    /**
     * Generate display name for azan file
     */
    private function generateAzanDisplayName($filename)
    {
        $names = [
            'makkah' => '🕌 Makkah (Tradisional)',
            'madinah' => '🏛️ Madinah (Moden)',
            'malaysia' => '🇲🇾 Malaysia',
            'madinah-fajr' => '🌅 Madinah Fajr (Khusus Subuh)',
            'makkah-fajr' => '🌅 Makkah Fajr (Khusus Subuh)',
            'malaysia-fajr' => '🌅 Malaysia Fajr (Khusus Subuh)',
        ];
        
        // Check for exact matches first
        if (isset($names[$filename])) {
            return $names[$filename];
        }
        
        // Check for patterns
        if (str_contains($filename, '-fajr') || str_contains($filename, 'fajr')) {
            $base = str_replace(['-fajr', 'fajr'], '', $filename);
            return "🌅 " . ucfirst($base) . " Fajr (Khusus Subuh)";
        }
        
        // Default formatting
        return "🎵 " . ucfirst(str_replace(['-', '_'], ' ', $filename));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Multi-Masjid Data Isolation - STRICT MODE for Tetapan
        if ($user->isSuperAdmin()) {
            // Super Admin is root - can manage settings for any masjid OR their personal settings
            $selectedMasjidId = $request->get('masjid_id');
            
            if (!$selectedMasjidId) {
                // Default to Super Admin personal settings
                return redirect()->route('tetapan.index', ['masjid_id' => 'personal']);
            }

            if ($selectedMasjidId === 'personal') {
                // Super Admin Personal Settings (masjid_id = null)
                $query = Tetapan::withoutMasjidScope()->whereNull('masjid_id');
                $selectedMasjid = (object) [
                    'id' => null,
                    'nama' => 'Tetapan Peribadi Super Admin',
                    'status' => 'active'
                ];
            } else {
                // Validate selected masjid exists and is active
                $selectedMasjid = \App\Models\Masjid::where('id', $selectedMasjidId)
                                                     ->where('status', 'active')
                                                     ->first();
                if (!$selectedMasjid) {
                    abort(404, 'Masjid yang dipilih tidak dijumpai atau tidak aktif.');
                }

                // Get tetapan for specific masjid (bypass MasjidScope)
                $query = Tetapan::withoutMasjidScope()->where('masjid_id', $selectedMasjidId);
            }
            
            // Get all active masjids for dropdown + personal option
            $masjids = \App\Models\Masjid::where('status', 'active')
                                        ->orderBy('nama')
                                        ->get();
            
            // Super Admin doesn't need administrator selection - they manage masjid settings directly
            $administrators = collect(); // Empty collection
            $selectedAdmin = null; // No specific admin
            $selectedAdminId = null;
        } else {
            // Admin Masjid can only see their own masjid settings
            $query = Tetapan::query(); // MasjidScope will automatically filter
            $selectedMasjid = $user->masjid;
            $masjids = collect(); // Empty collection
            $administrators = collect(); // Empty collection
            $selectedAdmin = $user; // Current admin user
            $selectedMasjidId = $user->masjid_id;
            $selectedAdminId = $user->id;
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $query->filterByKategori($request->kategori);
        }

        // Filter by type
        if ($request->filled('jenis')) {
            $query->filterByJenis($request->jenis);
        }

        $tetapan = $query->ordered()->get();

        // Get available categories and types for filters
        if ($user->isSuperAdmin()) {
            $kategori = Tetapan::withoutMasjidScope()
                              ->where('masjid_id', $selectedMasjidId)
                              ->distinct()
                              ->pluck('kategori');
            $jenis = Tetapan::withoutMasjidScope()
                           ->where('masjid_id', $selectedMasjidId)
                           ->distinct()
                           ->pluck('jenis');
        } else {
            $kategori = Tetapan::distinct()->pluck('kategori');
            $jenis = Tetapan::distinct()->pluck('jenis');
        }

        // Get available azan files
        $azanFiles = $this->getAvailableAzanFiles();
        $fajrAzanFiles = $azanFiles->filter(fn($file) => $file['is_fajr']);
        $regularAzanFiles = $azanFiles->filter(fn($file) => !$file['is_fajr']);

        // Get current system version
        $currentVersion = $this->getCurrentSystemVersion();

        return view('tetapan.index', compact('tetapan', 'kategori', 'jenis', 'user', 'masjids', 'selectedMasjid', 'selectedMasjidId', 'administrators', 'selectedAdmin', 'selectedAdminId', 'azanFiles', 'fajrAzanFiles', 'regularAzanFiles', 'currentVersion'));
    }

    // Note: store() method removed - Tetapan are pre-defined, no manual creation allowed

    // Note: update() method removed - Individual tetapan updates handled via bulkUpdate() only

    /**
     * Bulk update tetapan for current user's masjid
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'tetapan' => 'required|array',
            'tetapan.*' => 'nullable',
            'masjid_id' => 'nullable', // For Super Admin - can be 'personal' or masjid ID
        ]);

        // Additional validation for Super Admin masjid_id
        if (auth()->user()->isSuperAdmin()) {
            $masjidId = $request->get('masjid_id');
            if ($masjidId && $masjidId !== 'personal') {
                // Validate masjid exists if not personal
                $masjidExists = \App\Models\Masjid::where('id', $masjidId)
                                                  ->where('status', 'active')
                                                  ->exists();
                if (!$masjidExists) {
                    return redirect()->back()
                        ->with('error', 'Masjid yang dipilih tidak dijumpai atau tidak aktif.');
                }
            }
        }

        $user = Auth::user();

        // Multi-Masjid Data Isolation - STRICT MODE for Tetapan Updates
        if ($user->isSuperAdmin()) {
            // Super Admin must specify which masjid's settings to update OR personal
            $targetMasjidId = $request->get('masjid_id');
            
            if (!$targetMasjidId) {
                return redirect()->back()
                    ->with('error', 'Target tidak dinyatakan untuk kemaskini tetapan.');
            }

            $targetMasjid = null;
            $isPersonalSettings = false;

            if ($targetMasjidId === 'personal') {
                // Super Admin Personal Settings
                $isPersonalSettings = true;
                $targetMasjid = (object) [
                    'id' => null,
                    'nama' => 'Super Admin Personal Settings'
                ];
            } else {
                // Validate target masjid exists and is active
                $targetMasjid = \App\Models\Masjid::where('id', $targetMasjidId)
                                                 ->where('status', 'active')
                                                 ->first();
                if (!$targetMasjid) {
                    return redirect()->back()
                        ->with('error', 'Masjid yang dipilih tidak dijumpai atau tidak aktif.');
                }
            }

            // Update settings for specific masjid
            foreach ($request->tetapan as $kunci => $nilai) {
                if ($kunci && $nilai !== null) {
                    // Handle boolean values
                    if (in_array($kunci, ['recaptcha_enabled', 'notify_new_user', 'notify_login_failed', 'notify_system_error'])) {
                        $nilai = (bool) $nilai;
                    }

                    // Handle number values
                    if (in_array($kunci, ['max_login_attempts', 'session_timeout', 'default_latitude', 'default_longitude'])) {
                        $nilai = (float) $nilai;
                    }

                    // Update for specific masjid or personal settings (bypass static method)
                    Tetapan::withoutMasjidScope()->updateOrCreate(
                        [
                            'masjid_id' => $isPersonalSettings ? null : $targetMasjidId,
                            'kunci' => $kunci
                        ],
                        [
                            'nama' => ucfirst(str_replace('_', ' ', $kunci)),
                            'nilai' => $nilai,
                            'jenis' => 'text',
                            'kategori' => 'custom',
                            'susunan' => 999,
                            'boleh_edit' => true,
                            'updated_by' => $user->id,
                            'created_by' => $user->id
                        ]
                    );
                }
            }

            return redirect()->route('tetapan.index', ['masjid_id' => $targetMasjidId])
                ->with('success', 'Tetapan untuk ' . $targetMasjid->nama . ' berjaya dikemaskini.');
        } else {
            // Admin Masjid can only update their own masjid settings
            foreach ($request->tetapan as $kunci => $nilai) {
                if ($kunci && $nilai !== null) {
                    // Handle boolean values
                    if (in_array($kunci, ['recaptcha_enabled', 'notify_new_user', 'notify_login_failed', 'notify_system_error'])) {
                        $nilai = (bool) $nilai;
                    }

                    // Handle number values
                    if (in_array($kunci, ['max_login_attempts', 'session_timeout', 'default_latitude', 'default_longitude'])) {
                        $nilai = (float) $nilai;
                    }

                    // Use static method which automatically handles masjid_id
                    Tetapan::set($kunci, $nilai);
                }
            }

            return redirect()->route('tetapan.index')
                ->with('success', 'Tetapan berjaya dikemaskini.');
        }
    }

    // Note: destroy() method removed - Tetapan are pre-defined, no deletion allowed
}
