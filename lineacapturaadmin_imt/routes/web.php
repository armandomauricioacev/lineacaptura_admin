<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========== RUTAS DE DEPENDENCIAS ==========
Route::get('/dependencias', [AdminController::class, 'dependenciasIndex'])
    ->middleware(['auth', 'verified'])->name('dependencias.index');

Route::post('/dependencias', [AdminController::class, 'dependenciaStore'])
    ->middleware(['auth', 'verified'])->name('dependencias.store');

Route::put('/dependencias/{dependencia}', [AdminController::class, 'dependenciaUpdate'])
    ->middleware(['auth', 'verified'])->name('dependencias.update');

Route::delete('/dependencias/{dependencia}', [AdminController::class, 'dependenciaDestroy'])
    ->middleware(['auth', 'verified'])->name('dependencias.destroy');

// ========== RUTAS DE TRÁMITES ==========
Route::get('/tramites', [AdminController::class, 'tramitesIndex'])
    ->middleware(['auth', 'verified'])->name('tramites');

Route::post('/tramites', [AdminController::class, 'tramitesStore'])
    ->middleware(['auth', 'verified'])->name('tramites.store');

Route::get('/tramites/{tramite}/edit', [AdminController::class, 'tramitesEdit'])
    ->middleware(['auth', 'verified'])->name('tramites.edit');

Route::put('/tramites/{tramite}', [AdminController::class, 'tramitesUpdate'])
    ->middleware(['auth', 'verified'])->name('tramites.update');

Route::delete('/tramites/{tramite}', [AdminController::class, 'tramitesDestroy'])
    ->middleware(['auth', 'verified'])->name('tramites.destroy');

// ========== RUTAS DE EXCEL ==========
Route::post('/excel/upload-tramites', [ExcelController::class, 'uploadTramites'])
    ->middleware(['auth', 'verified'])->name('excel.upload-tramites');

// ========== RUTAS DE LÍNEAS DE CAPTURA ==========
Route::get('/lineas-captura', [AdminController::class, 'lineasCapturadasIndex'])
    ->middleware(['auth', 'verified'])->name('lineas-captura');

// IMPORTANTE: Esta ruta debe ir ANTES de la ruta con {linea}
// Ruta para eliminar líneas filtradas
Route::delete('/lineas-captura/delete-filtered', [AdminController::class, 'lineasCapturaDeleteFiltered'])
    ->middleware(['auth', 'verified'])->name('lineas-captura.delete-filtered');

// Ruta para eliminar línea individual
Route::delete('/lineas-captura/{linea}', [AdminController::class, 'lineaCapturaDestroy'])
    ->middleware(['auth', 'verified'])->name('lineas-captura.destroy');

// ========== RUTAS DE PERFIL ==========
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';