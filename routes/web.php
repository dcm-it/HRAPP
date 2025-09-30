<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes dengan middleware "auth"
Route::middleware(['auth'])->group(function () {

    /**
     * Pengajuan Cuti (Leave Requests)
     */
    Route::prefix('leave-requests')->name('leave.')->group(function () {
        Route::get('/', [LeaveRequestController::class, 'index'])->name('index');
        Route::post('/', [LeaveRequestController::class, 'store'])->name('store');

        // Form create & edit
        Route::get('/create', [LeaveRequestController::class, 'create'])->name('create');
        Route::get('/{leave}/edit', [LeaveRequestController::class, 'edit'])->name('edit');

        // Update & Delete
        Route::patch('/{leave}', [LeaveRequestController::class, 'update'])->name('update');
        Route::delete('/{leave}', [LeaveRequestController::class, 'destroy'])->name('destroy');

        // Update status (khusus HRD/Admin)
        Route::patch('/{leave}/status', [LeaveRequestController::class, 'updateStatus'])->name('updateStatus');
    });

    /**
     * Profile
     */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// Auth routes dari Breeze/Fortify
require __DIR__ . '/auth.php';
