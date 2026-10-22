<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Radiografer\ExaminationController;
use App\Http\Controllers\Radiografer\ImagingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Radiografer ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:radiografer'])
    ->prefix('radiografer')
    ->name('radiografer.')
    ->group(function () {
        // Pemeriksaan
        Route::get('/pemeriksaan', [ExaminationController::class, 'index'])->name('examinations.index');
        Route::get('/pemeriksaan/tambah', [ExaminationController::class, 'create'])->name('examinations.create');
        Route::post('/pemeriksaan', [ExaminationController::class, 'store'])->name('examinations.store');
        Route::get('/pemeriksaan/{examination}', [ExaminationController::class, 'show'])->name('examinations.show');

        // Imaging
        Route::post('/pemeriksaan/{examination}/imaging', [ImagingController::class, 'store'])->name('examinations.imaging.store');
        Route::post('/pemeriksaan/{examination}/kirim-interpretasi', [ImagingController::class, 'sendForInterpretation'])->name('examinations.send-interpretation');
    });

// ─── Dokter Radiologi (placeholder) ───────────────────────────────────────────
Route::middleware(['auth', 'role:dokter_radiologi'])
    ->prefix('dokter-radiologi')
    ->name('dokter-radiologi.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dokter-radiologi.dashboard');
        })->name('dashboard');
    });

// ─── Dokter Pengirim (placeholder) ────────────────────────────────────────────
Route::middleware(['auth', 'role:dokter_pengirim'])
    ->prefix('dokter-pengirim')
    ->name('dokter-pengirim.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dokter-pengirim.dashboard');
        })->name('dashboard');
    });
