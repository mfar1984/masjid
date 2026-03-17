<?php

use App\Http\Controllers\KariahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\WeatherConfigurationController;
use App\Http\Controllers\ApiConfigurationController;
use App\Http\Controllers\SanctumTokenController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentFolderController;
use App\Http\Controllers\Api\DocumentSharingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('overview');
});

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/overview', function () {
    $user = auth()->user();
    return view('overview', compact('user'));
})->middleware(['auth', 'verified'])->name('overview');

Route::get('/table-list', function () {
    return view('table-list');
})->middleware(['auth', 'verified'])->name('table-list');

Route::get('/textarea', function () {
    return view('textarea');
})->middleware(['auth', 'verified'])->name('textarea');

Route::get('/settings/global-config', function () {
    return view('settings.global-config');
})->middleware(['auth', 'verified'])->name('settings.global-config');

Route::get('/settings/role-management', function () {
    return view('settings.role-management');
})->middleware(['auth', 'verified'])->name('settings.role-management');

Route::get('/settings/user-management', function () {
    return view('settings.user-management');
})->middleware(['auth', 'verified'])->name('settings.user-management');

Route::get('/settings/activity-logs', function () {
    return view('settings.activity-logs');
})->middleware(['auth', 'verified'])->name('settings.activity-logs');

// Pentadbiran Sistem Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Senarai Masjid - Super Admin only (IMPORTANT: Specific routes MUST come before parameterized routes)
    Route::middleware(['permission:masjids,read'])->group(function () {
        Route::get('senarai-masjid', [App\Http\Controllers\MasjidController::class, 'index'])->name('senarai-masjid.index');
        Route::get('senarai-masjid-export', [App\Http\Controllers\MasjidController::class, 'export'])->name('senarai-masjid.export');
    });

    Route::middleware(['permission:masjids,create'])->group(function () {
        Route::get('senarai-masjid/create', [App\Http\Controllers\MasjidController::class, 'create'])->name('senarai-masjid.create');
        Route::post('senarai-masjid', [App\Http\Controllers\MasjidController::class, 'store'])->name('senarai-masjid.store');
    });

    Route::middleware(['permission:masjids,update'])->group(function () {
        Route::get('senarai-masjid/{masjid}/edit', [App\Http\Controllers\MasjidController::class, 'edit'])->name('senarai-masjid.edit');
        Route::put('senarai-masjid/{masjid}', [App\Http\Controllers\MasjidController::class, 'update'])->name('senarai-masjid.update');
        Route::patch('senarai-masjid/{masjid}', [App\Http\Controllers\MasjidController::class, 'update']);
    });

    Route::middleware(['permission:masjids,delete'])->group(function () {
        Route::delete('senarai-masjid/{masjid}', [App\Http\Controllers\MasjidController::class, 'destroy'])->name('senarai-masjid.destroy');
        Route::delete('senarai-masjid/attachment/{attachment}', [App\Http\Controllers\MasjidController::class, 'deleteAttachment'])->name('senarai-masjid.attachment.delete');
    });

    Route::middleware(['permission:masjids,approve'])->group(function () {
        Route::post('senarai-masjid/{masjid}/approve', [App\Http\Controllers\MasjidController::class, 'approve'])->name('senarai-masjid.approve');
    });

    Route::middleware(['permission:masjids,reject'])->group(function () {
        Route::post('senarai-masjid/{masjid}/reject', [App\Http\Controllers\MasjidController::class, 'reject'])->name('senarai-masjid.reject');
    });

    Route::middleware(['permission:masjids,suspend'])->group(function () {
        Route::post('senarai-masjid/{masjid}/suspend', [App\Http\Controllers\MasjidController::class, 'suspend'])->name('senarai-masjid.suspend');
    });

    Route::middleware(['permission:masjids,reactivate'])->group(function () {
        Route::post('senarai-masjid/{masjid}/unsuspend', [App\Http\Controllers\MasjidController::class, 'unsuspend'])->name('senarai-masjid.unsuspend');
    });

    Route::middleware(['permission:masjids,read'])->group(function () {
        // IMPORTANT: This MUST come AFTER /create route to avoid route conflict
        Route::get('senarai-masjid/{masjid}', [App\Http\Controllers\MasjidController::class, 'show'])->name('senarai-masjid.show');
    });

    // Senarai Kumpulan - Role-based access (IMPORTANT: Specific routes MUST come before parameterized routes)
    Route::middleware(['permission:roles,read'])->group(function () {
        Route::get('senarai-kumpulan', [App\Http\Controllers\RoleController::class, 'index'])->name('senarai-kumpulan.index');
    });

    Route::middleware(['permission:roles,create'])->group(function () {
        Route::get('senarai-kumpulan/create', [App\Http\Controllers\RoleController::class, 'create'])->name('senarai-kumpulan.create');
        Route::post('senarai-kumpulan', [App\Http\Controllers\RoleController::class, 'store'])->name('senarai-kumpulan.store');
    });

    Route::middleware(['permission:roles,update'])->group(function () {
        Route::get('senarai-kumpulan/{role}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('senarai-kumpulan.edit');
        Route::put('senarai-kumpulan/{role}', [App\Http\Controllers\RoleController::class, 'update'])->name('senarai-kumpulan.update');
        Route::patch('senarai-kumpulan/{role}', [App\Http\Controllers\RoleController::class, 'update']);
    });

    Route::middleware(['permission:roles,delete'])->group(function () {
        Route::delete('senarai-kumpulan/{role}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('senarai-kumpulan.destroy');
    });

    Route::middleware(['permission:roles,read'])->group(function () {
        // IMPORTANT: This MUST come AFTER /create route to avoid route conflict
        Route::get('senarai-kumpulan/{role}', [App\Http\Controllers\RoleController::class, 'show'])->name('senarai-kumpulan.show');
    });

    // Senarai Pengguna - Role-based access (IMPORTANT: Specific routes MUST come before parameterized routes)
    Route::middleware(['permission:users,read'])->group(function () {
        Route::get('senarai-pengguna', [App\Http\Controllers\UserController::class, 'index'])->name('senarai-pengguna.index');
    });

    Route::middleware(['permission:users,create'])->group(function () {
        Route::get('senarai-pengguna/create', [App\Http\Controllers\UserController::class, 'create'])->name('senarai-pengguna.create');
        Route::post('senarai-pengguna', [App\Http\Controllers\UserController::class, 'store'])->name('senarai-pengguna.store');
    });

    Route::middleware(['permission:users,update'])->group(function () {
        Route::get('senarai-pengguna/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('senarai-pengguna.edit');
        Route::put('senarai-pengguna/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('senarai-pengguna.update');
        Route::patch('senarai-pengguna/{user}', [App\Http\Controllers\UserController::class, 'update']);
    });

    Route::middleware(['permission:users,delete'])->group(function () {
        Route::delete('senarai-pengguna/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('senarai-pengguna.destroy');
    });

    // User workflow actions with specific permissions
    Route::middleware(['permission:users,reactivate'])->group(function () {
        Route::post('senarai-pengguna/{user}/verify', [App\Http\Controllers\UserController::class, 'verify'])->name('senarai-pengguna.verify');
    });

    Route::middleware(['permission:users,suspend'])->group(function () {
        Route::post('senarai-pengguna/{user}/unverify', [App\Http\Controllers\UserController::class, 'unverify'])->name('senarai-pengguna.unverify');
    });

    Route::middleware(['permission:users,read'])->group(function () {
        // IMPORTANT: This MUST come AFTER /create route to avoid route conflict
        Route::get('senarai-pengguna/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('senarai-pengguna.show');
    });

    // Tetapan Umum - Settings management for each masjid (read and update only)
    Route::middleware(['permission:settings,read'])->group(function () {
        Route::get('tetapan', [App\Http\Controllers\TetapanController::class, 'index'])->name('tetapan.index');
    });

    Route::middleware(['permission:settings,update'])->group(function () {
        Route::post('tetapan/bulk-update', [App\Http\Controllers\TetapanController::class, 'bulkUpdate'])->name('tetapan.bulk-update');
    });
});

// Bantuan Routes
Route::get('bantuan/nota-keluaran', function () {
    return view('bantuan.nota-keluaran');
})->name('bantuan.nota-keluaran')->middleware(['auth', 'verified']);

Route::get('bantuan/status-sistem', [App\Http\Controllers\StatusSistemController::class, 'index'])->name('bantuan.status-sistem')->middleware(['auth', 'verified']);
Route::post('bantuan/status-sistem/refresh', [App\Http\Controllers\StatusSistemController::class, 'refresh'])->name('bantuan.status-sistem.refresh')->middleware(['auth', 'verified']);

// Weather API
Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'getWeather'])
    ->middleware('api')
    ->name('weather');

// Prayer Times API - with Multi-Masjid Data Isolation
Route::get('/api/esolat/today', function() {
    try {
        $user = auth()->user();
        
        // Multi-Masjid Data Isolation for Prayer Zone
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated']);
        }

        // Get prayer zone based on user's masjid or Super Admin selection
        if ($user->isSuperAdmin()) {
            // Super Admin: get zone from selected masjid or personal settings
            $selectedMasjidId = request()->get('masjid_id');
            if ($selectedMasjidId === 'personal') {
                // Super Admin Personal Settings (masjid_id = null)
                $zone = \App\Models\Tetapan::get('prayer_zone', 'SWK08', null);
            } elseif ($selectedMasjidId) {
                // Get from specific masjid
                $zone = \App\Models\Tetapan::get('prayer_zone', 'SWK08', $selectedMasjidId);
            } else {
                // Fallback to Super Admin personal settings first
                $personalZone = \App\Models\Tetapan::get('prayer_zone', null, null);
                if ($personalZone) {
                    $zone = $personalZone;
                } else {
                    // Then fallback to first active masjid's zone
                    $firstMasjid = \App\Models\Masjid::where('status', 'active')->first();
                    $zone = $firstMasjid ? \App\Models\Tetapan::get('prayer_zone', 'SWK08', $firstMasjid->id) : 'SWK08';
                }
            }
        } else {
            // Admin Masjid: get zone from their masjid only
            $zone = \App\Models\Tetapan::get('prayer_zone', 'SWK08', $user->masjid_id);
        }

        // Call e-Solat JAKIM API directly
        $url = 'https://www.e-solat.gov.my/index.php?r=esolatApi/takwimsolat&period=today&zone=' . urlencode($zone);
        $response = @file_get_contents($url);
        $json = $response ? json_decode($response, true) : null;
        $obj = $json['prayerTime'][0] ?? [];

        // Normalize time format function
        $normalize = function($v) {
            $v = trim((string)($v ?? ''));
            if ($v === '' || $v === '-' || $v === '00:00:00') { return '--:--'; }
            // Convert 24h HH:MM:SS to HH:MM AM/PM
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $v)) {
                [$h, $m] = explode(':', $v); $ampm = ((int)$h < 12) ? 'AM' : 'PM';
                $h12 = (int)$h % 12; if ($h12 === 0) { $h12 = 12; }
                return sprintf('%02d:%02d %s', $h12, (int)$m, $ampm);
            }
            return $v;
        };

        // Handle possible key variants from API
        $get = function(array $a, array $keys) { 
            foreach ($keys as $k) { 
                if (isset($a[$k]) && $a[$k] !== null && $a[$k] !== '') return $a[$k]; 
            } 
            return null; 
        };

        $times = [
            'imsak'   => $normalize($get($obj, ['imsak'])),
            'fajr'    => $normalize($get($obj, ['fajr','subuh'])),
            'syuruk'  => $normalize($get($obj, ['syuruk','syrok','syuruk_time'])),
            'dhuha'   => $normalize($get($obj, ['dhuha','duha'])),
            'zuhr'    => $normalize($get($obj, ['zohor','zuhr','zuhur','dzuhur','dhuhr'])),
            'asr'     => $normalize($get($obj, ['asar','asr'])),
            'maghrib' => $normalize($get($obj, ['maghrib'])),
            'isha'    => $normalize($get($obj, ['isyak','isha','isya'])),
        ];

        return response()->json(['success' => true, 'zone' => $zone, 'times' => $times]);
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'message' => 'Failed to fetch e-Solat: ' . $e->getMessage()]);
    }
})->middleware(['auth', 'verified']);

