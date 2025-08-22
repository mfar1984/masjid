<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'getWeather'])
    ->middleware('api')
    ->name('weather');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__.'/auth.php'; // This line was commented out
