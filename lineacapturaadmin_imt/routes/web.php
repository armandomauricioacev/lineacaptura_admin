<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dependencias', [AdminController::class, 'dependenciasIndex'])
    ->middleware(['auth', 'verified'])->name('dependencias.index');

Route::put('/dependencias/{dependencia}', [AdminController::class, 'dependenciaUpdate'])
    ->middleware(['auth', 'verified'])->name('dependencias.update');

Route::delete('/dependencias/{dependencia}', [AdminController::class, 'dependenciaDestroy'])
    ->middleware(['auth', 'verified'])->name('dependencias.destroy');

Route::get('/tramites', [AdminController::class, 'tramitesIndex'])
    ->middleware(['auth', 'verified'])->name('tramites');

Route::put('/tramites/{tramite}', [AdminController::class, 'tramitesUpdate'])
    ->middleware(['auth', 'verified'])->name('tramites.update');

Route::delete('/tramites/{tramite}', [AdminController::class, 'tramitesDestroy'])
    ->middleware(['auth', 'verified'])->name('tramites.destroy');

Route::get('/lineas-captura', [AdminController::class, 'lineasCapturadasIndex'])
    ->middleware(['auth', 'verified'])->name('lineas-captura');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