// API Route for Azan Settings
Route::get('/api/azan-settings', function() {
    try {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['enabled' => false, 'type' => 'makkah', 'volume' => '0.7']);
        }

        // Get azan settings based on user's masjid or Super Admin selection
        if ($user->isSuperAdmin()) {
            $selectedMasjidId = request()->get('masjid_id');
            if ($selectedMasjidId === 'personal') {
                // Super Admin Personal Settings
                $enabled = \App\Models\Tetapan::get('azan_enabled', '1', null);
                $fajrType = \App\Models\Tetapan::get('azan_fajr_type', 'madinah-fajr', null);
                $regularType = \App\Models\Tetapan::get('azan_regular_type', 'makkah', null);
                $volume = \App\Models\Tetapan::get('azan_volume', '0.7', null);
            } elseif ($selectedMasjidId) {
                // Specific masjid settings
                $enabled = \App\Models\Tetapan::get('azan_enabled', '1', $selectedMasjidId);
                $fajrType = \App\Models\Tetapan::get('azan_fajr_type', 'madinah-fajr', $selectedMasjidId);
                $regularType = \App\Models\Tetapan::get('azan_regular_type', 'makkah', $selectedMasjidId);
                $volume = \App\Models\Tetapan::get('azan_volume', '0.7', $selectedMasjidId);
            } else {
                // Default to personal settings
                $enabled = \App\Models\Tetapan::get('azan_enabled', '1', null);
                $fajrType = \App\Models\Tetapan::get('azan_fajr_type', 'madinah-fajr', null);
                $regularType = \App\Models\Tetapan::get('azan_regular_type', 'makkah', null);
                $volume = \App\Models\Tetapan::get('azan_volume', '0.7', null);
            }
        } else {
            // Admin Masjid: get from their masjid only
            $enabled = \App\Models\Tetapan::get('azan_enabled', '1', $user->masjid_id);
            $fajrType = \App\Models\Tetapan::get('azan_fajr_type', 'madinah-fajr', $user->masjid_id);
            $regularType = \App\Models\Tetapan::get('azan_regular_type', 'makkah', $user->masjid_id);
            $volume = \App\Models\Tetapan::get('azan_volume', '0.7', $user->masjid_id);
        }

        return response()->json([
            'enabled' => $enabled === '1',
            'fajr_type' => $fajrType,
            'regular_type' => $regularType,
            'volume' => $volume
        ]);
    } catch (\Throwable $e) {
        return response()->json(['enabled' => false, 'type' => 'makkah', 'volume' => '0.7']);
    }
})->middleware(['auth', 'verified']);

// Integration Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // IMPORTANT: Specific routes MUST come before parameterized routes to avoid conflicts

    // Weather Configuration Routes (MUST be before /integrations/{integration})
    Route::get('/weather-configurations', [WeatherConfigurationController::class, 'index'])->name('weather-configurations.index')->middleware('permission:integrations_weather,read');
    Route::post('/weather-configurations', [WeatherConfigurationController::class, 'update'])->name('weather-configurations.update')->middleware('permission:integrations_weather,update');
    Route::post('/weather-configurations/test', [WeatherConfigurationController::class, 'testApi'])->name('weather-configurations.test')->middleware('permission:integrations_weather,update');
    Route::post('/weather-configurations/refresh', [WeatherConfigurationController::class, 'refreshData'])->name('weather-configurations.refresh')->middleware('permission:integrations_weather,update');

    // API Configuration Routes
    Route::post('/api-configurations/{id}', [ApiConfigurationController::class, 'update'])->name('api-configurations.update')->middleware('permission:integrations_api,update');
    Route::post('/api-configurations/test', [ApiConfigurationController::class, 'testApi'])->name('api-configurations.test')->middleware('permission:integrations_api,update');
    Route::post('/api-configurations/sync', [ApiConfigurationController::class, 'syncData'])->name('api-configurations.sync')->middleware('permission:integrations_api,update');

    // Sanctum Token Management Routes
    Route::get('/sanctum-tokens', [SanctumTokenController::class, 'index'])->name('sanctum-tokens.index')->middleware('permission:integrations_api,read');
    Route::post('/sanctum-tokens', [SanctumTokenController::class, 'store'])->name('sanctum-tokens.store')->middleware('permission:integrations_api,update');
    Route::delete('/sanctum-tokens', [SanctumTokenController::class, 'destroyAll'])->name('sanctum-tokens.destroy-all')->middleware('permission:integrations_api,update');

    Route::get('/integrations', [IntegrationController::class, 'index'])->name('integrations.index')->middleware('permission:integrations,read');
    Route::get('/integrations/create', [IntegrationController::class, 'create'])->name('integrations.create')->middleware('permission:integrations,update');
    Route::post('/integrations', [IntegrationController::class, 'store'])->name('integrations.store')->middleware('permission:integrations,update');
    Route::get('/integrations/{integration}', [IntegrationController::class, 'show'])->name('integrations.show')->middleware('permission:integrations,read');
    Route::get('/integrations/{integration}/edit', [IntegrationController::class, 'edit'])->name('integrations.edit')->middleware('permission:integrations,update');
    Route::put('/integrations/{integration}', [IntegrationController::class, 'update'])->name('integrations.update')->middleware('permission:integrations,update');
    Route::delete('/integrations/{integration}', [IntegrationController::class, 'destroy'])->name('integrations.destroy')->middleware('permission:integrations,update');
    Route::get('/integrations-export', [IntegrationController::class, 'export'])->name('integrations.export')->middleware('permission:integrations,read');
    
    // Integration API routes (mock endpoints for now)
    Route::post('/email-configurations/{id}', function(\Illuminate\Http\Request $request, $id) {
        try {
            $user = auth()->user();
            
            // Validate the request
            $validated = $request->validate([
                'smtp_host' => 'required|string|max:255',
                'smtp_port' => 'required|integer|min:1|max:65535',
                'smtp_username' => 'required|email|max:255',
                'smtp_password' => 'required|string|max:255',
                'smtp_encryption' => 'required|in:tls,ssl,none',
                'smtp_authentication' => 'required|in:Required,None',
                'smtp_from_name' => 'required|string|max:255',
                'smtp_reply_to' => 'required|email|max:255',
                'smtp_timeout' => 'required|integer|min:1|max:300',
                'smtp_max_retries' => 'required|integer|min:1|max:10',
            ]);
            
            // Determine masjid_id for data isolation
            $masjidId = null;
            if ($user->isSuperAdmin()) {
                // For Super Admin, check if they're editing personal settings or masjid settings
                $selectedMasjidId = request()->get('masjid_id');
                if ($selectedMasjidId && $selectedMasjidId !== 'personal') {
                    $masjidId = $selectedMasjidId;
                }
                // If personal or not specified, masjid_id stays null
            } else {
                // For Admin Masjid, use their masjid_id
                $masjidId = $user->masjid_id;
            }
            
            // Save email configuration using Tetapan model
            foreach ($validated as $key => $value) {
                \App\Models\Tetapan::set($key, $value, $masjidId);
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Konfigurasi email berjaya dikemaskini!'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sah: ' . implode(', ', collect($e->errors())->flatten()->toArray())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Email configuration update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ralat sistem: Gagal menyimpan konfigurasi'
            ], 500);
        }
    })->name('email-configurations.update')->middleware('permission:integrations,update');

    Route::get('/email-configurations/smtp-health', function() {
        return response()->json(['success' => true, 'message' => 'SMTP connection healthy', 'latency_ms' => '45']);
    })->name('email-configurations.smtp-health');

    
    // Test Email Route - within integration group
    Route::post('/test-email-send', function(\Illuminate\Http\Request $request) {
        \Log::info('TEST EMAIL ROUTE HIT - Starting processing', [
            'timestamp' => now()->toDateTimeString(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            $user = auth()->user();

            \Log::info('Test email request received', [
                'user_id' => $user->id ?? 'null',
                'user_email' => $user->email ?? 'unknown',
                'user_is_super_admin' => $user ? $user->isSuperAdmin() : false,
                'request_data' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'csrf_token_from_request' => $request->input('_token'),
                'csrf_token_length' => strlen($request->input('_token') ?? ''),
                'session_token' => session()->token(),
                'session_id' => session()->getId()
            ]);
            
            // Check if user is authenticated
            if (!$user) {
                \Log::error('Test email failed: User not authenticated');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Validate the request
            try {
                $validated = $request->validate([
                    'recipient_email' => 'required|email|max:255',
                ]);
                \Log::info('Validation passed', ['validated' => $validated]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Validation failed', [
                    'errors' => $e->errors(),
                    'input' => $request->all()
                ]);
                throw $e;
            }
            
            // Determine masjid_id for data isolation
            $masjidId = null;
            if ($user->isSuperAdmin()) {
                $selectedMasjidId = request()->get('masjid_id');
                if ($selectedMasjidId && $selectedMasjidId !== 'personal') {
                    $masjidId = $selectedMasjidId;
                }
            } else {
                $masjidId = $user->masjid_id;
            }

            // Get email configuration
            $smtpHost = \App\Models\Tetapan::get('smtp_host', 'localhost', $masjidId);
            $smtpPort = \App\Models\Tetapan::get('smtp_port', '587', $masjidId);
            $smtpUsername = \App\Models\Tetapan::get('smtp_username', '', $masjidId);
            $smtpPassword = \App\Models\Tetapan::get('smtp_password', '', $masjidId);
            $smtpEncryption = \App\Models\Tetapan::get('smtp_encryption', 'tls', $masjidId);
            $fromName = \App\Models\Tetapan::get('smtp_from_name', 'E-Masjid System', $masjidId);

            \Log::info('Test email SMTP configuration check', [
                'masjid_id' => $masjidId,
                'smtp_host' => $smtpHost,
                'smtp_username' => $smtpUsername,
                'smtp_password_set' => !empty($smtpPassword),
                'selected_masjid_id_from_request' => request()->get('masjid_id')
            ]);

            // Check if SMTP configuration is complete
            if (empty($smtpHost) || empty($smtpUsername) || empty($smtpPassword)) {
                \Log::error('SMTP configuration incomplete', [
                    'masjid_id' => $masjidId,
                    'smtp_host_empty' => empty($smtpHost),
                    'smtp_username_empty' => empty($smtpUsername),
                    'smtp_password_empty' => empty($smtpPassword)
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi SMTP tidak lengkap. Sila lengkapkan tetapan email terlebih dahulu atau pilih masjid yang betul.'
                ], 422);
            }
            
            \Log::info('Starting real email sending', [
                'recipient' => $validated['recipient_email'],
                'smtp_host' => $smtpHost,
                'smtp_username' => $smtpUsername,
                'from_name' => $fromName
            ]);

            try {
                // Configure mail settings dynamically and force SMTP
                config([
                    'mail.default' => 'smtp',  // Force SMTP mailer
                    'mail.mailers.smtp.host' => $smtpHost,
                    'mail.mailers.smtp.port' => $smtpPort,
                    'mail.mailers.smtp.username' => $smtpUsername,
                    'mail.mailers.smtp.password' => $smtpPassword,
                    'mail.mailers.smtp.encryption' => $smtpEncryption,
                    'mail.from.address' => $smtpUsername,
                    'mail.from.name' => $fromName,
                ]);

                \Log::info('Mail configuration set', [
                    'default_mailer' => config('mail.default'),
                    'host' => $smtpHost,
                    'port' => $smtpPort,
                    'encryption' => $smtpEncryption,
                    'from_address' => $smtpUsername,
                    'from_name' => $fromName
                ]);

                // Send the actual email
                Mail::to($validated['recipient_email'])->send(new \App\Mail\TestEmail($fromName));

                \Log::info('Test email sent successfully', [
                    'recipient' => $validated['recipient_email'],
                    'from_name' => $fromName,
                    'smtp_host' => $smtpHost
                ]);

                // Update test status and last test time in database
                \App\Models\Tetapan::set('smtp_last_test', now()->format('d/m/Y H:i'), 'SMTP Last Test', $masjidId);
                \App\Models\Tetapan::set('smtp_test_status', 'Berjaya', 'SMTP Test Status', $masjidId);

                return response()->json([
                    'success' => true,
                    'message' => 'Test email berjaya dihantar ke ' . $validated['recipient_email']
                ]);

            } catch (\Exception $e) {
                \Log::error('Failed to send test email', [
                    'recipient' => $validated['recipient_email'],
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'smtp_config' => [
                        'host' => $smtpHost,
                        'port' => $smtpPort,
                        'username' => $smtpUsername,
                        'encryption' => $smtpEncryption
                    ]
                ]);

                // Update test status for failed test
                \App\Models\Tetapan::set('smtp_last_test', now()->format('d/m/Y H:i'), 'SMTP Last Test', $masjidId);
                \App\Models\Tetapan::set('smtp_test_status', 'Gagal', 'SMTP Test Status', $masjidId);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghantar test email: ' . $e->getMessage()
                ], 500);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Exception in test email', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Email tidak sah: ' . implode(', ', collect($e->errors())->flatten()->toArray())
            ], 422);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            \Log::error('CSRF Token Mismatch in test email', [
                'request_token' => $request->input('_token'),
                'session_token' => session()->token(),
                'session_id' => session()->getId(),
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'CSRF token tidak sah. Sila refresh halaman dan cuba lagi.'
            ], 419);
        } catch (\Exception $e) {
            \Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Ralat sistem: Gagal menghantar test email'
            ], 500);
        }
    })->name('test-email-send')->middleware('permission:integrations_email,update');

});

