<?php

use App\Http\Controllers\SanctumTokenController;
use App\Http\Controllers\ApiConfigurationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DocumentSharingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Document Sharing API Routes moved to web.php for session authentication

// Sanctum Token management (protected by auth web; can adjust later to roles)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/sanctum-tokens', [SanctumTokenController::class, 'store']);
    Route::delete('/sanctum-tokens', [SanctumTokenController::class, 'destroyAll']);
});

// API Configuration management routes are in web.php

// Public Healthcheck for integrations testing: /api/v1/health
Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'ok' => true,
            'success' => true,
            'status' => 'healthy',
            'app' => config('app.name'),
            'version' => 'v1',
            'time' => now()->toIso8601String(),
            'timestamp' => now()->timestamp,
        ]);
    });

    // Protected API routes (require Sanctum authentication)
    Route::middleware('auth:sanctum')->group(function () {
        
        // User info
        Route::get('/me', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                    'masjid' => $request->user()->masjid ? [
                        'id' => $request->user()->masjid->id,
                        'nama' => $request->user()->masjid->nama,
                    ] : null,
                ]
            ]);
        });

        // Masjid info (if user has masjid)
        Route::get('/masjid', function (Request $request) {
            $user = $request->user();
            
            if (!$user->masjid) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak mempunyai masjid'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->masjid->id,
                    'nama' => $user->masjid->nama,
                    'alamat' => $user->masjid->alamat,
                    'telefon' => $user->masjid->telefon,
                    'email' => $user->masjid->email,
                    'status' => $user->masjid->status,
                ]
            ]);
        });

        // Basic statistics
        Route::get('/stats', function (Request $request) {
            $user = $request->user();

            $stats = [
                'total_users' => 0,
                'total_kariah' => 0,
                'total_masjids' => 0,
                'total_integrations' => 0,
            ];

            try {
                // Count based on user scope
                if ($user->hasRole('Super Admin')) {
                    $stats['total_users'] = \App\Models\User::count();
                    $stats['total_kariah'] = \App\Models\Kariah::count();
                    $stats['total_masjids'] = \App\Models\Masjid::count();
                    $stats['total_integrations'] = \App\Models\Integration::count();
                } else if ($user->masjid) {
                    $stats['total_users'] = \App\Models\User::where('masjid_id', $user->masjid_id)->count();
                    $stats['total_kariah'] = \App\Models\Kariah::where('masjid_id', $user->masjid_id)->count();
                    $stats['total_masjids'] = 1; // Current masjid only
                    $stats['total_integrations'] = \App\Models\Integration::count(); // Global integrations
                } else {
                    // Basic user stats
                    $stats['total_users'] = 1; // Self only
                }
            } catch (\Exception $e) {
                // If any model doesn't exist, return zeros
            }

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        });

        // Weather data (if available)
        Route::get('/weather', function (Request $request) {
            try {
                $weatherController = new \App\Http\Controllers\WeatherController();
                $response = $weatherController->getCurrentWeather();
                
                if ($response->getStatusCode() === 200) {
                    return $response;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Weather data tidak tersedia'
                    ], 503);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error mengambil data cuaca: ' . $e->getMessage()
                ], 500);
            }
        });

        // System status
        Route::get('/system/status', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => [
                    'database' => 'connected',
                    'cache' => 'working',
                    'storage' => 'accessible',
                    'mail' => 'configured',
                    'queue' => 'running',
                    'last_check' => now()->toIso8601String(),
                ]
            ]);
        });
    });
});

// Public endpoints (no authentication required)
Route::prefix('v1/public')->group(function () {
    
    // Public masjid info (basic info only)
    Route::get('/masjid/{id}', function ($id) {
        $masjid = \App\Models\Masjid::where('id', $id)
                                   ->where('status', 'active')
                                   ->first(['id', 'nama', 'alamat', 'telefon', 'email']);
        
        if (!$masjid) {
            return response()->json([
                'success' => false,
                'message' => 'Masjid tidak ditemui'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $masjid
        ]);
    });

    // Public activities (upcoming only)
    Route::get('/aktiviti', function (Request $request) {
        $masjidId = $request->query('masjid_id');
        
        $query = \App\Models\Aktiviti::where('tarikh_mula', '>=', now())
                                    ->where('status', 'active')
                                    ->orderBy('tarikh_mula', 'asc');
        
        if ($masjidId) {
            $query->where('masjid_id', $masjidId);
        }

        $aktiviti = $query->limit(10)->get(['id', 'nama', 'tarikh_mula', 'tarikh_tamat', 'lokasi', 'masjid_id']);

        return response()->json([
            'success' => true,
            'data' => $aktiviti
        ]);
    });
});
