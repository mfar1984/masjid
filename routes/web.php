<?php

use App\Http\Controllers\KariahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\WeatherConfigurationController;
use App\Http\Controllers\ApiConfigurationController;
use App\Http\Controllers\SanctumTokenController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentFolderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

// Pengurusan Dokumen Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Document management routes
    Route::get('/documents', [DocumentController::class, 'index'])->middleware('permission:documents,read')->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->middleware('permission:documents,create')->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->middleware('permission:documents,create')->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->middleware('permission:documents,read')->name('documents.show');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->middleware('permission:documents,update')->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->middleware('permission:documents,update')->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->middleware('permission:documents,delete')->name('documents.destroy');

    // Document actions
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::post('/documents/{document}/toggle-star', [DocumentController::class, 'toggleStar'])->name('documents.toggle-star');
    Route::post('/documents/{document}/share', [DocumentController::class, 'share'])->middleware('permission:documents,share')->name('documents.share');

    // Trash and spam actions
    Route::post('/documents/{document}/trash', [DocumentController::class, 'moveToTrash'])->middleware('permission:documents,delete')->name('documents.trash');
    Route::post('/documents/{document}/spam', [DocumentController::class, 'moveToSpam'])->middleware('permission:documents,delete')->name('documents.spam');
    Route::post('/documents/{document}/restore', [DocumentController::class, 'restore'])->middleware('permission:documents,update')->name('documents.restore');
    Route::delete('/documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])->middleware('permission:documents,delete')->name('documents.force-delete');

    // Folder management routes
    Route::post('/document-folders', [DocumentFolderController::class, 'store'])->middleware('permission:documents,create')->name('document-folders.store');
    Route::put('/document-folders/{folder}', [DocumentFolderController::class, 'update'])->middleware('permission:documents,update')->name('document-folders.update');
    Route::delete('/document-folders/{folder}', [DocumentFolderController::class, 'destroy'])->middleware('permission:documents,delete')->name('document-folders.destroy');
    Route::post('/document-folders/{folder}/toggle-star', [DocumentFolderController::class, 'toggleStar'])->name('document-folders.toggle-star');
    Route::post('/document-folders/{folder}/color', [DocumentFolderController::class, 'updateColor'])->middleware('permission:documents,update')->name('document-folders.color');

});

// Bantuan & Sokongan Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bantuan/panduan-pengguna', function () {
        return view('bantuan.panduan-pengguna');
    })->name('bantuan.panduan-pengguna');

    Route::get('/bantuan/faq', [App\Http\Controllers\FAQController::class, 'index'])->name('bantuan.faq');

    Route::get('/bantuan/hubungi-sokongan', function () {
        return view('bantuan.hubungi-sokongan');
    })->name('bantuan.hubungi-sokongan');




});



// Profile routes commented out - ProfileController not implemented yet
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php'; // This line was commented out