// Kariah Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kariah', [KariahController::class, 'index'])->middleware('permission:kariah,read')->name('kariah.index');
    Route::get('/kariah/create', [KariahController::class, 'create'])->middleware('permission:kariah,create')->name('kariah.create');
    Route::post('/kariah', [KariahController::class, 'store'])->middleware('permission:kariah,create')->name('kariah.store');
    Route::get('/kariah/{kariah}', [KariahController::class, 'show'])->middleware('permission:kariah,read')->name('kariah.show');
    Route::get('/kariah/{kariah}/edit', [KariahController::class, 'edit'])->middleware('permission:kariah,update')->name('kariah.edit');
    Route::put('/kariah/{kariah}', [KariahController::class, 'update'])->middleware('permission:kariah,update')->name('kariah.update');
    Route::delete('/kariah/{kariah}', [KariahController::class, 'destroy'])->middleware('permission:kariah,delete')->name('kariah.destroy');
    Route::get('/kariah-export', [KariahController::class, 'export'])->middleware('permission:kariah,read')->name('kariah.export');

    // Workflow routes
    Route::post('/kariah/{kariah}/approve', [KariahController::class, 'approve'])->middleware('permission:kariah,approve')->name('kariah.approve');
    Route::post('/kariah/{kariah}/reject', [KariahController::class, 'reject'])->middleware('permission:kariah,reject')->name('kariah.reject');
    Route::post('/kariah/{kariah}/suspend', [KariahController::class, 'suspend'])->middleware('permission:kariah,suspend')->name('kariah.suspend');
    Route::post('/kariah/{kariah}/reactivate', [KariahController::class, 'reactivate'])->middleware('permission:kariah,reactivate')->name('kariah.reactivate');
});

// AJK Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ajk', [App\Http\Controllers\AjkController::class, 'index'])->middleware('permission:ajk,read')->name('ajk.index');
    Route::get('/ajk/create', [App\Http\Controllers\AjkController::class, 'create'])->middleware('permission:ajk,create')->name('ajk.create');
    Route::post('/ajk', [App\Http\Controllers\AjkController::class, 'store'])->middleware('permission:ajk,create')->name('ajk.store');
    Route::get('/ajk/{ajk}', [App\Http\Controllers\AjkController::class, 'show'])->middleware('permission:ajk,read')->name('ajk.show');
    Route::get('/ajk/{ajk}/edit', [App\Http\Controllers\AjkController::class, 'edit'])->middleware('permission:ajk,update')->name('ajk.edit');
    Route::put('/ajk/{ajk}', [App\Http\Controllers\AjkController::class, 'update'])->middleware('permission:ajk,update')->name('ajk.update');
    Route::delete('/ajk/{ajk}', [App\Http\Controllers\AjkController::class, 'destroy'])->middleware('permission:ajk,delete')->name('ajk.destroy');
    Route::get('/ajk-export', [App\Http\Controllers\AjkController::class, 'export'])->middleware('permission:ajk,read')->name('ajk.export');

    // Arkib routes
    Route::get('/ajk-arkib', [App\Http\Controllers\AjkController::class, 'arkib'])->middleware('permission:ajk,read')->name('ajk.arkib');
    Route::post('/ajk/{ajk}/archive', [App\Http\Controllers\AjkController::class, 'archive'])->middleware('permission:ajk,update')->name('ajk.archive');
    Route::post('/ajk/{ajk}/unarchive', [App\Http\Controllers\AjkController::class, 'unarchive'])->middleware('permission:ajk,update')->name('ajk.unarchive');
    Route::get('/ajk/{ajk}/copy', [App\Http\Controllers\AjkController::class, 'copy'])->middleware('permission:ajk,create')->name('ajk.copy');

    // Laporan routes
    Route::get('/ajk-laporan', [App\Http\Controllers\AjkController::class, 'laporan'])->middleware('permission:ajk,read')->name('ajk.laporan');
    Route::get('/ajk-laporan-export', [App\Http\Controllers\AjkController::class, 'laporanExport'])->middleware('permission:ajk,read')->name('ajk.laporan.export');

    // Carta Organisasi route
    Route::get('/ajk-carta-organisasi', [App\Http\Controllers\AjkController::class, 'cartaOrganisasi'])->middleware('permission:ajk,read')->name('ajk.carta-organisasi');

    // Workflow routes
    Route::post('/ajk/{ajk}/approve', [App\Http\Controllers\AjkController::class, 'approve'])->middleware('permission:ajk,approve')->name('ajk.approve');
    Route::post('/ajk/{ajk}/reject', [App\Http\Controllers\AjkController::class, 'reject'])->middleware('permission:ajk,reject')->name('ajk.reject');
    Route::post('/ajk/{ajk}/suspend', [App\Http\Controllers\AjkController::class, 'suspend'])->middleware('permission:ajk,suspend')->name('ajk.suspend');
    Route::post('/ajk/{ajk}/reactivate', [App\Http\Controllers\AjkController::class, 'reactivate'])->middleware('permission:ajk,reactivate')->name('ajk.reactivate');
});

// Asnaf Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/asnaf', [App\Http\Controllers\AsnafController::class, 'index'])->middleware('permission:asnaf,read')->name('asnaf.index');
    Route::get('/asnaf/create', [App\Http\Controllers\AsnafController::class, 'create'])->middleware('permission:asnaf,create')->name('asnaf.create');
    Route::post('/asnaf', [App\Http\Controllers\AsnafController::class, 'store'])->middleware('permission:asnaf,create')->name('asnaf.store');
    Route::get('/asnaf/{asnaf}', [App\Http\Controllers\AsnafController::class, 'show'])->middleware('permission:asnaf,read')->name('asnaf.show');
    Route::get('/asnaf/{asnaf}/edit', [App\Http\Controllers\AsnafController::class, 'edit'])->middleware('permission:asnaf,update')->name('asnaf.edit');
    Route::put('/asnaf/{asnaf}', [App\Http\Controllers\AsnafController::class, 'update'])->middleware('permission:asnaf,update')->name('asnaf.update');
    Route::delete('/asnaf/{asnaf}', [App\Http\Controllers\AsnafController::class, 'destroy'])->middleware('permission:asnaf,delete')->name('asnaf.destroy');
    Route::get('/asnaf-export', [App\Http\Controllers\AsnafController::class, 'export'])->middleware('permission:asnaf,read')->name('asnaf.export');

    // Workflow routes
    Route::post('/asnaf/{asnaf}/approve', [App\Http\Controllers\AsnafController::class, 'approve'])->middleware('permission:asnaf,approve')->name('asnaf.approve');
    Route::post('/asnaf/{asnaf}/reject', [App\Http\Controllers\AsnafController::class, 'reject'])->middleware('permission:asnaf,reject')->name('asnaf.reject');
    Route::post('/asnaf/{asnaf}/suspend', [App\Http\Controllers\AsnafController::class, 'suspend'])->middleware('permission:asnaf,suspend')->name('asnaf.suspend');
    Route::post('/asnaf/{asnaf}/reactivate', [App\Http\Controllers\AsnafController::class, 'reactivate'])->middleware('permission:asnaf,reactivate')->name('asnaf.reactivate');
});

// Tetapan Asnaf Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tetapan-asnaf', [App\Http\Controllers\TetapanAsnafController::class, 'index'])->name('tetapan-asnaf.index');
    Route::post('/tetapan-asnaf', [App\Http\Controllers\TetapanAsnafController::class, 'update'])->name('tetapan-asnaf.update');
    
    // Kategori Asnaf Routes
    Route::post('/tetapan-asnaf/kategori', [App\Http\Controllers\TetapanAsnafController::class, 'kategoriStore'])->middleware('permission:asnaf,create')->name('tetapan-asnaf.kategori.store');
    Route::put('/tetapan-asnaf/kategori/{id}', [App\Http\Controllers\TetapanAsnafController::class, 'kategoriUpdate'])->middleware('permission:asnaf,update')->name('tetapan-asnaf.kategori.update');
    Route::delete('/tetapan-asnaf/kategori/{id}', [App\Http\Controllers\TetapanAsnafController::class, 'kategoriDestroy'])->middleware('permission:asnaf,delete')->name('tetapan-asnaf.kategori.destroy');
});

// Permohonan Zakat Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/permohonan-zakat', [App\Http\Controllers\PermohonanZakatController::class, 'index'])->middleware('permission:asnaf,read')->name('permohonan-zakat.index');
    Route::get('/permohonan-zakat/export', [App\Http\Controllers\PermohonanZakatController::class, 'export'])->middleware('permission:asnaf,read')->name('permohonan-zakat.export');
    Route::get('/permohonan-zakat/create', [App\Http\Controllers\PermohonanZakatController::class, 'create'])->middleware('permission:asnaf,create')->name('permohonan-zakat.create');
    Route::post('/permohonan-zakat', [App\Http\Controllers\PermohonanZakatController::class, 'store'])->middleware('permission:asnaf,create')->name('permohonan-zakat.store');
    Route::get('/permohonan-zakat/{permohonanZakat}', [App\Http\Controllers\PermohonanZakatController::class, 'show'])->middleware('permission:asnaf,read')->name('permohonan-zakat.show');
    Route::get('/permohonan-zakat/{permohonanZakat}/edit', [App\Http\Controllers\PermohonanZakatController::class, 'edit'])->middleware('permission:asnaf,update')->name('permohonan-zakat.edit');
    Route::put('/permohonan-zakat/{permohonanZakat}', [App\Http\Controllers\PermohonanZakatController::class, 'update'])->middleware('permission:asnaf,update')->name('permohonan-zakat.update');
    Route::delete('/permohonan-zakat/{permohonanZakat}', [App\Http\Controllers\PermohonanZakatController::class, 'destroy'])->middleware('permission:asnaf,delete')->name('permohonan-zakat.destroy');
    Route::post('/permohonan-zakat/{permohonanZakat}/approve', [App\Http\Controllers\PermohonanZakatController::class, 'approve'])->middleware('permission:asnaf,update')->name('permohonan-zakat.approve');
    Route::post('/permohonan-zakat/{permohonanZakat}/reject', [App\Http\Controllers\PermohonanZakatController::class, 'reject'])->middleware('permission:asnaf,update')->name('permohonan-zakat.reject');
});

// Agihan Zakat Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/agihan-zakat', [App\Http\Controllers\AgihanZakatController::class, 'index'])->middleware('permission:asnaf,read')->name('agihan-zakat.index');
    Route::get('/agihan-zakat/export', [App\Http\Controllers\AgihanZakatController::class, 'export'])->middleware('permission:asnaf,read')->name('agihan-zakat.export');
    Route::get('/agihan-zakat/laporan', [App\Http\Controllers\AgihanZakatController::class, 'laporan'])->middleware('permission:asnaf,read')->name('agihan-zakat.laporan');
    Route::get('/agihan-zakat/laporan/export', [App\Http\Controllers\AgihanZakatController::class, 'laporanExport'])->middleware('permission:asnaf,read')->name('agihan-zakat.laporan.export');
    Route::get('/agihan-zakat/create', [App\Http\Controllers\AgihanZakatController::class, 'create'])->middleware('permission:asnaf,create')->name('agihan-zakat.create');
    Route::post('/agihan-zakat', [App\Http\Controllers\AgihanZakatController::class, 'store'])->middleware('permission:asnaf,create')->name('agihan-zakat.store');
    Route::get('/agihan-zakat/{agihanZakat}', [App\Http\Controllers\AgihanZakatController::class, 'show'])->middleware('permission:asnaf,read')->name('agihan-zakat.show');
    Route::get('/agihan-zakat/{agihanZakat}/edit', [App\Http\Controllers\AgihanZakatController::class, 'edit'])->middleware('permission:asnaf,update')->name('agihan-zakat.edit');
    Route::put('/agihan-zakat/{agihanZakat}', [App\Http\Controllers\AgihanZakatController::class, 'update'])->middleware('permission:asnaf,update')->name('agihan-zakat.update');
    Route::delete('/agihan-zakat/{agihanZakat}', [App\Http\Controllers\AgihanZakatController::class, 'destroy'])->middleware('permission:asnaf,delete')->name('agihan-zakat.destroy');
    Route::post('/agihan-zakat/{agihanZakat}/bayar', [App\Http\Controllers\AgihanZakatController::class, 'bayar'])->middleware('permission:asnaf,update')->name('agihan-zakat.bayar');
    Route::post('/agihan-zakat/{agihanZakat}/batal', [App\Http\Controllers\AgihanZakatController::class, 'batal'])->middleware('permission:asnaf,update')->name('agihan-zakat.batal');
});

