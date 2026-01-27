<?php

use App\Http\Controllers\FaceRecognitionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WasteDetectionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Guest routes
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [FaceRecognitionController::class, 'showRegister'])->name('register');
    Route::post('/register/facial', [FaceRecognitionController::class, 'register'])->name('register.facial');
    
    // Login
    Route::get('/login', [FaceRecognitionController::class, 'showLogin'])->name('login');
    Route::post('/login/facial', [FaceRecognitionController::class, 'login'])->name('login.facial');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // Waste Detection
    Route::get('/waste', [WasteDetectionController::class, 'index'])->name('waste.index');
    Route::post('/waste/detect', [WasteDetectionController::class, 'store'])->name('waste.detect');
    Route::get('/scanner', function () {
        return view('waste.scanner');
    })->name('scanner');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports-map-data', [ReportController::class, 'getMapData'])->name('reports.map-data');
    
    // Map view
    Route::get('/map', function () {
        return view('reports.map');
    })->name('map');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/leaderboard/top-users', [LeaderboardController::class, 'getTopUsers'])->name('leaderboard.top-users');

    // Profile & Settings
    Route::post('/update-face', [FaceRecognitionController::class, 'updateFaceEncoding'])->name('update.face');



