<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImagingServeController;
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

// ─── Imaging (serve dengan autentikasi) ────────────────────────────────────────
// Hanya Radiografer dan Dokter Radiologi yang diizinkan melihat file imaging.
Route::get('/imaging/{imaging}', [ImagingServeController::class, 'show'])
    ->middleware(['auth', 'role:radiografer,dokter_radiologi'])
    ->name('imaging.show');

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

        // Pasien (AJAX / Store)
        Route::post('/pasien', [\App\Http\Controllers\Radiografer\PatientController::class, 'store'])->name('patients.store');

        // Imaging
        Route::post('/pemeriksaan/{examination}/imaging', [ImagingController::class, 'store'])->name('examinations.imaging.store');
        Route::post('/pemeriksaan/{examination}/kirim-interpretasi', [ImagingController::class, 'sendForInterpretation'])->name('examinations.send-interpretation');
    });

// ─── Dokter Radiologi ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:dokter_radiologi'])
    ->prefix('dokter-radiologi')
    ->name('dokter-radiologi.')
    ->group(function () {
        // Dashboard Dokter Radiologi (Landing Page)
        Route::get('/dashboard', [\App\Http\Controllers\DokterRadiologi\DashboardController::class, 'index'])->name('dashboard');

        // Worklist Pemeriksaan Aktif
        Route::get('/worklist', [\App\Http\Controllers\DokterRadiologi\WorklistController::class, 'index'])->name('worklist.index');

        // Riwayat Interpretasi Saya (Personal)
        Route::get('/riwayat', [\App\Http\Controllers\DokterRadiologi\HistoryController::class, 'index'])->name('history.index');

        // Detail pemeriksaan (Read-only untuk FINAL, atau menuju interpretasi untuk aktif)
        Route::get('/pemeriksaan/{examination}', [\App\Http\Controllers\DokterRadiologi\InterpretationController::class, 'show'])->name('examinations.show');

        // Form interpretasi
        Route::get('/pemeriksaan/{examination}/interpretasi', [\App\Http\Controllers\DokterRadiologi\InterpretationController::class, 'create'])->name('interpretations.create');

        // Simpan draft
        Route::post('/pemeriksaan/{examination}/interpretasi/draft', [\App\Http\Controllers\DokterRadiologi\InterpretationController::class, 'storeDraft'])->name('interpretations.store-draft');

        // Finalisasi
        Route::post('/pemeriksaan/{examination}/interpretasi/finalisasi', [\App\Http\Controllers\DokterRadiologi\InterpretationController::class, 'finalize'])->name('interpretations.finalize');
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