// Pengurusan Dokumen Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Document management routes
    Route::get('/documents', [DocumentController::class, 'index'])->middleware('permission:documents,read')->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->middleware('permission:documents,create')->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->middleware('permission:documents,create')->name('documents.store');

    // Google Drive style routes with hash tokens
    Route::get('/documents/d/{token}', [DocumentController::class, 'showByToken'])->middleware('permission:documents,read')->name('documents.show');
    Route::get('/documents/folders/{token}', [DocumentController::class, 'folderByToken'])->middleware('permission:documents,read')->name('documents.folder');

    // Token-based file serving for shared documents
    Route::get('/documents/d/{token}/preview', [DocumentController::class, 'previewByToken'])->middleware('permission:documents,read')->name('documents.preview-by-token');
    Route::get('/documents/d/{token}/download', [DocumentController::class, 'downloadByToken'])->middleware('permission:documents,read')->name('documents.download-by-token');

    // Legacy routes (keep for backward compatibility)
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->middleware('permission:documents,update')->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->middleware('permission:documents,update')->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->middleware('permission:documents,delete')->name('documents.destroy');

    // Document actions
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::post('/documents/{documentIdentifier}/toggle-star', [DocumentController::class, 'toggleStar'])->name('documents.toggle-star');
    Route::post('/documents/{document}/share', [DocumentController::class, 'share'])->middleware('permission:documents,share')->name('documents.share');

    // New document actions
    Route::post('/documents/{documentIdentifier}/move', [DocumentController::class, 'move'])->middleware('permission:documents,update')->name('documents.move');
    Route::post('/documents/{documentIdentifier}/favorites', [DocumentController::class, 'addToFavorites'])->name('documents.favorites');

    // Trash and spam actions
    Route::post('/documents/{documentIdentifier}/trash', [DocumentController::class, 'moveToTrash'])->middleware('permission:documents,delete')->name('documents.trash');
    Route::post('/documents/{documentIdentifier}/spam', [DocumentController::class, 'moveToSpam'])->middleware('permission:documents,delete')->name('documents.spam');
    Route::post('/documents/{documentIdentifier}/restore', [DocumentController::class, 'restore'])->middleware('permission:documents,update')->name('documents.restore');
    Route::delete('/documents/{documentIdentifier}/force-delete', [DocumentController::class, 'forceDelete'])->middleware('permission:documents,delete')->name('documents.force-delete');

    // Folder management routes
    Route::post('/document-folders', [DocumentFolderController::class, 'store'])->middleware('permission:documents,create')->name('document-folders.store');
    Route::put('/document-folders/{folder}', [DocumentFolderController::class, 'update'])->middleware('permission:documents,update')->name('document-folders.update');
    Route::delete('/document-folders/{folder}', [DocumentFolderController::class, 'destroy'])->middleware('permission:documents,delete')->name('document-folders.destroy');
    Route::post('/document-folders/{folderIdentifier}/toggle-star', [DocumentFolderController::class, 'toggleStar'])->name('document-folders.toggle-star');
    Route::post('/document-folders/{folderIdentifier}/color', [DocumentFolderController::class, 'updateColor'])->middleware('permission:documents,update')->name('document-folders.color');

    // New folder actions
    Route::post('/document-folders/{folderIdentifier}/move', [DocumentFolderController::class, 'move'])->middleware('permission:documents,update')->name('document-folders.move');
    Route::post('/document-folders/{folderIdentifier}/favorites', [DocumentFolderController::class, 'addToFavorites'])->name('document-folders.favorites');
    Route::get('/folders/list', [DocumentFolderController::class, 'listForMove'])->name('folders.list');

    // Document Sharing Routes (moved from api.php for session authentication)
    Route::prefix('api/documents/sharing')->group(function () {
        // Get sharing data for document or folder
        Route::get('{type}/{id}', [DocumentSharingController::class, 'getSharingData']);

        // Share with masjid
        Route::post('share', [DocumentSharingController::class, 'shareWithMasjid']);

        // Unshare with masjid
        Route::post('unshare', [DocumentSharingController::class, 'unshareWithMasjid']);

        // Update permission level for specific masjid
        Route::post('update-permission', [DocumentSharingController::class, 'updatePermission']);

        // Update access level (restricted/anyone with link)
        Route::post('access-level', [DocumentSharingController::class, 'updateAccessLevel']);

        // Update access level
        Route::post('access-level', [DocumentSharingController::class, 'updateAccessLevel']);

        // Get share link
        Route::get('link/{type}/{id}', [DocumentSharingController::class, 'getShareLink']);

        // Request access to restricted document
        Route::post('request-access', [DocumentSharingController::class, 'requestAccess']);
    });

});

// Bantuan & Sokongan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bantuan/panduan-pengguna', function () {
        return view('bantuan.panduan-pengguna');
    })->name('bantuan.panduan-pengguna');

    Route::get('/bantuan/faq', [App\Http\Controllers\FAQController::class, 'index'])->name('bantuan.faq');

    // Support Routes - Different interfaces for different roles
    Route::get('/bantuan/hubungi-sokongan', [App\Http\Controllers\SupportController::class, 'hubungiSokongan'])->name('bantuan.hubungi-sokongan');

    // Support System Routes (Super Admin only)
    // Note: Role checking is done in the controller methods using isSuperAdmin()
    Route::get('/support/dashboard', [App\Http\Controllers\SupportController::class, 'dashboard'])->name('support.dashboard');
    Route::get('/support/ticket/{ticketId}', [App\Http\Controllers\SupportController::class, 'ticketDetail'])->name('support.ticket.detail');

    // API Routes for Support System
    Route::get('/api/support/notifications', [App\Http\Controllers\SupportController::class, 'getNotifications'])->name('api.support.notifications');
    Route::post('/api/support/notifications/mark-all-read', [App\Http\Controllers\SupportController::class, 'markAllNotificationsRead'])->name('api.support.notifications.mark-all-read');
});



// Public share routes (no authentication required)
Route::get('/share/{token}', [DocumentSharingController::class, 'viewPublicShare'])->name('public.share');
Route::get('/share/{token}/download/{itemToken}', [DocumentSharingController::class, 'downloadPublicShare'])->name('public.share.download');

// ============================================
// KEBAJIKAN MODULE ROUTES
// ============================================

// Program Kebajikan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('program-kebajikan', [App\Http\Controllers\ProgramKebajikanController::class, 'index'])
        ->name('program-kebajikan.index')
        ->middleware('permission:program_kebajikan,read');
    Route::get('program-kebajikan/create', [App\Http\Controllers\ProgramKebajikanController::class, 'create'])
        ->name('program-kebajikan.create')
        ->middleware('permission:program_kebajikan,create');
    Route::post('program-kebajikan', [App\Http\Controllers\ProgramKebajikanController::class, 'store'])
        ->name('program-kebajikan.store')
        ->middleware('permission:program_kebajikan,create');
    Route::get('program-kebajikan/{programKebajikan}', [App\Http\Controllers\ProgramKebajikanController::class, 'show'])
        ->name('program-kebajikan.show')
        ->middleware('permission:program_kebajikan,read');
    Route::get('program-kebajikan/{programKebajikan}/edit', [App\Http\Controllers\ProgramKebajikanController::class, 'edit'])
        ->name('program-kebajikan.edit')
        ->middleware('permission:program_kebajikan,update');
    Route::put('program-kebajikan/{programKebajikan}', [App\Http\Controllers\ProgramKebajikanController::class, 'update'])
        ->name('program-kebajikan.update')
        ->middleware('permission:program_kebajikan,update');
    Route::delete('program-kebajikan/{programKebajikan}', [App\Http\Controllers\ProgramKebajikanController::class, 'destroy'])
        ->name('program-kebajikan.destroy')
        ->middleware('permission:program_kebajikan,delete');
});

// Penerima Bantuan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('penerima-bantuan', [App\Http\Controllers\PenerimaBantuanController::class, 'index'])
        ->name('penerima-bantuan.index')
        ->middleware('permission:penerima_bantuan,read');
    Route::get('penerima-bantuan/create', [App\Http\Controllers\PenerimaBantuanController::class, 'create'])
        ->name('penerima-bantuan.create')
        ->middleware('permission:penerima_bantuan,create');
    Route::post('penerima-bantuan', [App\Http\Controllers\PenerimaBantuanController::class, 'store'])
        ->name('penerima-bantuan.store')
        ->middleware('permission:penerima_bantuan,create');
    Route::get('penerima-bantuan/{penerimaBantuan}', [App\Http\Controllers\PenerimaBantuanController::class, 'show'])
        ->name('penerima-bantuan.show')
        ->middleware('permission:penerima_bantuan,read');
    Route::get('penerima-bantuan/{penerimaBantuan}/edit', [App\Http\Controllers\PenerimaBantuanController::class, 'edit'])
        ->name('penerima-bantuan.edit')
        ->middleware('permission:penerima_bantuan,update');
    Route::put('penerima-bantuan/{penerimaBantuan}', [App\Http\Controllers\PenerimaBantuanController::class, 'update'])
        ->name('penerima-bantuan.update')
        ->middleware('permission:penerima_bantuan,update');
    Route::delete('penerima-bantuan/{penerimaBantuan}', [App\Http\Controllers\PenerimaBantuanController::class, 'destroy'])
        ->name('penerima-bantuan.destroy')
        ->middleware('permission:penerima_bantuan,delete');
});

// Permohonan Bantuan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('permohonan-bantuan', [App\Http\Controllers\PermohonanBantuanController::class, 'index'])
        ->name('permohonan-bantuan.index')
        ->middleware('permission:permohonan_bantuan,read');
    Route::get('permohonan-bantuan/create', [App\Http\Controllers\PermohonanBantuanController::class, 'create'])
        ->name('permohonan-bantuan.create')
        ->middleware('permission:permohonan_bantuan,create');
    Route::post('permohonan-bantuan', [App\Http\Controllers\PermohonanBantuanController::class, 'store'])
        ->name('permohonan-bantuan.store')
        ->middleware('permission:permohonan_bantuan,create');
    Route::get('permohonan-bantuan/{permohonanBantuan}', [App\Http\Controllers\PermohonanBantuanController::class, 'show'])
        ->name('permohonan-bantuan.show')
        ->middleware('permission:permohonan_bantuan,read');
    Route::get('permohonan-bantuan/{permohonanBantuan}/edit', [App\Http\Controllers\PermohonanBantuanController::class, 'edit'])
        ->name('permohonan-bantuan.edit')
        ->middleware('permission:permohonan_bantuan,update');
    Route::put('permohonan-bantuan/{permohonanBantuan}', [App\Http\Controllers\PermohonanBantuanController::class, 'update'])
        ->name('permohonan-bantuan.update')
        ->middleware('permission:permohonan_bantuan,update');
    Route::delete('permohonan-bantuan/{permohonanBantuan}', [App\Http\Controllers\PermohonanBantuanController::class, 'destroy'])
        ->name('permohonan-bantuan.destroy')
        ->middleware('permission:permohonan_bantuan,delete');

    // Workflow actions
    Route::post('permohonan-bantuan/{id}/semak', [App\Http\Controllers\PermohonanBantuanController::class, 'semak'])
        ->name('permohonan-bantuan.semak')
        ->middleware('permission:permohonan_bantuan,approve');

    Route::post('permohonan-bantuan/{id}/lawatan', [App\Http\Controllers\PermohonanBantuanController::class, 'lawatan'])
        ->name('permohonan-bantuan.lawatan')
        ->middleware('permission:permohonan_bantuan,approve');

    Route::post('permohonan-bantuan/{id}/lulus', [App\Http\Controllers\PermohonanBantuanController::class, 'lulus'])
        ->name('permohonan-bantuan.lulus')
        ->middleware('permission:permohonan_bantuan,approve');

    Route::post('permohonan-bantuan/{id}/tolak', [App\Http\Controllers\PermohonanBantuanController::class, 'tolak'])
        ->name('permohonan-bantuan.tolak')
        ->middleware('permission:permohonan_bantuan,reject');

    Route::post('permohonan-bantuan/{id}/batal', [App\Http\Controllers\PermohonanBantuanController::class, 'batal'])
        ->name('permohonan-bantuan.batal')
        ->middleware('permission:permohonan_bantuan,delete');
});

// Pembayaran Bantuan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pembayaran-bantuan', [App\Http\Controllers\PembayaranBantuanController::class, 'index'])
        ->name('pembayaran-bantuan.index')
        ->middleware('permission:pembayaran_bantuan,read');
    Route::get('pembayaran-bantuan/create', [App\Http\Controllers\PembayaranBantuanController::class, 'create'])
        ->name('pembayaran-bantuan.create')
        ->middleware('permission:pembayaran_bantuan,create');
    Route::post('pembayaran-bantuan', [App\Http\Controllers\PembayaranBantuanController::class, 'store'])
        ->name('pembayaran-bantuan.store')
        ->middleware('permission:pembayaran_bantuan,create');
    Route::get('pembayaran-bantuan/{pembayaranBantuan}', [App\Http\Controllers\PembayaranBantuanController::class, 'show'])
        ->name('pembayaran-bantuan.show')
        ->middleware('permission:pembayaran_bantuan,read');
    Route::get('pembayaran-bantuan/{pembayaranBantuan}/edit', [App\Http\Controllers\PembayaranBantuanController::class, 'edit'])
        ->name('pembayaran-bantuan.edit')
        ->middleware('permission:pembayaran_bantuan,update');
    Route::put('pembayaran-bantuan/{pembayaranBantuan}', [App\Http\Controllers\PembayaranBantuanController::class, 'update'])
        ->name('pembayaran-bantuan.update')
        ->middleware('permission:pembayaran_bantuan,update');
    Route::delete('pembayaran-bantuan/{pembayaranBantuan}', [App\Http\Controllers\PembayaranBantuanController::class, 'destroy'])
        ->name('pembayaran-bantuan.destroy')
        ->middleware('permission:pembayaran_bantuan,delete');

    // Workflow actions
    Route::post('pembayaran-bantuan/{id}/sahkan', [App\Http\Controllers\PembayaranBantuanController::class, 'sahkan'])
        ->name('pembayaran-bantuan.sahkan')
        ->middleware('permission:pembayaran_bantuan,update');

    Route::post('pembayaran-bantuan/{id}/batal', [App\Http\Controllers\PembayaranBantuanController::class, 'batal'])
        ->name('pembayaran-bantuan.batal')
        ->middleware('permission:pembayaran_bantuan,delete');
});

