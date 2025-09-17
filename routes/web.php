<?php

use App\Http\Controllers\KariahController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Kariah Resource Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('kariah', KariahController::class);
    Route::get('/kariah-export', [KariahController::class, 'export'])->name('kariah.export');
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



    Route::get('/bantuan/nota-keluaran', function () {
        return view('bantuan.nota-keluaran');
    })->name('bantuan.nota-keluaran');
});

// Test route for icons and checkbox styling
Route::get('/test-icons', function () {
    return view('test-icons');
})->name('test-icons');

// Profile routes commented out - ProfileController not implemented yet
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php'; // This line was commented out
