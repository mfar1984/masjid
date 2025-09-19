<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Integration;
use App\Models\WeatherConfiguration;
use App\Models\ApiConfiguration;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can view all integrations with masjid selector
            $selectedMasjidId = $request->get('masjid_id');
            
            if (!$selectedMasjidId) {
                // Default to personal settings for Super Admin
                $selectedMasjidId = 'personal';
            }

            if ($selectedMasjidId === 'personal') {
                // Super Admin personal integrations (masjid_id = null)
                $query = Integration::withoutMasjidScope()->whereNull('masjid_id');
                $selectedMasjid = (object) ['nama' => 'Tetapan Peribadi (Super Admin)'];
            } else {
                $selectedMasjid = \App\Models\Masjid::where('id', $selectedMasjidId)
                                                     ->where('status', 'active')
                                                     ->first();
                if (!$selectedMasjid) {
                    // Fallback to personal if masjid not found
                    return redirect()->route('integrations.index', ['masjid_id' => 'personal']);
                }
                $query = Integration::withoutMasjidScope()->where('masjid_id', $selectedMasjidId);
            }
            
            $masjids = \App\Models\Masjid::where('status', 'active')
                                        ->orderBy('nama')
                                        ->get();
        } else {
            // Admin Masjid can only see their own masjid integrations
            $query = Integration::query(); // MasjidScope will automatically filter
            $selectedMasjid = $user->masjid;
            $selectedMasjidId = $user->masjid_id;
            $masjids = collect();
        }

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->filterByStatus($request->status);
        }

        if ($request->filled('jenis')) {
            $query->filterByType($request->jenis);
        }

        $integrations = $query->with(['createdBy', 'updatedBy'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        // Get filter options
        $statuses = Integration::getStatusOptions();
        $types = Integration::getJenisOptions();

        // Load actual email configuration data from database
        $targetMasjidId = null;
        if ($user->isSuperAdmin()) {
            if ($selectedMasjidId !== 'personal') {
                $targetMasjidId = $selectedMasjidId;
            }
        } else {
            $targetMasjidId = $user->masjid_id;
        }
        
        $emailConfig = (object) [
            'smtp_host' => \App\Models\Tetapan::get('smtp_host', 'localhost', $targetMasjidId),
            'smtp_port' => \App\Models\Tetapan::get('smtp_port', '587', $targetMasjidId),
            'username' => \App\Models\Tetapan::get('smtp_username', '', $targetMasjidId),
            'password' => \App\Models\Tetapan::get('smtp_password', '', $targetMasjidId) ? '••••••••••••••••' : '',
            'encryption' => strtoupper(\App\Models\Tetapan::get('smtp_encryption', 'tls', $targetMasjidId)),
            'authentication' => \App\Models\Tetapan::get('smtp_authentication', 'Required', $targetMasjidId),
            'from_name' => \App\Models\Tetapan::get('smtp_from_name', 'E-Masjid System', $targetMasjidId),
            'reply_to' => \App\Models\Tetapan::get('smtp_reply_to', '', $targetMasjidId) ?: \App\Models\Tetapan::get('smtp_username', '', $targetMasjidId),
            'connection_timeout' => \App\Models\Tetapan::get('smtp_timeout', '30', $targetMasjidId),
            'max_retries' => \App\Models\Tetapan::get('smtp_max_retries', '3', $targetMasjidId),
            'formatted_last_test' => \App\Models\Tetapan::get('smtp_last_test', 'Belum diuji', $targetMasjidId),
            'status_badge' => \App\Models\Tetapan::get('smtp_test_status', 'Belum diuji', $targetMasjidId)
        ];

        // Get or create weather configuration
        $weatherConfig = WeatherConfiguration::where('masjid_id', $targetMasjidId)->first();

        if (!$weatherConfig) {
            // Create default configuration if none exists
            $weatherConfig = WeatherConfiguration::create([
                'masjid_id' => $targetMasjidId,
                'provider' => 'OpenWeatherMap',
                'api_key' => '',
                'base_url' => 'https://api.openweathermap.org/data/2.5',
                'default_location' => 'Bintulu',
                'latitude' => 3.1667000,
                'longitude' => 113.0333000,
                'units' => 'metric',
                'language' => 'ms',
                'update_frequency' => 30,
                'cache_duration' => 20,
                'is_active' => true
            ]);
        }

        // Get or create API configuration
        $apiConfig = ApiConfiguration::first();

        if (!$apiConfig) {
            // Create default configuration if none exists
            $apiConfig = ApiConfiguration::create([
                'base_url' => url('/'),
                'version' => 'v1',
                'auth_type' => 'Bearer Token (Laravel Sanctum)',
                'rate_limit' => 0, // Unlimited
                'timeout' => 30,
                'max_retries' => 3,
                'ssl_verification' => 'enabled',
                'logging_level' => 'Info',
                'token_default_expiry' => '6h',
                'allowed_origins' => '',
                'default_abilities' => json_encode([
                    'read:overview',
                    'read:integrations',
                    'write:integrations'
                ]),
                'token_name' => 'e_masjid_api',
            ]);
        }

        $latestToken = (object) [
            'name' => 'public_website',
            'created_at' => '2 hari lalu',
            'last_used_at' => '1 jam lalu'
        ];

        // Check tab permissions
        $tabPermissions = [
            'email' => $user->hasPermission('integrations_email', 'read'),
            'weather' => $user->hasPermission('integrations_weather', 'read'),
            'api' => $user->hasPermission('integrations_api', 'read'),
        ];

        return view('integrations.index', compact(
            'integrations', 
            'statuses', 
            'types', 
            'user', 
            'selectedMasjid',
            'selectedMasjidId',
            'masjids',
            'emailConfig',
            'weatherConfig',
            'apiConfig',
            'latestToken',
            'tabPermissions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('integrations.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif,Dalam Pembangunan',
            'penerangan' => 'nullable|string',
            'url_endpoint' => 'nullable|url',
            'api_key' => 'nullable|string',
        ]);

        $user = Auth::user();

        $integration = Integration::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'penerangan' => $request->penerangan,
            'url_endpoint' => $request->url_endpoint,
            'api_key' => $request->api_key,
            'masjid_id' => $user->masjid_id,
            'created_by' => $user->id,
        ]);

        return redirect()->route('integrations.index')
            ->with('success', 'Integration berjaya dicipta.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Integration $integration)
    {
        $user = Auth::user();
        return view('integrations.show', compact('integration', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Integration $integration)
    {
        $user = Auth::user();
        return view('integrations.edit', compact('integration', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Integration $integration)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif,Dalam Pembangunan',
            'penerangan' => 'nullable|string',
            'url_endpoint' => 'nullable|url',
            'api_key' => 'nullable|string',
        ]);

        $user = Auth::user();

        $integration->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'penerangan' => $request->penerangan,
            'url_endpoint' => $request->url_endpoint,
            'api_key' => $request->api_key,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('integrations.index')
            ->with('success', 'Integration berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Integration $integration)
    {
        $integration->delete();

        return redirect()->route('integrations.index')
            ->with('success', 'Integration berjaya dipadamkan.');
    }

    /**
     * Export integrations to CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        
        // Apply same multi-masjid logic as index
        if ($user->isSuperAdmin()) {
            $selectedMasjidId = $request->get('masjid_id');
            $integrations = Integration::withoutMasjidScope()
                ->where('masjid_id', $selectedMasjidId)
                ->with(['createdBy', 'updatedBy'])
                ->get();
        } else {
            $integrations = Integration::with(['createdBy', 'updatedBy'])->get();
        }

        $filename = 'integrations_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = [
            'ID', 'Nama', 'Jenis', 'Status', 'Penerangan', 'URL Endpoint', 
            'API Key', 'Terakhir Sync', 'Dicipta Oleh', 'Dikemaskini Oleh',
            'Tarikh Cipta', 'Tarikh Kemaskini'
        ];

        $callback = function() use ($integrations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($integrations as $integration) {
                fputcsv($file, [
                    $integration->id,
                    $integration->nama,
                    $integration->jenis,
                    $integration->status,
                    $integration->penerangan,
                    $integration->url_endpoint,
                    $integration->api_key ? '***' : '', // Hide API key in export
                    $integration->terakhir_sync_formatted,
                    $integration->createdBy->name ?? '-',
                    $integration->updatedBy->name ?? '-',
                    $integration->created_at_formatted,
                    $integration->updated_at_formatted,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}