// Laporan Kebajikan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-kebajikan', [App\Http\Controllers\LaporanKebajikanController::class, 'index'])
        ->name('laporan-kebajikan.index')
        ->middleware('permission:laporan_kebajikan,read');

    Route::get('laporan-kebajikan/pdf', [App\Http\Controllers\LaporanKebajikanController::class, 'pdf'])
        ->name('laporan-kebajikan.pdf')
        ->middleware('permission:laporan_kebajikan,read');

    Route::get('laporan-kebajikan/excel', [App\Http\Controllers\LaporanKebajikanController::class, 'excel'])
        ->name('laporan-kebajikan.excel')
        ->middleware('permission:laporan_kebajikan,read');
});

// Tetapan Kebajikan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('tetapan-kebajikan', [App\Http\Controllers\TetapanKebajikanController::class, 'index'])
        ->name('tetapan-kebajikan.index');

    Route::post('tetapan-kebajikan', [App\Http\Controllers\TetapanKebajikanController::class, 'update'])
        ->name('tetapan-kebajikan.update');

    // Kategori Kebajikan Routes
    Route::post('tetapan-kebajikan/kategori', [App\Http\Controllers\TetapanKebajikanController::class, 'kategoriStore'])
        ->name('tetapan-kebajikan.kategori.store')
        ->middleware('permission:tetapan_kebajikan,update');

    Route::put('tetapan-kebajikan/kategori/{id}', [App\Http\Controllers\TetapanKebajikanController::class, 'kategoriUpdate'])
        ->name('tetapan-kebajikan.kategori.update')
        ->middleware('permission:tetapan_kebajikan,update');

    Route::delete('tetapan-kebajikan/kategori/{id}', [App\Http\Controllers\TetapanKebajikanController::class, 'kategoriDestroy'])
        ->name('tetapan-kebajikan.kategori.destroy')
        ->middleware('permission:tetapan_kebajikan,update');
});

// ============================================
// KEWANGAN MODULE ROUTES
// ============================================

// Akaun Bank Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('akaun-bank', [App\Http\Controllers\AkaunBankController::class, 'index'])
        ->name('akaun-bank.index')
        ->middleware('permission:akaun_bank,read');
    
    Route::get('akaun-bank/create', [App\Http\Controllers\AkaunBankController::class, 'create'])
        ->name('akaun-bank.create')
        ->middleware('permission:akaun_bank,create');
    
    Route::post('akaun-bank', [App\Http\Controllers\AkaunBankController::class, 'store'])
        ->name('akaun-bank.store')
        ->middleware('permission:akaun_bank,create');
    
    Route::get('akaun-bank/{akaunBank}', [App\Http\Controllers\AkaunBankController::class, 'show'])
        ->name('akaun-bank.show')
        ->middleware('permission:akaun_bank,read');
    
    Route::get('akaun-bank/{akaunBank}/edit', [App\Http\Controllers\AkaunBankController::class, 'edit'])
        ->name('akaun-bank.edit')
        ->middleware('permission:akaun_bank,update');
    
    Route::put('akaun-bank/{akaunBank}', [App\Http\Controllers\AkaunBankController::class, 'update'])
        ->name('akaun-bank.update')
        ->middleware('permission:akaun_bank,update');
    
    Route::delete('akaun-bank/{akaunBank}', [App\Http\Controllers\AkaunBankController::class, 'destroy'])
        ->name('akaun-bank.destroy')
        ->middleware('permission:akaun_bank,delete');
});

// Transaksi Kewangan Routes (IMPORTANT: Specific routes MUST come before parameterized routes)
Route::middleware(['auth', 'verified'])->group(function () {
    // Index route
    Route::get('transaksi-kewangan', [App\Http\Controllers\TransaksiKewanganController::class, 'index'])
        ->name('transaksi-kewangan.index')
        ->middleware('permission:transaksi_kewangan,read');

    // Specific form routes - MUST come before {transaksiKewangan} parameter route
    Route::get('transaksi-kewangan/tambah-pendapatan', [App\Http\Controllers\TransaksiKewanganController::class, 'createPendapatan'])
        ->name('transaksi-kewangan.create-pendapatan')
        ->middleware('permission:transaksi_kewangan,create');

    Route::get('transaksi-kewangan/tambah-perbelanjaan', [App\Http\Controllers\TransaksiKewanganController::class, 'createPerbelanjaan'])
        ->name('transaksi-kewangan.create-perbelanjaan')
        ->middleware('permission:transaksi_kewangan,create');

    // Kutipan Dana Forms (specific routes)
    Route::get('transaksi-kewangan/kutipan-kariah', [App\Http\Controllers\KutipanDanaController::class, 'kutipanKariah'])
        ->name('transaksi-kewangan.kutipan-kariah')
        ->middleware('permission:transaksi_kewangan,create');

    Route::get('transaksi-kewangan/derma-sumbangan', [App\Http\Controllers\KutipanDanaController::class, 'dermaSumbangan'])
        ->name('transaksi-kewangan.derma-sumbangan')
        ->middleware('permission:transaksi_kewangan,create');

    Route::get('transaksi-kewangan/kutipan-zakat', [App\Http\Controllers\KutipanDanaController::class, 'kutipanZakat'])
        ->name('transaksi-kewangan.kutipan-zakat')
        ->middleware('permission:transaksi_kewangan,create');

    Route::get('transaksi-kewangan/kutipan-lain', [App\Http\Controllers\KutipanDanaController::class, 'kutipanLain'])
        ->name('transaksi-kewangan.kutipan-lain')
        ->middleware('permission:transaksi_kewangan,create');

    // Perbelanjaan Forms (specific routes)
    Route::get('transaksi-kewangan/utiliti-bil', [App\Http\Controllers\PerbelanjaanController::class, 'utilitiBil'])
        ->name('transaksi-kewangan.utiliti-bil')
        ->middleware('permission:transaksi_kewangan,create');

    Route::get('transaksi-kewangan/penyelenggaraan', [App\Http\Controllers\PerbelanjaanController::class, 'penyelenggaraan'])
        ->name('transaksi-kewangan.penyelenggaraan')
        ->middleware('permission:transaksi_kewangan,create');

    Route::get('transaksi-kewangan/gaji-elaun', [App\Http\Controllers\PerbelanjaanController::class, 'gajiElaun'])
        ->name('transaksi-kewangan.gaji-elaun')
        ->middleware('permission:transaksi_kewangan,create');

    Route::get('transaksi-kewangan/perbelanjaan-lain', [App\Http\Controllers\PerbelanjaanController::class, 'perbelanjaanLain'])
        ->name('transaksi-kewangan.perbelanjaan-lain')
        ->middleware('permission:transaksi_kewangan,create');

    // Store route
    Route::post('transaksi-kewangan', [App\Http\Controllers\TransaksiKewanganController::class, 'store'])
        ->name('transaksi-kewangan.store')
        ->middleware('permission:transaksi_kewangan,create');

    // Parameterized routes - MUST come AFTER all specific routes
    Route::get('transaksi-kewangan/{transaksiKewangan}/edit', [App\Http\Controllers\TransaksiKewanganController::class, 'edit'])
        ->name('transaksi-kewangan.edit')
        ->middleware('permission:transaksi_kewangan,update');

    Route::get('transaksi-kewangan/{transaksiKewangan}', [App\Http\Controllers\TransaksiKewanganController::class, 'show'])
        ->name('transaksi-kewangan.show')
        ->middleware('permission:transaksi_kewangan,read');

    Route::put('transaksi-kewangan/{transaksiKewangan}', [App\Http\Controllers\TransaksiKewanganController::class, 'update'])
        ->name('transaksi-kewangan.update')
        ->middleware('permission:transaksi_kewangan,update');

    Route::delete('transaksi-kewangan/{transaksiKewangan}', [App\Http\Controllers\TransaksiKewanganController::class, 'destroy'])
        ->name('transaksi-kewangan.destroy')
        ->middleware('permission:transaksi_kewangan,delete');

    // Store routes for Kutipan Dana & Perbelanjaan (keep original for form submissions)
    Route::post('kutipan-dana', [App\Http\Controllers\KutipanDanaController::class, 'store'])
        ->name('kutipan-dana.store')
        ->middleware('permission:transaksi_kewangan,create');

    Route::post('perbelanjaan', [App\Http\Controllers\PerbelanjaanController::class, 'store'])
        ->name('perbelanjaan.store')
        ->middleware('permission:transaksi_kewangan,create');

    // Show, Edit, Update, Delete routes for Kutipan Dana
    Route::get('kutipan-dana/{kutipanDana}/edit', [App\Http\Controllers\KutipanDanaController::class, 'edit'])
        ->name('kutipan-dana.edit')
        ->middleware('permission:transaksi_kewangan,update');

    Route::get('kutipan-dana/{kutipanDana}', [App\Http\Controllers\KutipanDanaController::class, 'show'])
        ->name('kutipan-dana.show')
        ->middleware('permission:transaksi_kewangan,read');

    Route::put('kutipan-dana/{kutipanDana}', [App\Http\Controllers\KutipanDanaController::class, 'update'])
        ->name('kutipan-dana.update')
        ->middleware('permission:transaksi_kewangan,update');

    Route::delete('kutipan-dana/{kutipanDana}', [App\Http\Controllers\KutipanDanaController::class, 'destroy'])
        ->name('kutipan-dana.destroy')
        ->middleware('permission:transaksi_kewangan,delete');

    // Show, Edit, Update, Delete routes for Perbelanjaan
    Route::get('perbelanjaan/{perbelanjaan}/edit', [App\Http\Controllers\PerbelanjaanController::class, 'edit'])
        ->name('perbelanjaan.edit')
        ->middleware('permission:transaksi_kewangan,update');

    Route::get('perbelanjaan/{perbelanjaan}', [App\Http\Controllers\PerbelanjaanController::class, 'show'])
        ->name('perbelanjaan.show')
        ->middleware('permission:transaksi_kewangan,read');

    Route::put('perbelanjaan/{perbelanjaan}', [App\Http\Controllers\PerbelanjaanController::class, 'update'])
        ->name('perbelanjaan.update')
        ->middleware('permission:transaksi_kewangan,update');

    Route::delete('perbelanjaan/{perbelanjaan}', [App\Http\Controllers\PerbelanjaanController::class, 'destroy'])
        ->name('perbelanjaan.destroy')
        ->middleware('permission:transaksi_kewangan,delete');

    // Workflow actions for Perbelanjaan
    Route::post('perbelanjaan/{id}/approve', [App\Http\Controllers\PerbelanjaanController::class, 'approve'])
        ->name('perbelanjaan.approve')
        ->middleware('permission:transaksi_kewangan,update');

    Route::post('perbelanjaan/{id}/reject', [App\Http\Controllers\PerbelanjaanController::class, 'reject'])
        ->name('perbelanjaan.reject')
        ->middleware('permission:transaksi_kewangan,update');
});

// Laporan Kewangan Routes
// Note: Permission check handled in controller for TAB-level access control
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-kewangan', [App\Http\Controllers\LaporanKewanganController::class, 'index'])
        ->name('laporan-kewangan.index');

    Route::get('laporan-kewangan/pdf', [App\Http\Controllers\LaporanKewanganController::class, 'pdf'])
        ->name('laporan-kewangan.pdf');

    Route::get('laporan-kewangan/excel', [App\Http\Controllers\LaporanKewanganController::class, 'excel'])
        ->name('laporan-kewangan.excel');
});

// Tetapan Kewangan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('tetapan-kewangan', [App\Http\Controllers\TetapanKewanganController::class, 'index'])
        ->name('tetapan-kewangan.index');

    Route::post('tetapan-kewangan', [App\Http\Controllers\TetapanKewanganController::class, 'update'])
        ->name('tetapan-kewangan.update');

    // Kategori Kewangan Routes
    Route::post('tetapan-kewangan/kategori', [App\Http\Controllers\TetapanKewanganController::class, 'kategoriStore'])
        ->name('tetapan-kewangan.kategori.store');

    Route::put('tetapan-kewangan/kategori/{id}', [App\Http\Controllers\TetapanKewanganController::class, 'kategoriUpdate'])
        ->name('tetapan-kewangan.kategori.update');

    Route::delete('tetapan-kewangan/kategori/{id}', [App\Http\Controllers\TetapanKewanganController::class, 'kategoriDestroy'])
        ->name('tetapan-kewangan.kategori.destroy')
        ->middleware('permission:kewangan,delete');
});

// ============================================
// ASET ROUTES
// ============================================

// Laporan Aset Routes
// Note: Permission check handled in controller for TAB-level access control
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-aset', [App\Http\Controllers\LaporanAsetController::class, 'index'])
        ->name('laporan-aset.index');
});

