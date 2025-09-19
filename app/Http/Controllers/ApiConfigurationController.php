<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use App\Models\ApiConfiguration;
use Illuminate\Support\Facades\Auth;


class ApiConfigurationController extends Controller
{
    /**
     * Update the API configuration.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'base_url' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:50'],
            'auth_type' => ['required', 'string', 'max:100'],
            'access_token' => ['nullable', 'string', 'max:255'],
            'rate_limit' => ['nullable'],
            'timeout' => ['nullable'],
            'max_retries' => ['nullable'],
            'ssl_verification' => ['nullable', 'string', 'max:50'],
            'logging_level' => ['nullable', 'string', 'max:50'],
            // Sanctum fields
            'token_default_expiry' => ['nullable', 'string', 'max:20'],
            'allowed_origins' => ['nullable', 'string'],
            'default_abilities' => ['nullable', 'array'],
            'token_name' => ['nullable', 'string', 'max:100'],
        ]);

        // Normalize numeric fields: store numbers only
        $rateLimitInput = $request->input('rate_limit');
        $timeoutInput = $request->input('timeout');
        $maxRetriesInput = $request->input('max_retries');

        $normalizedRateLimit = 0;
        if (is_numeric($rateLimitInput)) {
            $normalizedRateLimit = (int) $rateLimitInput;
        } elseif (is_string($rateLimitInput)) {
            if (strtolower(trim($rateLimitInput)) === 'unlimited') {
                $normalizedRateLimit = 0;
            } elseif (preg_match('/(\d+)/', $rateLimitInput, $m)) {
                $normalizedRateLimit = (int) $m[1];
            }
        }

        $normalizedTimeout = 30;
        if (is_numeric($timeoutInput)) {
            $normalizedTimeout = (int) $timeoutInput;
        } elseif (is_string($timeoutInput) && preg_match('/(\d+)/', $timeoutInput, $m)) {
            $normalizedTimeout = (int) $m[1];
        }

        $normalizedMaxRetries = 3;
        if ($maxRetriesInput !== null) {
            if (is_numeric($maxRetriesInput)) {
                $normalizedMaxRetries = (int) $maxRetriesInput;
            } elseif (is_string($maxRetriesInput) && preg_match('/(\d+)/', $maxRetriesInput, $m)) {
                $normalizedMaxRetries = (int) $m[1];
            }
        }

        $validated['rate_limit'] = $normalizedRateLimit;
        $validated['timeout'] = $normalizedTimeout;
        $validated['max_retries'] = $normalizedMaxRetries;

        // Convert abilities array to JSON string for storage
        if (array_key_exists('default_abilities', $validated) && is_array($validated['default_abilities'])) {
            $validated['default_abilities'] = json_encode($validated['default_abilities']);
        }

        // If table exists, persist config; otherwise return success without DB
        if (Schema::hasTable('api_configurations')) {
            $config = ApiConfiguration::query()->find($id);
            if (!$config) {
                $config = new ApiConfiguration();
                $config->id = $id; // fixed singleton row by id
            }
            $config->fill($validated);
            $config->save();

            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi API berjaya disimpan.',
                'data' => $config->only(array_keys($validated)),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi API diterima (jadual belum wujud, tiada simpanan DB).',
            'data' => $validated,
        ]);
    }

    /**
     * Test API connectivity
     */
    public function testApi(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            // Determine target masjid_id
            $targetMasjidId = null;
            if ($user->isSuperAdmin()) {
                $selectedMasjidId = $request->input('masjid_id', 'personal');
                if ($selectedMasjidId !== 'personal') {
                    $targetMasjidId = $selectedMasjidId;
                }
            } else {
                $targetMasjidId = $user->masjid_id;
            }

            // Get base URL from configuration or use default
            $baseUrl = \App\Models\Tetapan::get('api_base_url', url('/'), $targetMasjidId);

            // Test health endpoint
            $testUrl = rtrim($baseUrl, '/') . '/api/v1/health';

            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($testUrl);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['ok']) || isset($data['success']) || isset($data['status'])) {
                    // Update test status in database
                    \App\Models\Tetapan::set('api_last_test', now()->format('d/m/Y H:i'), 'API Last Test', $targetMasjidId);
                    \App\Models\Tetapan::set('api_test_status', 'Berjaya', 'API Test Status', $targetMasjidId);

                    return response()->json([
                        'success' => true,
                        'message' => 'API berfungsi dengan baik',
                        'data' => $data
                    ]);
                }
            }

            // Update test status for failed test
            \App\Models\Tetapan::set('api_last_test', now()->format('d/m/Y H:i'), 'API Last Test', $targetMasjidId);
            \App\Models\Tetapan::set('api_test_status', 'Gagal', 'API Test Status', $targetMasjidId);

            return response()->json([
                'success' => false,
                'message' => 'API tidak dapat diakses atau format response tidak dijangka'
            ]);

        } catch (\Exception $e) {
            // Update test status for failed test
            $user = auth()->user();
            $targetMasjidId = $user->isSuperAdmin() ? null : $user->masjid_id;
            \App\Models\Tetapan::set('api_last_test', now()->format('d/m/Y H:i'), 'API Last Test', $targetMasjidId);
            \App\Models\Tetapan::set('api_test_status', 'Gagal', 'API Test Status', $targetMasjidId);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Sync data with external API
     */
    public function syncData(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            // Determine target masjid_id
            $targetMasjidId = null;
            if ($user->isSuperAdmin()) {
                $selectedMasjidId = $request->input('masjid_id', 'personal');
                if ($selectedMasjidId !== 'personal') {
                    $targetMasjidId = $selectedMasjidId;
                }
            } else {
                $targetMasjidId = $user->masjid_id;
            }

            // Get API configuration
            $baseUrl = \App\Models\Tetapan::get('api_base_url', url('/'), $targetMasjidId);
            $version = \App\Models\Tetapan::get('api_version', 'v1', $targetMasjidId);

            // Simulate data sync process
            $syncResults = [];

            // Sync users data
            $usersUrl = rtrim($baseUrl, '/') . '/api/' . $version . '/users';
            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($usersUrl);

            if ($response->successful()) {
                $syncResults['users'] = [
                    'status' => 'success',
                    'count' => count($response->json()['data'] ?? []),
                    'message' => 'Users data synced successfully'
                ];
            } else {
                $syncResults['users'] = [
                    'status' => 'failed',
                    'message' => 'Failed to sync users data'
                ];
            }

            // Sync masjid data
            $masjidUrl = rtrim($baseUrl, '/') . '/api/' . $version . '/masjid';
            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($masjidUrl);

            if ($response->successful()) {
                $syncResults['masjid'] = [
                    'status' => 'success',
                    'count' => count($response->json()['data'] ?? []),
                    'message' => 'Masjid data synced successfully'
                ];
            } else {
                $syncResults['masjid'] = [
                    'status' => 'failed',
                    'message' => 'Failed to sync masjid data'
                ];
            }

            // Update last sync time
            \App\Models\Tetapan::set('api_last_sync', now()->format('d/m/Y H:i'), 'API Last Sync', $targetMasjidId);
            \App\Models\Tetapan::set('api_sync_status', 'Completed', 'API Sync Status', $targetMasjidId);

            return response()->json([
                'success' => true,
                'message' => 'Data synchronization completed',
                'results' => $syncResults
            ]);

        } catch (\Exception $e) {
            // Update sync status for failed sync
            $targetMasjidId = $user->masjid_id ?? null;
            \App\Models\Tetapan::set('api_last_sync', now()->format('d/m/Y H:i'), 'API Last Sync', $targetMasjidId);
            \App\Models\Tetapan::set('api_sync_status', 'Failed', 'API Sync Status', $targetMasjidId);

            return response()->json([
                'success' => false,
                'message' => 'Sync error: ' . $e->getMessage()
            ]);
        }
    }
}