// Pemindahan Aset Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pemindahan-aset', [App\Http\Controllers\PemindahanAsetController::class, 'index'])
        ->name('pemindahan-aset.index')
        ->middleware('permission:pemindahan_aset,read');
    Route::get('pemindahan-aset/create', [App\Http\Controllers\PemindahanAsetController::class, 'create'])
        ->name('pemindahan-aset.create')
        ->middleware('permission:pemindahan_aset,create');
    Route::post('pemindahan-aset', [App\Http\Controllers\PemindahanAsetController::class, 'store'])
        ->name('pemindahan-aset.store')
        ->middleware('permission:pemindahan_aset,create');
    Route::get('pemindahan-aset/{pemindahanAset}', [App\Http\Controllers\PemindahanAsetController::class, 'show'])
        ->name('pemindahan-aset.show')
        ->middleware('permission:pemindahan_aset,read');
    Route::get('pemindahan-aset/{pemindahanAset}/edit', [App\Http\Controllers\PemindahanAsetController::class, 'edit'])
        ->name('pemindahan-aset.edit')
        ->middleware('permission:pemindahan_aset,update');
    Route::put('pemindahan-aset/{pemindahanAset}', [App\Http\Controllers\PemindahanAsetController::class, 'update'])
        ->name('pemindahan-aset.update')
        ->middleware('permission:pemindahan_aset,update');
    Route::delete('pemindahan-aset/{pemindahanAset}', [App\Http\Controllers\PemindahanAsetController::class, 'destroy'])
        ->name('pemindahan-aset.destroy')
        ->middleware('permission:pemindahan_aset,delete');
});

// Kategori Aset Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('kategori-aset', [App\Http\Controllers\KategoriAsetController::class, 'index'])
        ->name('kategori-aset.index')
        ->middleware('permission:kategori_aset,read');
    Route::get('kategori-aset/create', [App\Http\Controllers\KategoriAsetController::class, 'create'])
        ->name('kategori-aset.create')
        ->middleware('permission:kategori_aset,create');
    Route::post('kategori-aset', [App\Http\Controllers\KategoriAsetController::class, 'store'])
        ->name('kategori-aset.store')
        ->middleware('permission:kategori_aset,create');
    Route::get('kategori-aset/{kategoriAset}', [App\Http\Controllers\KategoriAsetController::class, 'show'])
        ->name('kategori-aset.show')
        ->middleware('permission:kategori_aset,read');
    Route::get('kategori-aset/{kategoriAset}/edit', [App\Http\Controllers\KategoriAsetController::class, 'edit'])
        ->name('kategori-aset.edit')
        ->middleware('permission:kategori_aset,update');
    Route::put('kategori-aset/{kategoriAset}', [App\Http\Controllers\KategoriAsetController::class, 'update'])
        ->name('kategori-aset.update')
        ->middleware('permission:kategori_aset,update');
    Route::delete('kategori-aset/{kategoriAset}', [App\Http\Controllers\KategoriAsetController::class, 'destroy'])
        ->name('kategori-aset.destroy')
        ->middleware('permission:kategori_aset,delete');
});

// Senarai Aset Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('senarai-aset', [App\Http\Controllers\SenariAsetController::class, 'index'])
        ->name('senarai-aset.index')
        ->middleware('permission:senarai_aset,read');
    Route::get('senarai-aset/create', [App\Http\Controllers\SenariAsetController::class, 'create'])
        ->name('senarai-aset.create')
        ->middleware('permission:senarai_aset,create');
    Route::post('senarai-aset', [App\Http\Controllers\SenariAsetController::class, 'store'])
        ->name('senarai-aset.store')
        ->middleware('permission:senarai_aset,create');
    Route::get('senarai-aset/{senariAset}', [App\Http\Controllers\SenariAsetController::class, 'show'])
        ->name('senarai-aset.show')
        ->middleware('permission:senarai_aset,read');
    Route::get('senarai-aset/{senariAset}/edit', [App\Http\Controllers\SenariAsetController::class, 'edit'])
        ->name('senarai-aset.edit')
        ->middleware('permission:senarai_aset,update');
    Route::put('senarai-aset/{senariAset}', [App\Http\Controllers\SenariAsetController::class, 'update'])
        ->name('senarai-aset.update')
        ->middleware('permission:senarai_aset,update');
    Route::delete('senarai-aset/{senariAset}', [App\Http\Controllers\SenariAsetController::class, 'destroy'])
        ->name('senarai-aset.destroy')
        ->middleware('permission:senarai_aset,delete');
});

// Pergerakan Aset Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pergerakan-aset', [App\Http\Controllers\PergerakanAsetController::class, 'index'])
        ->name('pergerakan-aset.index')
        ->middleware('permission:pergerakan_aset,read');
    Route::get('pergerakan-aset/create', [App\Http\Controllers\PergerakanAsetController::class, 'create'])
        ->name('pergerakan-aset.create')
        ->middleware('permission:pergerakan_aset,create');
    Route::post('pergerakan-aset', [App\Http\Controllers\PergerakanAsetController::class, 'store'])
        ->name('pergerakan-aset.store')
        ->middleware('permission:pergerakan_aset,create');
    Route::get('pergerakan-aset/{pergerakanAset}', [App\Http\Controllers\PergerakanAsetController::class, 'show'])
        ->name('pergerakan-aset.show')
        ->middleware('permission:pergerakan_aset,read');
    Route::get('pergerakan-aset/{pergerakanAset}/edit', [App\Http\Controllers\PergerakanAsetController::class, 'edit'])
        ->name('pergerakan-aset.edit')
        ->middleware('permission:pergerakan_aset,update');
    Route::put('pergerakan-aset/{pergerakanAset}', [App\Http\Controllers\PergerakanAsetController::class, 'update'])
        ->name('pergerakan-aset.update')
        ->middleware('permission:pergerakan_aset,update');
    Route::delete('pergerakan-aset/{pergerakanAset}', [App\Http\Controllers\PergerakanAsetController::class, 'destroy'])
        ->name('pergerakan-aset.destroy')
        ->middleware('permission:pergerakan_aset,delete');
    
    // Workflow Actions
    Route::post('pergerakan-aset/{id}/lulus', [App\Http\Controllers\PergerakanAsetController::class, 'lulus'])
        ->name('pergerakan-aset.lulus')
        ->middleware('permission:pergerakan_aset,update');
    Route::post('pergerakan-aset/{id}/pulang', [App\Http\Controllers\PergerakanAsetController::class, 'pulang'])
        ->name('pergerakan-aset.pulang')
        ->middleware('permission:pergerakan_aset,update');
    Route::post('pergerakan-aset/{id}/lewat', [App\Http\Controllers\PergerakanAsetController::class, 'lewat'])
        ->name('pergerakan-aset.lewat')
        ->middleware('permission:pergerakan_aset,update');
    Route::post('pergerakan-aset/{id}/hilang', [App\Http\Controllers\PergerakanAsetController::class, 'hilang'])
        ->name('pergerakan-aset.hilang')
        ->middleware('permission:pergerakan_aset,update');
    
    // Partial Return Routes
    Route::post('pergerakan-aset/{id}/pulang-sebahagian', [App\Http\Controllers\PergerakanAsetController::class, 'pulangSebahagian'])
        ->name('pergerakan-aset.pulang-sebahagian')
        ->middleware('permission:pergerakan_aset,update');
    Route::get('pergerakan-aset/{id}/return-stats', [App\Http\Controllers\PergerakanAsetController::class, 'getReturnStats'])
        ->name('pergerakan-aset.return-stats')
        ->middleware('permission:aset,read');
});

// ============================================
// OPERASI MODULE - FASILITI & TEMPAHAN
// ============================================

// Senarai Fasiliti Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('senarai-fasiliti', [App\Http\Controllers\SenariFasilitiController::class, 'index'])
        ->name('senarai-fasiliti.index')
        ->middleware('permission:senarai_fasiliti,read');
    Route::get('senarai-fasiliti/create', [App\Http\Controllers\SenariFasilitiController::class, 'create'])
        ->name('senarai-fasiliti.create')
        ->middleware('permission:senarai_fasiliti,create');
    Route::post('senarai-fasiliti', [App\Http\Controllers\SenariFasilitiController::class, 'store'])
        ->name('senarai-fasiliti.store')
        ->middleware('permission:senarai_fasiliti,create');
    Route::get('senarai-fasiliti/{senarai_fasiliti}', [App\Http\Controllers\SenariFasilitiController::class, 'show'])
        ->name('senarai-fasiliti.show')
        ->middleware('permission:senarai_fasiliti,read');
    Route::get('senarai-fasiliti/{senarai_fasiliti}/edit', [App\Http\Controllers\SenariFasilitiController::class, 'edit'])
        ->name('senarai-fasiliti.edit')
        ->middleware('permission:senarai_fasiliti,update');
    Route::put('senarai-fasiliti/{senarai_fasiliti}', [App\Http\Controllers\SenariFasilitiController::class, 'update'])
        ->name('senarai-fasiliti.update')
        ->middleware('permission:senarai_fasiliti,update');
    Route::delete('senarai-fasiliti/{senarai_fasiliti}', [App\Http\Controllers\SenariFasilitiController::class, 'destroy'])
        ->name('senarai-fasiliti.destroy')
        ->middleware('permission:senarai_fasiliti,delete');
});

// Tempahan Fasiliti Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('tempahan-fasiliti', [App\Http\Controllers\TempahanFasilitiController::class, 'index'])
        ->name('tempahan-fasiliti.index')
        ->middleware('permission:tempahan_fasiliti,read');
    Route::get('tempahan-fasiliti/create', [App\Http\Controllers\TempahanFasilitiController::class, 'create'])
        ->name('tempahan-fasiliti.create')
        ->middleware('permission:tempahan_fasiliti,create');
    Route::post('tempahan-fasiliti', [App\Http\Controllers\TempahanFasilitiController::class, 'store'])
        ->name('tempahan-fasiliti.store')
        ->middleware('permission:tempahan_fasiliti,create');
    Route::get('tempahan-fasiliti/{tempahan_fasiliti}', [App\Http\Controllers\TempahanFasilitiController::class, 'show'])
        ->name('tempahan-fasiliti.show')
        ->middleware('permission:tempahan_fasiliti,read');
    Route::get('tempahan-fasiliti/{tempahan_fasiliti}/edit', [App\Http\Controllers\TempahanFasilitiController::class, 'edit'])
        ->name('tempahan-fasiliti.edit')
        ->middleware('permission:tempahan_fasiliti,update');
    Route::put('tempahan-fasiliti/{tempahan_fasiliti}', [App\Http\Controllers\TempahanFasilitiController::class, 'update'])
        ->name('tempahan-fasiliti.update')
        ->middleware('permission:tempahan_fasiliti,update');
    Route::delete('tempahan-fasiliti/{tempahan_fasiliti}', [App\Http\Controllers\TempahanFasilitiController::class, 'destroy'])
        ->name('tempahan-fasiliti.destroy')
        ->middleware('permission:tempahan_fasiliti,delete');
    
    // AJAX Availability Check
    Route::get('tempahan-fasiliti/check-availability', [App\Http\Controllers\TempahanFasilitiController::class, 'checkAvailability'])
        ->name('tempahan-fasiliti.check-availability')
        ->middleware('permission:tempahan_fasiliti,read');
    
    // Workflow Actions
    Route::post('tempahan-fasiliti/{id}/semak', [App\Http\Controllers\TempahanFasilitiController::class, 'semak'])
        ->name('tempahan-fasiliti.semak')
        ->middleware('permission:tempahan_fasiliti,update');
    Route::post('tempahan-fasiliti/{id}/lulus', [App\Http\Controllers\TempahanFasilitiController::class, 'lulus'])
        ->name('tempahan-fasiliti.lulus')
        ->middleware('permission:tempahan_fasiliti,approve');
    Route::post('tempahan-fasiliti/{id}/tolak', [App\Http\Controllers\TempahanFasilitiController::class, 'tolak'])
        ->name('tempahan-fasiliti.tolak')
        ->middleware('permission:tempahan_fasiliti,update');
    Route::post('tempahan-fasiliti/{id}/batal', [App\Http\Controllers\TempahanFasilitiController::class, 'batal'])
        ->name('tempahan-fasiliti.batal')
        ->middleware('permission:tempahan_fasiliti,delete');
    Route::post('tempahan-fasiliti/{id}/selesai', [App\Http\Controllers\TempahanFasilitiController::class, 'selesai'])
        ->name('tempahan-fasiliti.selesai')
        ->middleware('permission:tempahan_fasiliti,update');
    Route::post('tempahan-fasiliti/{id}/pulang', [App\Http\Controllers\TempahanFasilitiController::class, 'pulang'])
        ->name('tempahan-fasiliti.pulang')
        ->middleware('permission:tempahan_fasiliti,update');
    Route::get('tempahan-fasiliti/{id}/return-status', [App\Http\Controllers\TempahanFasilitiController::class, 'getReturnStatus'])
        ->name('tempahan-fasiliti.return-status')
        ->middleware('permission:tempahan_fasiliti,read');
    Route::post('tempahan-fasiliti/{tempahan_id}/item/{item_id}/batal', [App\Http\Controllers\TempahanFasilitiController::class, 'batalItem'])
        ->name('tempahan-fasiliti.batal-item')
        ->middleware('permission:tempahan_fasiliti,delete');
});

// Pembayaran Sewa Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pembayaran-sewa', [App\Http\Controllers\PembayaranSewaController::class, 'index'])
        ->name('pembayaran-sewa.index')
        ->middleware('permission:pembayaran_sewa,read');
    Route::get('pembayaran-sewa/create', [App\Http\Controllers\PembayaranSewaController::class, 'create'])
        ->name('pembayaran-sewa.create')
        ->middleware('permission:pembayaran_sewa,create');
    Route::post('pembayaran-sewa', [App\Http\Controllers\PembayaranSewaController::class, 'store'])
        ->name('pembayaran-sewa.store')
        ->middleware('permission:pembayaran_sewa,create');
    Route::get('pembayaran-sewa/{pembayaran_sewa}', [App\Http\Controllers\PembayaranSewaController::class, 'show'])
        ->name('pembayaran-sewa.show')
        ->middleware('permission:pembayaran_sewa,read');
    Route::get('pembayaran-sewa/{pembayaran_sewa}/edit', [App\Http\Controllers\PembayaranSewaController::class, 'edit'])
        ->name('pembayaran-sewa.edit')
        ->middleware('permission:pembayaran_sewa,update');
    Route::put('pembayaran-sewa/{pembayaran_sewa}', [App\Http\Controllers\PembayaranSewaController::class, 'update'])
        ->name('pembayaran-sewa.update')
        ->middleware('permission:pembayaran_sewa,update');
    Route::delete('pembayaran-sewa/{pembayaran_sewa}', [App\Http\Controllers\PembayaranSewaController::class, 'destroy'])
        ->name('pembayaran-sewa.destroy')
        ->middleware('permission:pembayaran_sewa,delete');
});

// Laporan Tempahan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-tempahan', [App\Http\Controllers\LaporanTempahanController::class, 'index'])
        ->name('laporan-tempahan.index')
        ->middleware('permission:laporan_tempahan,read');
    Route::get('laporan-tempahan/pdf', [App\Http\Controllers\LaporanTempahanController::class, 'pdf'])
        ->name('laporan-tempahan.pdf')
        ->middleware('permission:laporan_tempahan,read');
    Route::get('laporan-tempahan/excel', [App\Http\Controllers\LaporanTempahanController::class, 'excel'])
        ->name('laporan-tempahan.excel')
        ->middleware('permission:laporan_tempahan,read');
});

// Profile routes commented out - ProfileController not implemented yet
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php'; // This line was commented out

// ============================================
// PENYELENGGARAAN MODULE
// ============================================

// Jadual Penyelenggaraan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('jadual-penyelenggaraan', [App\Http\Controllers\JadualPenyelenggaraanController::class, 'index'])
        ->name('jadual-penyelenggaraan.index')
        ->middleware('permission:jadual_penyelenggaraan,read');
    Route::get('jadual-penyelenggaraan/create', [App\Http\Controllers\JadualPenyelenggaraanController::class, 'create'])
        ->name('jadual-penyelenggaraan.create')
        ->middleware('permission:jadual_penyelenggaraan,create');
    Route::post('jadual-penyelenggaraan', [App\Http\Controllers\JadualPenyelenggaraanController::class, 'store'])
        ->name('jadual-penyelenggaraan.store')
        ->middleware('permission:jadual_penyelenggaraan,create');
    Route::get('jadual-penyelenggaraan/{jadualPenyelenggaraan}', [App\Http\Controllers\JadualPenyelenggaraanController::class, 'show'])
        ->name('jadual-penyelenggaraan.show')
        ->middleware('permission:jadual_penyelenggaraan,read');
    Route::get('jadual-penyelenggaraan/{jadualPenyelenggaraan}/edit', [App\Http\Controllers\JadualPenyelenggaraanController::class, 'edit'])
        ->name('jadual-penyelenggaraan.edit')
        ->middleware('permission:jadual_penyelenggaraan,update');
    Route::put('jadual-penyelenggaraan/{jadualPenyelenggaraan}', [App\Http\Controllers\JadualPenyelenggaraanController::class, 'update'])
        ->name('jadual-penyelenggaraan.update')
        ->middleware('permission:jadual_penyelenggaraan,update');
    Route::delete('jadual-penyelenggaraan/{jadualPenyelenggaraan}', [App\Http\Controllers\JadualPenyelenggaraanController::class, 'destroy'])
        ->name('jadual-penyelenggaraan.destroy')
        ->middleware('permission:jadual_penyelenggaraan,delete');
});

// Kerja Penyelenggaraan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('kerja-penyelenggaraan', [App\Http\Controllers\KerjaPenyelenggaraanController::class, 'index'])
        ->name('kerja-penyelenggaraan.index')
        ->middleware('permission:kerja_penyelenggaraan,read');
    Route::get('kerja-penyelenggaraan/create', [App\Http\Controllers\KerjaPenyelenggaraanController::class, 'create'])
        ->name('kerja-penyelenggaraan.create')
        ->middleware('permission:kerja_penyelenggaraan,create');
    Route::post('kerja-penyelenggaraan', [App\Http\Controllers\KerjaPenyelenggaraanController::class, 'store'])
        ->name('kerja-penyelenggaraan.store')
        ->middleware('permission:kerja_penyelenggaraan,create');
    Route::get('kerja-penyelenggaraan/{kerjaPenyelenggaraan}', [App\Http\Controllers\KerjaPenyelenggaraanController::class, 'show'])
        ->name('kerja-penyelenggaraan.show')
        ->middleware('permission:kerja_penyelenggaraan,read');
    Route::get('kerja-penyelenggaraan/{kerjaPenyelenggaraan}/edit', [App\Http\Controllers\KerjaPenyelenggaraanController::class, 'edit'])
        ->name('kerja-penyelenggaraan.edit')
        ->middleware('permission:kerja_penyelenggaraan,update');
    Route::put('kerja-penyelenggaraan/{kerjaPenyelenggaraan}', [App\Http\Controllers\KerjaPenyelenggaraanController::class, 'update'])
        ->name('kerja-penyelenggaraan.update')
        ->middleware('permission:kerja_penyelenggaraan,update');
    Route::delete('kerja-penyelenggaraan/{kerjaPenyelenggaraan}', [App\Http\Controllers\KerjaPenyelenggaraanController::class, 'destroy'])
        ->name('kerja-penyelenggaraan.destroy')
        ->middleware('permission:kerja_penyelenggaraan,delete');
});

// Laporan Penyelenggaraan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-penyelenggaraan', [App\Http\Controllers\LaporanPenyelenggaraanController::class, 'index'])
        ->name('laporan-penyelenggaraan.index')
        ->middleware('permission:laporan_penyelenggaraan,read');
});

// ═══════════════════════════════════════════════════════════════
// PENYUSUTAN & NILAI ROUTES
// ═══════════════════════════════════════════════════════════════

// Jadual Penyusutan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('jadual-penyusutan', [App\Http\Controllers\JadualPenyusutanController::class, 'index'])
        ->name('jadual-penyusutan.index')
        ->middleware('permission:jadual_penyusutan,read');
    Route::get('jadual-penyusutan/create', [App\Http\Controllers\JadualPenyusutanController::class, 'create'])
        ->name('jadual-penyusutan.create')
        ->middleware('permission:jadual_penyusutan,create');
    Route::post('jadual-penyusutan', [App\Http\Controllers\JadualPenyusutanController::class, 'store'])
        ->name('jadual-penyusutan.store')
        ->middleware('permission:jadual_penyusutan,create');
    Route::get('jadual-penyusutan/{jadualPenyusutan}', [App\Http\Controllers\JadualPenyusutanController::class, 'show'])
        ->name('jadual-penyusutan.show')
        ->middleware('permission:jadual_penyusutan,read');
    Route::get('jadual-penyusutan/{jadualPenyusutan}/edit', [App\Http\Controllers\JadualPenyusutanController::class, 'edit'])
        ->name('jadual-penyusutan.edit')
        ->middleware('permission:jadual_penyusutan,update');
    Route::put('jadual-penyusutan/{jadualPenyusutan}', [App\Http\Controllers\JadualPenyusutanController::class, 'update'])
        ->name('jadual-penyusutan.update')
        ->middleware('permission:jadual_penyusutan,update');
    Route::delete('jadual-penyusutan/{jadualPenyusutan}', [App\Http\Controllers\JadualPenyusutanController::class, 'destroy'])
        ->name('jadual-penyusutan.destroy')
        ->middleware('permission:jadual_penyusutan,delete');
});

// Nilai Semasa Aset Routes (Read Only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('nilai-semasa-aset', [App\Http\Controllers\NilaiSemasaAsetController::class, 'index'])
        ->name('nilai-semasa-aset.index')
        ->middleware('permission:nilai_semasa,read');
});

// Trend Penyusutan Routes (Read Only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('trend-penyusutan', [App\Http\Controllers\TrendPenyusutanController::class, 'index'])
        ->name('trend-penyusutan.index')
        ->middleware('permission:trend_penyusutan,read');
});

// ═══════════════════════════════════════════════════════════════
// PELUPUSAN ASET ROUTES
// ═══════════════════════════════════════════════════════════════

// Permohonan Pelupusan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('permohonan-pelupusan', [App\Http\Controllers\PermohonanPelupusanController::class, 'index'])
        ->name('permohonan-pelupusan.index')
        ->middleware('permission:permohonan_pelupusan,read');
    Route::get('permohonan-pelupusan/create', [App\Http\Controllers\PermohonanPelupusanController::class, 'create'])
        ->name('permohonan-pelupusan.create')
        ->middleware('permission:permohonan_pelupusan,create');
    Route::post('permohonan-pelupusan', [App\Http\Controllers\PermohonanPelupusanController::class, 'store'])
        ->name('permohonan-pelupusan.store')
        ->middleware('permission:permohonan_pelupusan,create');
    Route::get('permohonan-pelupusan/{permohonanPelupusan}', [App\Http\Controllers\PermohonanPelupusanController::class, 'show'])
        ->name('permohonan-pelupusan.show')
        ->middleware('permission:permohonan_pelupusan,read');
    Route::get('permohonan-pelupusan/{permohonanPelupusan}/edit', [App\Http\Controllers\PermohonanPelupusanController::class, 'edit'])
        ->name('permohonan-pelupusan.edit')
        ->middleware('permission:permohonan_pelupusan,update');
    Route::put('permohonan-pelupusan/{permohonanPelupusan}', [App\Http\Controllers\PermohonanPelupusanController::class, 'update'])
        ->name('permohonan-pelupusan.update')
        ->middleware('permission:permohonan_pelupusan,update');
    Route::delete('permohonan-pelupusan/{permohonanPelupusan}', [App\Http\Controllers\PermohonanPelupusanController::class, 'destroy'])
        ->name('permohonan-pelupusan.destroy')
        ->middleware('permission:permohonan_pelupusan,delete');
});

// Kelulusan Pelupusan Routes (Workflow)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('kelulusan-pelupusan', [App\Http\Controllers\KelulusanPelupusanController::class, 'index'])
        ->name('kelulusan-pelupusan.index')
        ->middleware('permission:kelulusan_pelupusan,read');
    Route::get('kelulusan-pelupusan/{permohonanPelupusan}', [App\Http\Controllers\KelulusanPelupusanController::class, 'show'])
        ->name('kelulusan-pelupusan.show')
        ->middleware('permission:kelulusan_pelupusan,read');
    Route::post('kelulusan-pelupusan/{permohonanPelupusan}/approve', [App\Http\Controllers\KelulusanPelupusanController::class, 'approve'])
        ->name('kelulusan-pelupusan.approve')
        ->middleware('permission:kelulusan_pelupusan,approve');
    Route::post('kelulusan-pelupusan/{permohonanPelupusan}/reject', [App\Http\Controllers\KelulusanPelupusanController::class, 'reject'])
        ->name('kelulusan-pelupusan.reject')
        ->middleware('permission:kelulusan_pelupusan,reject');
    Route::post('kelulusan-pelupusan/{permohonanPelupusan}/complete', [App\Http\Controllers\KelulusanPelupusanController::class, 'complete'])
        ->name('kelulusan-pelupusan.complete')
        ->middleware('permission:kelulusan_pelupusan,approve');
});

// Rekod Pelupusan Routes (Read Only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('rekod-pelupusan', [App\Http\Controllers\RekodPelupusanController::class, 'index'])
        ->name('rekod-pelupusan.index')
        ->middleware('permission:rekod_pelupusan,read');
    Route::get('rekod-pelupusan/{permohonanPelupusan}', [App\Http\Controllers\RekodPelupusanController::class, 'show'])
        ->name('rekod-pelupusan.show')
        ->middleware('permission:rekod_pelupusan,read');
});

// ============================================
// OPERASI MODULE - PROGRAM & PENDIDIKAN
// ============================================

// Senarai Program Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('senarai-program', [App\Http\Controllers\SenaraiProgramController::class, 'index'])
        ->name('senarai-program.index')
        ->middleware('permission:senarai_program,read');
    Route::get('senarai-program/create', [App\Http\Controllers\SenaraiProgramController::class, 'create'])
        ->name('senarai-program.create')
        ->middleware('permission:senarai_program,create');
    Route::post('senarai-program', [App\Http\Controllers\SenaraiProgramController::class, 'store'])
        ->name('senarai-program.store')
        ->middleware('permission:senarai_program,create');
    Route::get('senarai-program/{senaraiProgram}', [App\Http\Controllers\SenaraiProgramController::class, 'show'])
        ->name('senarai-program.show')
        ->middleware('permission:senarai_program,read');
    Route::get('senarai-program/{senaraiProgram}/edit', [App\Http\Controllers\SenaraiProgramController::class, 'edit'])
        ->name('senarai-program.edit')
        ->middleware('permission:senarai_program,update');
    Route::put('senarai-program/{senaraiProgram}', [App\Http\Controllers\SenaraiProgramController::class, 'update'])
        ->name('senarai-program.update')
        ->middleware('permission:senarai_program,update');
    Route::delete('senarai-program/{senaraiProgram}', [App\Http\Controllers\SenaraiProgramController::class, 'destroy'])
        ->name('senarai-program.destroy')
        ->middleware('permission:senarai_program,delete');
});

// Jadual Program Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('jadual-program', [App\Http\Controllers\JadualProgramController::class, 'index'])
        ->name('jadual-program.index')
        ->middleware('permission:jadual_program,read');
    Route::get('jadual-program/create', [App\Http\Controllers\JadualProgramController::class, 'create'])
        ->name('jadual-program.create')
        ->middleware('permission:jadual_program,create');
    Route::post('jadual-program', [App\Http\Controllers\JadualProgramController::class, 'store'])
        ->name('jadual-program.store')
        ->middleware('permission:jadual_program,create');
    Route::get('jadual-program/{jadualProgram}', [App\Http\Controllers\JadualProgramController::class, 'show'])
        ->name('jadual-program.show')
        ->middleware('permission:jadual_program,read');
    Route::get('jadual-program/{jadualProgram}/edit', [App\Http\Controllers\JadualProgramController::class, 'edit'])
        ->name('jadual-program.edit')
        ->middleware('permission:jadual_program,update');
    Route::put('jadual-program/{jadualProgram}', [App\Http\Controllers\JadualProgramController::class, 'update'])
        ->name('jadual-program.update')
        ->middleware('permission:jadual_program,update');
    Route::delete('jadual-program/{jadualProgram}', [App\Http\Controllers\JadualProgramController::class, 'destroy'])
        ->name('jadual-program.destroy')
        ->middleware('permission:jadual_program,delete');
});

// Pendaftaran Peserta Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pendaftaran-peserta', [App\Http\Controllers\PendaftaranPesertaController::class, 'index'])
        ->name('pendaftaran-peserta.index')
        ->middleware('permission:pendaftaran_peserta,read');
    Route::get('pendaftaran-peserta/create', [App\Http\Controllers\PendaftaranPesertaController::class, 'create'])
        ->name('pendaftaran-peserta.create')
        ->middleware('permission:pendaftaran_peserta,create');
    Route::post('pendaftaran-peserta', [App\Http\Controllers\PendaftaranPesertaController::class, 'store'])
        ->name('pendaftaran-peserta.store')
        ->middleware('permission:pendaftaran_peserta,create');
    Route::get('pendaftaran-peserta/{pendaftaranPeserta}', [App\Http\Controllers\PendaftaranPesertaController::class, 'show'])
        ->name('pendaftaran-peserta.show')
        ->middleware('permission:pendaftaran_peserta,read');
    Route::get('pendaftaran-peserta/{pendaftaranPeserta}/edit', [App\Http\Controllers\PendaftaranPesertaController::class, 'edit'])
        ->name('pendaftaran-peserta.edit')
        ->middleware('permission:pendaftaran_peserta,update');
    Route::put('pendaftaran-peserta/{pendaftaranPeserta}', [App\Http\Controllers\PendaftaranPesertaController::class, 'update'])
        ->name('pendaftaran-peserta.update')
        ->middleware('permission:pendaftaran_peserta,update');
    Route::delete('pendaftaran-peserta/{pendaftaranPeserta}', [App\Http\Controllers\PendaftaranPesertaController::class, 'destroy'])
        ->name('pendaftaran-peserta.destroy')
        ->middleware('permission:pendaftaran_peserta,delete');
});

// Laporan Program Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-program', [App\Http\Controllers\LaporanProgramController::class, 'index'])
        ->name('laporan-program.index')
        ->middleware('permission:laporan_program,read');
});

// ============================================
// OPERASI MODULE - JADUAL TUGAS
// ============================================

// Senarai Penceramah Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('senarai-penceramah', [App\Http\Controllers\SenaraiPenceramahController::class, 'index'])
        ->name('senarai-penceramah.index')
        ->middleware('permission:senarai_penceramah,read');
    Route::get('senarai-penceramah/create', [App\Http\Controllers\SenaraiPenceramahController::class, 'create'])
        ->name('senarai-penceramah.create')
        ->middleware('permission:senarai_penceramah,create');
    Route::post('senarai-penceramah', [App\Http\Controllers\SenaraiPenceramahController::class, 'store'])
        ->name('senarai-penceramah.store')
        ->middleware('permission:senarai_penceramah,create');
    Route::get('senarai-penceramah/{senaraiPenceramah}', [App\Http\Controllers\SenaraiPenceramahController::class, 'show'])
        ->name('senarai-penceramah.show')
        ->middleware('permission:senarai_penceramah,read');
    Route::get('senarai-penceramah/{senaraiPenceramah}/edit', [App\Http\Controllers\SenaraiPenceramahController::class, 'edit'])
        ->name('senarai-penceramah.edit')
        ->middleware('permission:senarai_penceramah,update');
    Route::put('senarai-penceramah/{senaraiPenceramah}', [App\Http\Controllers\SenaraiPenceramahController::class, 'update'])
        ->name('senarai-penceramah.update')
        ->middleware('permission:senarai_penceramah,update');
    Route::delete('senarai-penceramah/{senaraiPenceramah}', [App\Http\Controllers\SenaraiPenceramahController::class, 'destroy'])
        ->name('senarai-penceramah.destroy')
        ->middleware('permission:senarai_penceramah,delete');
});

// Jadual Ceramah Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('jadual-ceramah', [App\Http\Controllers\JadualCeramahController::class, 'index'])
        ->name('jadual-ceramah.index')
        ->middleware('permission:jadual_ceramah,read');
    Route::get('jadual-ceramah/create', [App\Http\Controllers\JadualCeramahController::class, 'create'])
        ->name('jadual-ceramah.create')
        ->middleware('permission:jadual_ceramah,create');
    Route::post('jadual-ceramah', [App\Http\Controllers\JadualCeramahController::class, 'store'])
        ->name('jadual-ceramah.store')
        ->middleware('permission:jadual_ceramah,create');
    Route::get('jadual-ceramah/{jadualCeramah}', [App\Http\Controllers\JadualCeramahController::class, 'show'])
        ->name('jadual-ceramah.show')
        ->middleware('permission:jadual_ceramah,read');
    Route::get('jadual-ceramah/{jadualCeramah}/edit', [App\Http\Controllers\JadualCeramahController::class, 'edit'])
        ->name('jadual-ceramah.edit')
        ->middleware('permission:jadual_ceramah,update');
    Route::put('jadual-ceramah/{jadualCeramah}', [App\Http\Controllers\JadualCeramahController::class, 'update'])
        ->name('jadual-ceramah.update')
        ->middleware('permission:jadual_ceramah,update');
    Route::delete('jadual-ceramah/{jadualCeramah}', [App\Http\Controllers\JadualCeramahController::class, 'destroy'])
        ->name('jadual-ceramah.destroy')
        ->middleware('permission:jadual_ceramah,delete');
    Route::post('jadual-ceramah/{jadualCeramah}/bayar', [App\Http\Controllers\JadualCeramahController::class, 'bayar'])
        ->name('jadual-ceramah.bayar')
        ->middleware('permission:jadual_ceramah,update');
});

// Jadual Imam & Bilal Routes (Combined)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('jadual-imam-bilal', [App\Http\Controllers\JadualImamBilalController::class, 'index'])
        ->name('jadual-imam-bilal.index')
        ->middleware('permission:jadual_imam_bilal,read');
    Route::get('jadual-imam-bilal/create', [App\Http\Controllers\JadualImamBilalController::class, 'create'])
        ->name('jadual-imam-bilal.create')
        ->middleware('permission:jadual_imam_bilal,create');
    Route::get('jadual-imam-bilal/auto-generate', [App\Http\Controllers\JadualImamBilalController::class, 'showAutoGenerateForm'])
        ->name('jadual-imam-bilal.auto-generate')
        ->middleware('permission:jadual_imam_bilal,create');
    Route::post('jadual-imam-bilal/auto-generate', [App\Http\Controllers\JadualImamBilalController::class, 'autoGenerate'])
        ->name('jadual-imam-bilal.auto-generate.store')
        ->middleware('permission:jadual_imam_bilal,create');
    Route::get('jadual-imam-bilal/export-pdf', [App\Http\Controllers\JadualImamBilalController::class, 'exportPdf'])
        ->name('jadual-imam-bilal.export-pdf')
        ->middleware('permission:jadual_imam_bilal,read');
    Route::get('jadual-imam-bilal/bulan/{bulan}/{tahun}', [App\Http\Controllers\JadualImamBilalController::class, 'showMonth'])
        ->name('jadual-imam-bilal.show-month')
        ->middleware('permission:jadual_imam_bilal,read');
    Route::delete('jadual-imam-bilal/delete-month/{bulan}/{tahun}', [App\Http\Controllers\JadualImamBilalController::class, 'destroyMonth'])
        ->name('jadual-imam-bilal.destroy-month')
        ->middleware('permission:jadual_imam_bilal,delete');
    Route::post('jadual-imam-bilal', [App\Http\Controllers\JadualImamBilalController::class, 'store'])
        ->name('jadual-imam-bilal.store')
        ->middleware('permission:jadual_imam_bilal,create');
    Route::get('jadual-imam-bilal/{jadualImamBilal}', [App\Http\Controllers\JadualImamBilalController::class, 'show'])
        ->name('jadual-imam-bilal.show')
        ->middleware('permission:jadual_imam_bilal,read');
    Route::get('jadual-imam-bilal/{jadualImamBilal}/edit', [App\Http\Controllers\JadualImamBilalController::class, 'edit'])
        ->name('jadual-imam-bilal.edit')
        ->middleware('permission:jadual_imam_bilal,update');
    Route::put('jadual-imam-bilal/{jadualImamBilal}', [App\Http\Controllers\JadualImamBilalController::class, 'update'])
        ->name('jadual-imam-bilal.update')
        ->middleware('permission:jadual_imam_bilal,update');
    Route::delete('jadual-imam-bilal/{jadualImamBilal}', [App\Http\Controllers\JadualImamBilalController::class, 'destroy'])
        ->name('jadual-imam-bilal.destroy')
        ->middleware('permission:jadual_imam_bilal,delete');
});

// Laporan Tugas Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-tugas', [App\Http\Controllers\LaporanTugasController::class, 'index'])
        ->name('laporan-tugas.index')
        ->middleware('permission:laporan_tugas,read');
});

// ============================================
// OPERASI MODULE - KHIDMAT KOMUNITI
// ============================================

// Urusan Jenazah Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('urusan-jenazah', [App\Http\Controllers\UrusanJenazahController::class, 'index'])
        ->name('urusan-jenazah.index')
        ->middleware('permission:urusan_jenazah,read');
    Route::get('urusan-jenazah/create', [App\Http\Controllers\UrusanJenazahController::class, 'create'])
        ->name('urusan-jenazah.create')
        ->middleware('permission:urusan_jenazah,create');
    Route::post('urusan-jenazah', [App\Http\Controllers\UrusanJenazahController::class, 'store'])
        ->name('urusan-jenazah.store')
        ->middleware('permission:urusan_jenazah,create');
    Route::get('urusan-jenazah/{urusanJenazah}', [App\Http\Controllers\UrusanJenazahController::class, 'show'])
        ->name('urusan-jenazah.show')
        ->middleware('permission:urusan_jenazah,read');
    Route::get('urusan-jenazah/{urusanJenazah}/edit', [App\Http\Controllers\UrusanJenazahController::class, 'edit'])
        ->name('urusan-jenazah.edit')
        ->middleware('permission:urusan_jenazah,update');
    Route::put('urusan-jenazah/{urusanJenazah}', [App\Http\Controllers\UrusanJenazahController::class, 'update'])
        ->name('urusan-jenazah.update')
        ->middleware('permission:urusan_jenazah,update');
    Route::delete('urusan-jenazah/{urusanJenazah}', [App\Http\Controllers\UrusanJenazahController::class, 'destroy'])
        ->name('urusan-jenazah.destroy')
        ->middleware('permission:urusan_jenazah,delete');
});

// Laporan Khidmat Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('laporan-khidmat', [App\Http\Controllers\LaporanKhidmatController::class, 'index'])
        ->name('laporan-khidmat.index')
        ->middleware('permission:laporan_khidmat,read');
});
